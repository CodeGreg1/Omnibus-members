<?php

namespace Modules\EmailTemplates\Services;

use Illuminate\Support\Facades\Mail;
use Modules\EmailTemplates\Emails\EmailSender;
use Modules\Users\Repositories\UserRepository;
use Modules\EmailTemplates\Services\DefaultShortcodes;
use Modules\EmailTemplates\Repositories\EmailTemplateRepository;

class Mailer
{	
	/**
	 * @var EmailTemplateRepository
	 */
	protected $repository;

	/**
	 * @var UserRepository
	 */
	protected $user;

	/**
	 * @var EmailTemplateRepository|null
	 */
	protected $template;

	/**
	 * @var array $attributes
	 */
	protected $attributes = [];

	/**
	 * @var array $files
	 */
	protected $files = [];

	/**
	 * @var string $email
	 */
	protected $email;

	/**
	 * @var array $email
	 */
	protected $ccEmails = [];

	/**
	 * @var array $bccEmails
	 */
	protected $bccEmails = [];

	public function __construct() 
	{
		$this->repository = new EmailTemplateRepository;
		$this->user = new UserRepository;
	}

	/**
	 * Handle template
	 * 
	 * @param string $name
	 * 
	 * @return EmailTemplateRepository|null
	 */
	public function template($name) 
	{
		$this->template = $this->repository->findBy('name', $name);

		if (!$this->template) {
			throw new \Exception('Email template using name not foound.');
		}

		return $this;
	}

	/**
	 * To email
	 * 
	 * @param string $email
	 * 	email|admin|user
	 */
	public function to($email) 
	{	
		$email = strtolower($email);

		switch ($email) {
			case EMAIL_ADMINS: //for all user admins
				$admins = $this->user->allAdmins();

				$this->email = $admins->first()->email;

				foreach($admins as $admin) {
					if($this->email != $admin->email) {
						$this->bccEmails[] = $admin->email;
					}
				}

				break;
			case EMAIL_USERS: // for all users
				$users = $this->user->allUsers();

				if($users->count()) {
					$this->email = $users->first()->email;

					foreach($users as $user) {
						if($this->email != $user->email) {
							$this->bccEmails[] = $user->email;
						}
					}
				}
				
				break;
			
			default:

				$this->email = $email;

				break;
		}

		

		return $this;
	}

	/**
	 * CC emails
	 * 
	 * @param array $emails
	 */
	public function cc($emails) 
	{
		$this->ccEmails = $emails;

		return $this;
	}

	/**
	 * BCC emails
	 * 
	 * @param array $emails
	 */
	public function bcc($emails) 
	{
		$this->bccEmails = $emails;

		return $this;
	}

	/**
	 * Content defined variable using shortcode 
	 * 
	 * @param array $attributes
	 */
	public function with($attributes) 
	{
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * Attachement files
	 * 
	 * @param array $files
	 * 
	 * Sample: [
	 * 	public_path('upload/users/1629801575.png'),
	 *  public_path('upload/users/1629806576.png')
	 * ]
	 */
	public function attachments($files) 
	{
		$this->files = $files;

		return $this;
	}

	/**
	 * Handle sending email
	 */
	public function send() 
	{
		$content = $this->template->content;
		$subject = $this->template->subject;

		if (!$this->email) {
			throw new \Exception('No email address provided.');
		}

		// merge defined shortcodes with default shortcodes
		$attributes = array_merge(
			$this->attributes, 
			(new DefaultShortcodes($this->template))->get()
		);

		foreach ($attributes as $search=>$value) {
			$content = str_replace('{'.$search.'}', $value, $content);
			$subject = str_replace('{'.$search.'}', $value, $subject);
		}

		$mail = Mail::to($this->email);

		if($this->ccEmails) {
			$mail->cc($this->ccEmails);
		}

		if($this->bccEmails) {
			$mail->bcc($this->bccEmails);
		}

		$mail->send(new EmailSender([
			'content' => $content,
			'subject' => $subject,
			'attachments' => $this->files
		]));
		
	}
}