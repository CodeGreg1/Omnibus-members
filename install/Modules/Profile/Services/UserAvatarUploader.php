<?php

namespace Modules\Profile\Services;

use Illuminate\Support\Facades\File;

class UserAvatarUploader {

	/**
     * @var string Destination directoy storing user photos
     */
	protected $destinationDir = '/upload/users';

	/**
	 * @var string Default extension for user avatar
	 */
	protected $defaultExtension = "png";

	/**
	 * Upload user avatar
	 * 
	 * @param string Image (data:image/png;base64)
	 * 
	 * @return string
	 */
	public function upload($image) 
	{
		list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);

        $path = $this->getPath();

        File::put(public_path($path), $this->decodeBase64($image));

        return $path;
	}

	/**
	 * Delete profile avatar
	 * 
	 * @param string $path
	 * 
	 * @return mixed
	 */
	public function delete($path) 
	{	
		if (File::exists(public_path($path))) File::delete(public_path($path));
	}

	/**
	 * Decode Image base64
	 * 
	 * @param string Image (data:image/png;base64)
	 * 
	 * @return string
	 */
	protected function decodeBase64($image) 
	{
		return base64_decode($image);
	}	

	/**
	 * Get path
	 * 
	 * @return string
	 */
	protected function getPath() 
	{
		return $this->destinationDir . '/'. $this->generateFileName();
	}

	/**
	 * Generate profile avatar filename
	 * 
	 * @return string
	 */
	protected function generateFileName() 
	{
		return time() . '.' . $this->defaultExtension;
	}

}