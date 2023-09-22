<?php

namespace Modules\Base\Support\Language;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Modules\Base\Support\CurrentModule;
use Illuminate\Pagination\LengthAwarePaginator;

class Translations
{
	/**
	 * @var string $en
	 */
	protected $en = 'en';

	/**
	 * @var array $defaultLanguageGroups
	 */
	protected $defaultLanguageGroups = [
		'auth',
		'pagination',
		'passwords',
		'validation'
	];

	/**
	 * These are the default language group such as 
	 * lang/{code}/auth, 
	 * lang/{code}/pagination,
	 * lang/{code}/passwords,
	 * lang/{code}/validation
	 */
	const DEFAULT_LANG_GROUP_TYPE = 'group';

	/**
	 * This is for the language with lang/{code}.json
	 */
	const DEFAULT_LANG_JSON_TYPE = 'json';

	public function getContentByPath($path) 
	{
		$file = new Filesystem;

		return $file->get($path);
	}

	/**
	 * Get all translations
	 * 
	 * @return json
	 */
	public static function all() 
	{
		$module = (new CurrentModule)->get();
		$trans = new Translations;
		$file = new Filesystem;
            
        $result = json_decode(
            $file->get($trans->setLanguagePath(app()->getLocale())), 
            true
        );

        $moduleLang = $trans->setModuleLanguagePath($module, app()->getLocale());

        if(file_exists($moduleLang)) {
        	$moduleTranslations = $file->get($moduleLang);

        	$result = array_merge(
                $result, 
                json_decode($moduleTranslations,true)
            );
        }

        return json_encode($result);
	}

	/**
	 * Get translations by locale
	 * 
	 * @param string $code
	 * 
	 * @return array
	 */
	public function get($code = 'en') 
	{
		$file = new Filesystem;

		$content = $file->get($this->setLanguagePath($code));

		return json_decode($content,true);
	}

	/**
	 * Add/Update language key value
	 * 
	 * @param string $code
	 * @param string $key
	 * 		- For default you must add title.key or auth.failed
	 * @param string $value
	 * @param boolean $default 
	 * 		- Useful for updating the default language lang/locale/title.php
	 * 
	 * @return void
	 */
	public function put($code, $key, $value, $default = false) 
	{
		$file = new Filesystem;

		if($default) {

			if(strpos($key, '.') !== false) {
				$array = explode('.', $key);
				$title = reset($array); //get the first value of array variable
				$key = end($array); //get the key
				$trans = trans($title, [], $code); //get the language translations

				// check if trans has a value
				if(count($trans)) {

					// check if has a another dimension key
					if(strpos($key, '|') !== false) {
						$array = explode('|', $key);

						// limit with 2
						if(count($array) == 2) {
							$trans[$array[0]][$array[1]] = $value;
						}
					} else {
						$trans[$key] = $value;
					}

					$path = $this->setDefaultLanguagePath($code, $title);
			        $output = "<?php\n\nreturn " . var_export($trans, true) . ";\n";

			        $file->put($path, $output);
				}
			}
		} else {
			$content = $file->get($this->setLanguagePath($code));

	        $items = json_decode($content,true);

	        $items[$key] = $value;

	        $file->put(
	            $this->setLanguagePath($code), 
	            json_encode($items, JSON_PRETTY_PRINT)
	        );
		}
	}

	/**
	 * Able to update the language using code by default/english
	 * 
	 * @param string $code
	 * 
	 * @return void
	 */
	public function updateByDefault($code) 
	{
		$file = new Filesystem;

		$default = $this->get();
        $language = $this->get($code);
        
        // get the new added language phrases
        $updates = array_diff_key($default, $language);

        // message the new phrases to the current language
        $items = array_merge($language, $updates);

        $file->put(
            $this->setLanguagePath($code), 
            json_encode($items, JSON_PRETTY_PRINT)
        );
	}

	/**
	 * Create default langauge by code
	 *  Like * auth.php, pagination.php, passwords.php and validation.php which is copied from english
	 * 
	 * @param string $code
	 * @param string|null $type - Supported types 'group|json'
	 * 
	 * @return void
	 */
	public function createDefaultByCode($code, $type = null) 
	{
		$file = new Filesystem;
		$pathToCreate = $this->setLanguagePath($code);
        $pathDefault = $this->setLanguagePath($this->en);

        $jsonContent = $file->get($pathDefault);

        if(is_null($type) || $type == self::DEFAULT_LANG_GROUP_TYPE) {
        	foreach($this->defaultLanguageGroups as $group) {
	        	$pathDefaultLanguageEnglish = $this->setDefaultLanguagePath($this->en, $group);

	        	$dirDefaultLangaugeToCreate = $this->setDefaultLanguageDirectory($code);
	        	$pathDefaultLanguageToCreate = $this->setDefaultLanguagePath($code, $group);

	        	$englishContent = $file->get($pathDefaultLanguageEnglish);
	       	
	       		if(!$file->isDirectory($dirDefaultLangaugeToCreate)) {
	       			$file->makeDirectory($dirDefaultLangaugeToCreate);
	       		}

	       		$englishContent = str_replace('=> \'', '=> utf8_encode(\'', $englishContent);
	       		$englishContent = str_replace('\',', '\'),', $englishContent);

	       		$englishContent = str_replace('=> "', '=> utf8_encode("', $englishContent);
	       		$englishContent = str_replace('",', '"),', $englishContent);

	        	$file->put($pathDefaultLanguageToCreate, $englishContent);
	        }
        }
        
        if(is_null($type) || $type == self::DEFAULT_LANG_JSON_TYPE) {
        	$file->put($pathToCreate, $jsonContent);
        }
	}

