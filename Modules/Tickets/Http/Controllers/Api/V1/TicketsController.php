<?php
   
namespace Modules\Tickets\Http\Controllers\Api\V1;
   
use Illuminate\Http\Request;
use Modules\Tickets\Events\TicketsCreated;
use Modules\Tickets\Events\TicketsDeleted;
use Modules\Tickets\Events\TicketsUpdated;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Tickets\Transformers\TicketsResource;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Base\Http\Controllers\Api\BaseController;
use Modules\Tickets\Http\Requests\ApiStoreTicketRequest;
use Modules\Tickets\Http\Requests\ApiUpdateTicketRequest;

/**
 * @group Tickets endpoints
 *
 * APIs for managing tickets
 */ 
class TicketsController extends BaseController
{   
    /**
     * @var TicketsRepository $tickets
     */
    protected $tickets;

    /**
     * @param TicketsRepository $tickets
     *
     * @return void
     */
    public function __construct(TicketsRepository $tickets) 
    {
        $this->tickets = $tickets;
    }

    /**
     * Get all tickets
     *
     * @authenticated
     * @response status=200 scenario="Success" {
     *    "success": true,
     *    "message": "All tickets records.",
     *    "data": [
     *        {
     *            "id": 1,
     *            "number": "number value",
     *            "user_id": 1,
     *            "user": {
     *                  "id": "1",
     *                  "name": "",
     *                  "first_name": "Ark",
     *                  "last_name": "Admin",
     *                  "email": "admin@domain.com",
     *                  "username": "admin",
     *                  "email_verified_at": "2022-07-14T03:55:22.000000Z",
     *                  "authy_status": "",
     *                  "authy_country_code": "",
     *                  "authy_phone": "",
     *                  "avatar": "",
     *                  "timezone": "UTC",
     *                  "timezone_display": "(UTC+00:00) UTC",
     *                  "date_format": "M j, Y h:i A",
     *                  "time_format": "12",
     *                  "locale": "en",
     *                  "currency": "USD",
     *                  "country_id": "",
     *                  "last_login": "2022-08-19 11:16:50",
     *                  "last_logout": "",
     *                  "last_activity": "2022-08-19 15:05:30",
     *                  "status": "Active",
     *                  "invited": "",
     *                  "created_at": "2022-07-14T03:55:22.000000Z",
     *                  "updated_at": "2022-07-21T09:18:55.000000Z",
     *                  "full_name": "Ark Admin",
     *                  "last_login_for_humans": "3 hours ago",
     *                  "created_at_for_humans": "Jul 14, 2022 03:55 AM",
     *                  "can_impersonate": "1",
     *                  "can_be_impersonated": "",
     *            },
     *            "category_id": 1,
     *            "category": {
     *                  "id": "1",
     *                  "name": "Support",
     *                  "description": "",
     *                  "color": "#17abc9",
     *                  "created_at": "2022-08-19T13:08:47.000000Z",
     *                  "updated_at": "2022-08-19T14:08:52.000000Z",
     *                  "deleted_at": "",
     *                  "category_type_id": "1",
     *                  "parent_id": "",
     *            },
     *            "subject": "subject value",
     *            "priority": "priority value",
     *            "message": "message value",
     *            "attachments": [
     *            	{
     *                  "model_type": "Modules\ModuleName\Models\ModelName",
     *                  "model_id": "1",
     *                  "uuid": "7b92dd82-7148-43b9-b54b-2561b55f1037",
     *                  "collection_name": "attachments",
     *                  "name": "file_name",
     *                  "file_name": "file_name.jpg",
     *                  "mime_type": "image/jpeg",
     *                  "disk": "public",
     *                  "conversions_disk": "public",
     *                  "size": "1000",
     *                  "manipulations": "[]",
     *                  "custom_properties": "[]",
     *                  "generated_conversions": "[]",
     *                  "responsive_images": "[]",
     *                  "order_column": "1",
     *            	},
     *            ],
     *            "status": "status value",
     *            "rating": "rating value",
     *            "created_at": "2022-06-05T05:07:47.000000Z",
     *            "updated_at": "2022-06-05T05:07:47.000000Z"
     *        },...
     *  ]
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * @response status=403 scenario="Unauthorized" {
     *       "message": "This action is unauthorized."
     * }
     * 
     * @return JsonResource
     */
    public function index()
    {
        authorize([
            'user.tickets.index', 
            'admin.tickets.index'
        ]);

        $model = $this->tickets->all();

        $model->load(['user', 'category']);
        
        return $this->successResponse(
            __('All tickets records.'), 
            TicketsResource::collection($model)
        );
    }

