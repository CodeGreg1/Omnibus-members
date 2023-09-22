<?php

namespace Modules\Base\Support\Common;

use File;

class Helper
{	
	/**
     * Load common helper functions
     * 
     * @param string $directory
     * @author Ronard Cauba
     * @since 1.0
     * @return File
     */
    public static function loader($directory)
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    /**
     * Get exact timezone
     *
     * @return string
     */
    public static function timezone()
    {
        return auth()->check() ? auth()->user()->timezone : config('app.timezone');
    }
}