	/**
	 * Get by module name
	 * 
	 * @param string $name
	 * @param string $code
	 * 
	 * @return array|null
	 */
	public function getByModule($name, $code = null) 
	{
		$file = new Filesystem;

		if(is_null($code)) {
			$code = app()->getLocale();
		}

		$moduleLang = $this->setModuleLanguagePath($name, $code);
	
		if(file_exists($moduleLang)) {
			return $file->get($moduleLang);
		}
	}

	public function putByModule($name, $code, $key, $value) 
	{
		$file = new Filesystem;

		$content = $this->getByModule(
			ucfirst($name),
			$code
		);

        $items = json_decode($content,true);

        $items[$key] = $value;

        $file->put(
            $this->setModuleLanguagePath($name, $code), 
            json_encode($items, JSON_PRETTY_PRINT)
        );
	}

	/**
	 * Check module has a langauge
	 * 
	 * @param string $name
	 * @param string code
	 * 
	 * @return boolean
	 */
	public function moduleHasLanguage($name, $code) 
	{
		return file_exists($this->setModuleLanguagePath($name, $code));
	}

	/**
	 * Copy module english language by code
	 * 
	 * @param string $name
	 * @param string $code
	 */
	public function copyModuleEnglishLanguageByCode($name, $code) 
	{
		$file = new Filesystem;

		if($this->moduleHasLanguage($name, $this->en)) {
			$file->copy($this->setModuleLanguagePath($name, $this->en), $this->setModuleLanguagePath($name, $code));
		}
	}

	/**
	 * Load datatable data
	 * 
	 * NOTE: This datatable will only support 2 dimensional array
	 * 
	 * @param json $content
	 * @param boolean $default 
	 * 		- Useful for determining if the language is default like en/auth.php
	 * @return array
	 */
	public function datatable($content, $default = false) 
	{
		$items = json_decode($content,true);

		// handle query
		if(request('queryValue')) {
			$items = preg_grep('~' . request('queryValue') . '~', $items);
		}

		// handle sort
		if(request('sortValue') == 'id_asc') {
			ksort($items, SORT_REGULAR);
		} else {
			krsort($items, SORT_REGULAR);
		}

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
 
        // Create a new Laravel collection from the array data
        $itemCollection = collect($this->handleParsingData($items, $default));
 
        // Define how many products we want to be visible in each page
        $perPage = request('limit') ?? 25;
 
        // Slice the collection to get the products to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath(request()->url());

        if(request('page') > 1) {
            return $this->handleSecondPageToLastIssue($paginatedItems->toArray());
        }

        return $paginatedItems->toArray();
	}

	/**
     * Handle parsing data that support the datatable
     * 
     * @param array $items
     * @param boolean $default
     * 
     * @return array
     */
    protected function handleParsingData($items, $default = false) 
    {
        $result = [];
        $cntr = 0;
        foreach($items as $key => $value) {
            $result[$cntr]['key'] = $key;
            $result[$cntr]['value'] = $value;
            $result[$cntr]['default'] = $default == true ? 1 : 0;

            $cntr++;
        }

        return $result;
    }

    /**
     * Handle fixing issue for the second page to last
     * 
     * @param array $items
     * 
     * @return array
     */
    protected function handleSecondPageToLastIssue($items)
    {
        $result = [];
        $cntr = 0;
        foreach($items['data'] as $data) {
            $result[$cntr] = $data;
            
            $cntr++;
        }

        $items['data'] = $result;

        return $items;
    }

	/**
	 * Set module language path
	 * 
	 * @param string $name
	 * @param string $code
	 * 
	 * @return string
	 */
	protected function setModuleLanguagePath($name, $code) 
	{
		return base_path() . '/Modules/'.$name.'/Resources/lang/' . $code . '.json';
	}

	/**
	 * Set the app language path
	 * 
	 * @param string $code
	 * 
	 * @return string
	 */
	public function setLanguagePath($code) 
	{
		return base_path() . '/lang/' . $code . '.json';
	}

	/**
	 * Set the default language path
	 * 
	 * @param string $code
	 * @param string $title
	 * 		- This is the filename of the language
	 * 
	 * @return string
	 */
	public function setDefaultLanguagePath($code, $title) 
	{
		return base_path() . '/lang/' . $code . '/'  . $title . '.php';
	}

	/**
	 * Set the default language directory
	 * 
	 * @param string $code
	 * 
	 * @return string
	 */
	public function setDefaultLanguageDirectory($code) 
	{
		return base_path() . '/lang/' . $code;
	}
}