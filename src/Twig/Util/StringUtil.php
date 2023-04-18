<?php

namespace WHTwig\Twig\Util;

/**
 * @author Will Herzog <willherzog@gmail.com>
 */
class StringUtil
{
	private function __construct()
	{}

	static public function convertUnderscoresToDashes(string $str, bool $preserveDoubleUnderscores = false)
	{
		if( str_contains($str, '_') ) {
			if( $preserveDoubleUnderscores ) {
				$str = str_replace(['__','_','++'], ['++','-','__'], $str);
			} else {
				$str = str_replace('_', '-', $str);
			}
		}

		return $str;
	}
}
