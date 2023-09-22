<?php

namespace Modules\Profile\Services\TwoFactor;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

trait DownloadTwoFactorRecoveryCode
{	
	/**
	 * @var $fileName
	 */
	protected $fileName;

	public function __construct() 
	{
		$this->fileName = time();
	}

	/**
	 * Download recovery codes
	 * 
	 * @param array $codes
	 * 
	 * @return Response
	 */
	public function downloadTwoFactorRecoveryCode($codes) 
	{
        $contents = '';

        foreach ($codes as $code) {
            $contents .= $code . PHP_EOL;
        }

        File::put($this->fileName . '.txt', $contents, true);

        return Response::download($this->filePath(), 'recovery_codes.txt', [
            'Content-Type: text/plain'
        ])->deleteFileAfterSend(true);
	}

	/**
	 * Get file path
	 * 
	 * @return string
	 */
	protected function filePath() 
	{
		return public_path() . '/' . $this->fileName . '.txt';
	}
}