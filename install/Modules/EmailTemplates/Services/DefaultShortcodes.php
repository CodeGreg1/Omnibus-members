<?php

namespace Modules\EmailTemplates\Services;

use Illuminate\Support\Facades\Mail;
use Modules\EmailTemplates\Emails\EmailSender;
use Modules\EmailTemplates\Repositories\EmailTemplateRepository;

class DefaultShortcodes
{	
	/**
	 * @var \Modules\EmailTemplates\Models\EmailTemplate|null $template
	 */
	protected $template;
	/**
	 * @var array $default
	 */
	protected $default = [
		'app_name',
		'app_colored_logo',
		'app_white_logo',
		'app_url',
		'year',
		'code'
	];

	/**
	 * @param \Modules\EmailTemplates\Models\EmailTemplate $template
	 */
	public function __construct($template = null) 
	{
		$this->template = $template;
	}

	/**
	 * Generate shortcodes
	 * 
	 * @param string|null $curly
	 * 
	 * @return array
	 */
	public function shortcodes($curly=null) 
	{
		$result = [];

		if($curly == null) {
			return $this->default;
		} else {
			foreach($this->default as $shortcode) {
				$result[] = '{'.$shortcode.'}';
			}
		}
		
		return $result;
	}

	/**
	 * Handle parsing default shortcode with values
	 * 
	 * @return array
	 */
	public function get() 
	{
		$result = [];

		foreach($this->default as $shortcode) {
			if(in_array('app_name', $this->default)) {
				$result['app_name'] = $this->appName();
			}

			if(in_array('app_colored_logo', $this->default)) {
				$result['app_colored_logo'] = $this->appColoredLogo();
			}

			if(in_array('app_white_logo', $this->default)) {
				$result['app_white_logo'] = $this->appWhiteLogo();
			}

			if(in_array('app_url', $this->default)) {
				$result['app_url'] = $this->appUrl();
			}

			if(in_array('year', $this->default)) {
				$result['year'] = $this->year();
			}

			if(in_array('code', $this->default)) {
				$result['code'] = $this->code();
			}
		}

		return $result;
	}

	/**
	 * Handle getting shortcode app name value
	 * 
	 * @return string
	 */
	protected function appName() 
	{
		return config('app.name');
	}

	/**
	 * Handle getting shortcode app colored logo value
	 * 
	 * @return html
	 */
	protected function appColoredLogo() 
	{
		return $this->htmlLogo(setting('colored_logo'));
	}

	/**
	 * Handle getting shortcode app white logo value
	 * 
	 * @return html
	 */
	protected function appWhiteLogo() 
	{
		return $this->htmlLogo(setting('white_logo'));
	}

	/**
	 * Handle getting shortcode app url value
	 * 
	 * @return string
	 */
	protected function appUrl() 
	{
		return config('app.url');
	}

	/**
	 * Handle getting shortcode year value
	 * 
	 * @return string
	 */
	protected function year() 
	{
		return date('Y');
	}

	/**
	 * Handle getting email template code
	 * 
	 * @return string
	 */
	protected function code() 
	{
		return $this->template->code;
	}

	/**
	 * Handle generating html logo
	 * 
	 * @param string $logo
	 * 
	 * @return html
	 */
	protected function htmlLogo($logo) 
	{
		return '<img src="' . $logo . '" alt="'.setting('app_name').'" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;max-width:100%;border:none">';
	}
}