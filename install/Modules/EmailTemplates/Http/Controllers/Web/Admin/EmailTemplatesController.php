<?php

namespace Modules\EmailTemplates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Modules\EmailTemplates\Support\Copy;
use Modules\EmailTemplates\Services\Mailer;
use Illuminate\Contracts\Support\Renderable;
use Modules\EmailTemplates\Emails\MyTestMail;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\EmailTemplates\Services\DefaultShortcodes;
use Modules\EmailTemplates\Support\ShortcodeExtractor;
use Modules\EmailTemplates\Events\EmailTemplatesCreated;
use Modules\EmailTemplates\Events\EmailTemplatesUpdated;
use Modules\EmailTemplates\Events\EmailTemplatesSentTest;
use Modules\EmailTemplates\Repositories\EmailTemplateRepository;
use Modules\EmailTemplates\Http\Requests\StoreEmailTemplateRequest;
use Modules\EmailTemplates\Http\Requests\UpdateEmailTemplateRequest;
use Modules\EmailTemplates\Http\Requests\SendTestEmailTemplateRequest;

class EmailTemplatesController extends BaseController
{   
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var EmailTemplateRepository
     */
    protected $emailTemplates;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/email-templates';

    public function __construct(Mailer $mailer, EmailTemplateRepository $emailTemplates) 
    {
        $this->mailer = $mailer;

        $this->emailTemplates = $emailTemplates;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.email-templates.index');

        return view('emailtemplates::admin.index', [
            'pageTitle' => __('Email Templates'),
            'policies' => JsPolicy::get('email-templates')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.email-templates.create');

        return view('emailtemplates::admin.create', [
            'pageTitle' => __('Create new email template')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreEmailTemplateRequest $request
     * @return JsonResponse
     */
    public function store(StoreEmailTemplateRequest $request)
    {
        $emailTemplates = $this->emailTemplates->create($request->only('code', 'name', 'subject', 'content'));

        event(new EmailTemplatesCreated($emailTemplates));

        return $this->handleAjaxRedirectResponse(
            __('Email template created successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.email-templates.edit');

        $emailTemplate = $this->emailTemplates->find($id);
        
        $shortcodes = (new ShortcodeExtractor)->extract($emailTemplate->content);

        return view('emailtemplates::admin.edit', [
            'pageTitle' => __('Edit email template'),
            'emailTemplate' => $emailTemplate,
            'shortcodes' => $shortcodes
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateEmailTemplateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateEmailTemplateRequest $request, $id)
    {
        $emailTemplate = $this->emailTemplates->find($id);

        $this->emailTemplates->update($emailTemplate, $request->only(
            'code', 
            'name', 
            'subject', 
            'content'
        ));

        event(new EmailTemplatesUpdated($emailTemplate));

        return $this->handleAjaxRedirectResponse(
            __('Email template updated successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Multi-delete email templates
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.email-templates.delete');

        $this->emailTemplates->multiDelete($request->ids);

        return $this->successResponse(__('Selected email template(s) deleted successfully.'));
    }

    /**
     * Test email template
     * @param int $id
     * @return Renderable
     */
    public function test()
    {
        $this->authorize('admin.email-templates.test');

        return view('emailtemplates::admin.test', [
            'pageTitle' => __('Send email test'),
            'emailTemplates' => $this->emailTemplates->orderBy('id', 'desc')->all()
        ]);
    }

    /**
     * Send email testing
     * 
     * @param SendTestEmailTemplateRequest $request
     * 
     * @return JsonResponse
     */
    public function send(SendTestEmailTemplateRequest $request) 
    {
        $emailTemplate = $this->emailTemplates->find($request->get('id'));

        $attributes = $this->handleParsingShortcode($request->get('shortcodes'));

        $this->mailer->template($emailTemplate->name)
            ->to($request->get('send_to'))
            ->with($attributes)->send();

        event(new EmailTemplatesSentTest($emailTemplate));

        return $this->successResponse(__('Email test sent successfully.'));
    }

    /**
     * Get email template for testing
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function get(Request $request) 
    {
        $this->authorize('admin.email-templates.get');

        $emailTemplate = $this->emailTemplates->find($request->get('id'));
        $shortcodes = (new ShortcodeExtractor)->extract($emailTemplate->content);
        $subjectShortcodes = (new ShortcodeExtractor)->extract($emailTemplate->subject);

        return $this->successResponse(__('Email template record.'), [
            'emailTemplate' => $emailTemplate,
            'definedShortcodes' => email_template_merge_shortcode([$shortcodes, $subjectShortcodes]),
            'defaultShortcodes' => (new DefaultShortcodes)->shortcodes(1),
            'defaultShortcodesValues' => (new DefaultShortcodes($emailTemplate))->get()
        ]);
    }

    /**
     * Handle parsing shortcode
     * 
     * @param string $shortcodes
     * 
     * @return array
     */
    protected function handleParsingShortcode($shortcodes) 
    {
        $result = [];

        foreach($shortcodes as $shortcode=>$value) {
            $origShortcode = $shortcode;
            $shortcode = str_replace('{', '', $shortcode);
            $shortcode = str_replace('}', '', $shortcode);

            $result[$shortcode] = $value ?? $origShortcode;
        }

        return $result;
    }
}
