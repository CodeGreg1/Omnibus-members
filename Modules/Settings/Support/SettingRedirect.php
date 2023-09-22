<?php

namespace Modules\Settings\Support;

use Setting;
use Illuminate\Support\Str;

class SettingRedirect
{	
	/**
	 * @var array SETTING_KEYS
	 */
	const SETTING_KEYS = [
		'services_facebook_redirect',
		'services_google_redirect'
	];

	/**
	 * Update automatically the redirect url defined above
	 * 
	 * @return void
	 */
	public static function update() 
	{
		$that = new SettingRedirect;

		$isSet = 0;

		foreach(self::SETTING_KEYS as $key) {
			$settingHost = $that->getHost(setting($key));
			$appHost = $that->getHost(env('APP_URL'));

			if(!is_null($settingHost) && !is_null($appHost) && $settingHost != $appHost) {
				$value = env('APP_URL') . url_remove_domain(setting($key));
				Setting::set($key, $value);
				$isSet = 1;
			}
		}

		// if setting is set then save
		if($isSet) {
			Setting::save();
		}
	}

	protected function getHost($url) 
	{
		$url = parse_url($url);

		return isset($url['host']) ? $url['host'] : null;
	}
}