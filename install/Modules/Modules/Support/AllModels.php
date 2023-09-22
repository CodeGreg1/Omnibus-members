<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AllModels {

	private $hiddenModelsOnDemo = [
		'AffiliateCommission',
		'AffiliateCommissionType',
		'AffiliateReferral',
		'AffiliateReferralCommission',
		'AffiliateReferralLevel',
		'AffiliateUser',
		'AvailableCurrency',
		'Address',
		'Country',
		'Currency',
		'Blog',
		'CartItem',
		'Checkout',
		'CheckoutItem',
		'Coupon',
		'PromoCode',
		'ShippingRate',
		'TaxRate',
		'Category',
		'CategoryType',
		'DashboardWidget',
		'Deposit',
		'EmailTemplate',
		'Frontend',
		'Language',
		'Menu',
		'MenuLink',
		'MenuRole',
		'Module',
		'ModuleRelation',
		'Order',
		'OrderItem',
		'Page',
		'PageContent',
		'PageScriptag',
		'PageSection',
		'PageStyle',
		'Payment',
		'Photo',
		'Product',
		'Company',
		'ProfilePasswordChange',
		'Session',
		'SessionLocation',
		'SocialLogin',
		'Service',
		'Setting',
		'Feature',
		'Package',
		'PackageExtraCondition',
		'PackageIntegration',
		'PackageModuleLimit',
		'PackagePrice',
		'PackagePriceIntegration',
		'PackageTerm',
		'PricingTable',
		'PricingTableItem',
		'Subscription',
		'SubscriptionCoupon',
		'SubscriptionItem',
		'UserModuleLimitCounter',
		'Tag',
		'Ticket',
		'TicketConversation',
		'Transaction',
		'Activity',
		'ManualGateway',
		'Wallet',
		'Withdrawal'
	];

	/**
	 * Get all module models
	 * 
	 * @return array
	 */
	public function get() 
	{
		$paths = [
			app_path() . "/Models",
			base_path() . "/Modules/*/Models"
		];

		$result = [];

		foreach($paths as $path) {
			$result[] = $this->extract($path);
		}

		return $result;
	}

	/**
	 * Extract module models using path
	 * 
	 * @param string $path
	 * 
	 * @return array
	 */
	protected function extract($path) 
	{
		$out = [];
	    $folders = glob($path, GLOB_BRACE);

	    if(!env('APP_DEMO')) {
			$this->hiddenModelsOnDemo = [];
		}

	    foreach ($folders as $folder) {

	        if (is_dir($folder)) {

	        	$files = scandir($folder);

	        	foreach($files as $file) 
	        	{
	        		if ($file === '.' or $file === '..' or $file === '.gitkeep') continue;

					$modelName = $this->modelName($file);

					if (!in_array($modelName, $this->hiddenModelsOnDemo)) {
						$namespace = $this->namespace(
							$modelName,
		        			$this->content($folder, $file)
		        		);

						$model = $this->modelNamespace(
							$namespace, $modelName
						);

						$tableName = (new TableName($modelName, $model))->get();
						
						if (Schema::hasTable($tableName)) {
							$out[$tableName] = [
								'namespace' => $model,
								'model_name' => $modelName
							];
						}
					}
	        	}
	        }
	    }

	    return $out;
	}

	/**
	 * Get model content
	 *
	 * @param string $folder
	 * @param string $file
	 *
	 * @return string
	 */
	protected function content($folder, $file) 
	{
		return file_get_contents($folder.'/'.$file);
	}

	/**
	 * Get model namespace
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	protected function namespace($model, $content) 
	{
		preg_match_all('/namespace(.*?);/s', $content, $match);

		if(isset($match[1])) {
			return reset($match[1]);
		} else {
			throw new \Exception(__(":model: Cannot read the model namespace.", ['model' => $model]), 1);
		}
		
	}

	/**
	 * Get model name
	 *
	 * @param string $filename
	 * 
	 * @return string
	 */
	protected function modelName($filename) 
	{
		$model = explode('.', $filename);

		return reset($model);
	}

	/**
	 * Get model namespace
	 *
	 * @param string $namespace
	 * @param string $model
	 *
	 * @return string
	 */
	protected function modelNamespace($namespace, $model) 
	{
		return '\\'. trim($namespace) . '\\' . $model;
	}

	/**
	 * Get default table name by model name
	 *
	 * @param string $model
	 *
	 * @return string
	 */
	protected function defaultTableName($model) 
	{
		return  Str::plural(Str::snake($model));
	}

}