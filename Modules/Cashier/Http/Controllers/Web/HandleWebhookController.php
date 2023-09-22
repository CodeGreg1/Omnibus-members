<?php

namespace Modules\Cashier\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Webhook;
use Modules\Cashier\Events\WebhookEventHandled;
use Modules\Base\Http\Controllers\Web\BaseController;

class HandleWebhookController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle incoming webhook events from gateway
     * @return Response
     */
    public function handle($shortcode, Request $request)
    {
        $service = Webhook::provider($shortcode);
        $payload = json_decode($request->getContent(), true);
        if ($service->verifyIPN($request)) {
            try {
                $response = $service->handle($payload, $request);
                WebhookEventHandled::dispatch($response['gateway'], $response['event']);
                return response()->json(['status' => 'Success'], 200);
            } catch (\Exception $e) {
                report($e);
                return response()->json(
                    [
                        'status' => 'Error',
                        'message' => $e->getMessage()
                    ],
                    401
                );
            }
        }

        return response()->json(
            [
                'status' => 'Error',
                'message' => __('Something went wrong')
            ],
            401
        );
    }
}