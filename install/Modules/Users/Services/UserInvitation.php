<?php

namespace Modules\Users\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\EmailTemplates\Services\Mailer;

trait UserInvitation
{	
	/**
	 * @var Mailer
	 */
	protected $mailer;

	/**
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * @var string
	 */
	protected $templateName = 'Create user invitation';

	public function __construct(Mailer $mailer, UserRepository $users) 
	{
		$this->mailer = $mailer;
		$this->users = $users;
	}

	/**
	 * Sample Logic
	 * 
	 * 	if password provided and not check send invitation
	 *	 	then no need to send email
	 *  if password provided and checked send invitation
	 *	 	then send email
	 *	if password not provided and not check send invitation
	 *	 	then send email
	 *	if password not provided and checked send invitation
	 *	 	then send email
     *
	 * @param User $user
	 * @param Request $requeset
	 * 
	 * @return mixed
	 */
	public function sendUserInvitation($user, Request $request) 
	{	
		// Check the logic fo sending invitation
		if($request->get('password') && !$request->get('send_invitation')) {
			return;
		}

		$user->fresh();

		$role = $user->roles->first();
        
        return $this->mailer->template($this->templateName)
            ->to($request->get('email'))
            ->with([
                'first_name' => $request->get('first_name'),
                'user_role' => $role->name,
                'button_link' => config('app.url'),
                'password' => $this->handlePasswordGenerator($user, $request->get('password'))
            ])->send();
	}

	/**
	 * Handle generating password with logic if not password provided
	 * 
	 * @param User Model $user
	 * @param string|null $password
	 * 
	 * @return string
	 */
	protected function handlePasswordGenerator($user, $password) 
	{
		if ($password) {
			return $password;
		}

		return $this->handleUpdatePassword($user);
	}

	/**
	 * Handle updating generated password
	 * 
	 * @param User Model $user
	 * 
	 * @return string
	 */
	protected function handleUpdatePassword($user) 
	{
		$password = Str::random(8);

		$this->users->update($user, ['password' => $password]);

		return $password;
	}
}