    /**
     * Store new ticket.
     * 
     * @authenticated
     * @bodyParam number integer required The number of the ticket.
     * @bodyParam user_id integer optional The user_id of the ticket.
     * @bodyParam category_id integer optional The category_id of the ticket.
     * @bodyParam subject string required The subject of the ticket.
     * @bodyParam priority string required The priority of the ticket.
     * @bodyParam message string required The message of the ticket.
     * @bodyParam attachments file[] optional The attachments of the ticket.
     * @bodyParam status string required The status of the ticket.
     * @bodyParam rating string optional The rating of the ticket.
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Ticket created successfully."
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The number field is required.",
     *      "errors": {    
     *          "number": [
     *              "The number field is required."
     *          ],    
     *          "subject": [
     *              "The subject field is required."
     *          ],    
     *          "priority": [
     *              "The priority field is required."
     *          ],    
     *          "message": [
     *              "The message field is required."
     *          ],    
     *          "status": [
     *              "The status field is required."
     *          ],
     *      }
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * @response status=403 scenario="Unauthorized" {
     *       "message": "This action is unauthorized."
     * }
     * 
     * 
     * @param ApiStoreTicketRequest $request
     * @return JsonResource
     */    
    public function store(ApiStoreTicketRequest $request)
    {
        $model = $this->tickets->create($request->only('number', 'user_id', 'category_id', 'subject', 'priority', 'message', 'status', 'rating'));

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('attachments');
                }
            }

        }

        event(new TicketsCreated($model));

        return $this->successResponse(__('Ticket created successfully.'));
    }

    /**
     * Show the specified ticket.
     * 
     * @authenticated
     * @urlParam id integer The ID of the ticket.
     * 
     * @response status=200 scenario="Success" {
     *    "success": true,
     *    "message": "Ticket fetched successfully.",
     *    "data": {
     *            "id": 1,
     *            "number": "number value",
     *            "user_id": 1,
     *            "user": {
     *                  "id": "1",
     *                  "name": "",
     *                  "first_name": "Ark",
     *                  "last_name": "Admin",
     *                  "email": "admin@domain.com",
     *                  "username": "admin",
     *                  "email_verified_at": "2022-07-14T03:55:22.000000Z",
     *                  "authy_status": "",
     *                  "authy_country_code": "",
     *                  "authy_phone": "",
     *                  "avatar": "",
     *                  "timezone": "UTC",
     *                  "timezone_display": "(UTC+00:00) UTC",
     *                  "date_format": "M j, Y h:i A",
     *                  "time_format": "12",
     *                  "locale": "en",
     *                  "currency": "USD",
     *                  "country_id": "",
     *                  "last_login": "2022-08-19 11:16:50",
     *                  "last_logout": "",
     *                  "last_activity": "2022-08-19 15:05:30",
     *                  "status": "Active",
     *                  "invited": "",
     *                  "created_at": "2022-07-14T03:55:22.000000Z",
     *                  "updated_at": "2022-07-21T09:18:55.000000Z",
     *                  "full_name": "Ark Admin",
     *                  "last_login_for_humans": "3 hours ago",
     *                  "created_at_for_humans": "Jul 14, 2022 03:55 AM",
     *                  "can_impersonate": "1",
     *                  "can_be_impersonated": "",
     *            },
     *            "category_id": 1,
     *            "category": {
     *                  "id": "1",
     *                  "name": "Support",
     *                  "description": "",
     *                  "color": "#17abc9",
     *                  "created_at": "2022-08-19T13:08:47.000000Z",
     *                  "updated_at": "2022-08-19T14:08:52.000000Z",
     *                  "deleted_at": "",
     *                  "category_type_id": "1",
     *                  "parent_id": "",
     *            },
     *            "subject": "subject value",
     *            "priority": "priority value",
     *            "message": "message value",
     *            "attachments": [
     *            	{
     *                  "model_type": "Modules\ModuleName\Models\ModelName",
     *                  "model_id": "1",
     *                  "uuid": "7b92dd82-7148-43b9-b54b-2561b55f1037",
     *                  "collection_name": "attachments",
     *                  "name": "file_name",
     *                  "file_name": "file_name.jpg",
     *                  "mime_type": "image/jpeg",
     *                  "disk": "public",
     *                  "conversions_disk": "public",
     *                  "size": "1000",
     *                  "manipulations": "[]",
     *                  "custom_properties": "[]",
     *                  "generated_conversions": "[]",
     *                  "responsive_images": "[]",
     *                  "order_column": "1",
     *            	},
     *            ],
     *            "status": "status value",
     *            "rating": "rating value",
     *            "created_at": "2022-06-05T05:07:47.000000Z",
     *            "updated_at": "2022-06-05T05:07:47.000000Z"
     *        }
     * }
     * @response status=404 scenario="Not Found" {
     *    "success": false,
     *    "message": "Resource Not Found"
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * @response status=403 scenario="Unauthorized" {
     *       "message": "This action is unauthorized."
     * }
     * 
     * 
     * @param id integer The ID of the ticket. 
     * @return JsonResource
     */
    public function show($id)
    {
        authorize([
            'user.tickets.show', 
            'admin.tickets.show'
        ]);

        $model = $this->tickets->find($id);

        if (is_null($model)) {
            return $this->errorNotFound();
        }

        $model->load(['user', 'category']);

        return $this->successResponse(
            __('Ticket fetched successfully.'), 
            new TicketsResource($model)
        );
    }
    
    /**
     * Update the specified ticket.
     * 
     * @authenticated
     * @urlParam id integer required The ID of the ticket.
     * @bodyParam number integer required The number of the ticket.
     * @bodyParam user_id integer optional The user_id of the ticket.
     * @bodyParam category_id integer optional The category_id of the ticket.
     * @bodyParam subject string required The subject of the ticket.
     * @bodyParam priority string required The priority of the ticket.
     * @bodyParam message string required The message of the ticket.
     * @bodyParam attachments file[] optional The attachments of the ticket.
     * @bodyParam status string required The status of the ticket.
     * @bodyParam rating string optional The rating of the ticket.
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Ticket updated successfully."
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The number field is required.",
     *      "errors": {    
     *          "number": [
     *              "The number field is required."
     *          ],    
     *          "subject": [
     *              "The subject field is required."
     *          ],    
     *          "priority": [
     *              "The priority field is required."
     *          ],    
     *          "message": [
     *              "The message field is required."
     *          ],    
     *          "status": [
     *              "The status field is required."
     *          ],
     *      }
     * }
     * @response status=404 scenario="Not Found" {
     *    "success": false,
     *    "message": "Resource Not Found"
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * @response status=403 scenario="Unauthorized" {
     *       "message": "This action is unauthorized."
     * }
     * 
     * 
     * @param ApiUpdateTicketRequest $request
     * @param integer $id
     * @return JsonResource
     */ 
    public function update(ApiUpdateTicketRequest $request, $id)
    {
        $model = $this->tickets->findOrFail($id);

        $this->tickets
            ->update($model, 
                $request->only('number', 'user_id', 'category_id', 'subject', 'priority', 'message', 'status', 'rating'));

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('attachments');
                }
            }

        }

        event(new TicketsUpdated($model));

        return $this->successResponse(__('Ticket updated successfully.'));
    }
    
    /**
     * Remove the specified ticket.
     * 
     * @authenticated
     * @urlParam id integer required The ID of the ticket.
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Ticket deleted successfully."
     * }
     * @response status=404 scenario="Not Found" {
     *    "success": false,
     *    "message": "Resource Not Found"
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * @response status=403 scenario="Unauthorized" {
     *       "message": "This action is unauthorized."
     * }
     * 
     * @param integer $id
     * @return JsonResource
     */ 
    public function destroy($id)
    {
        authorize([
            'user.tickets.delete', 
            'admin.tickets.delete'
        ]);

        $model = $this->tickets->findOrFail($id);

        $this->tickets->delete($model);
        
        event(new TicketsDeleted($model));
        
        return $this->successResponse(__('Ticket deleted successfully.'));
    }
}