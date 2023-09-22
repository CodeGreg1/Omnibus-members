<?php

namespace Modules\EmailTemplates\Support;

class ShortcodeExtractor 
{
	/**
	 * @var $opening
	 */
	private $start = "{";

	/**
	 * @var $closing
	 */
	private $end = "}";

	public function extract($content, $result=[]) 
    {

        preg_match_all('/{(.*?)}/s', $content, $match);

        return $match[0];
    }
}
