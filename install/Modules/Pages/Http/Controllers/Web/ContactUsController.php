<?php

namespace Modules\Pages\Http\Controllers\Web;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Modules\EmailTemplates\Services\Mailer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Pages\Http\Requests\SendContactUsRequest;

class ContactUsController extends BaseController
{
    public $mailer;

    public function __construct()
    {
        parent::__construct();

        $this->mailer = new Mailer;
    }

    /**
     * Send email
     * @param SendContactUsRequest $request
     * @return json
     */
    public function send(SendContactUsRequest $request)
    {
        $message = __("Thanks for contacting us. We'll get back to you as soon as possible.");

        $position = Location::get();

        $this->mailer->template('Contact us message')
            ->to(setting('app_email'))
            ->with([
                'user' => $request->get('name'),
                'email' => $request->get('email'),
                'message' => $request->get('message'),
                'country' => $position ? $position->countryName : ''
            ])
            ->send();

        if ($request->has('redirectTo')) {
            return $this->handleAjaxRedirectResponse($message, $request->get('redirectTo'));
        }

        return $this->successResponse($message);
    }
}
