<?php

namespace WHTwig\Twig;

use Twig\Extension\AbstractExtension;
use Twig\{TwigFilter,TwigFunction};

use WHTwig\Twig\Util\StringUtil;

/**
 * @author Will Herzog <willherzog@gmail.com>
 */
class GenericExtension extends AbstractExtension
{
	public function __construct(protected readonly string $appDir)
	{}

	/**
	 * @inheritDoc
	 */
	public function getFilters(): array
	{
		return [
			new TwigFilter('underscore_to_dash', function(string $str, bool $preserveDoubleUnderscores = true): string {
				return StringUtil::convertUnderscoresToDashes($str, $preserveDoubleUnderscores);
			}),

			new TwigFilter('percentage', function(float $decimal, bool $round = true): string {
				$percent = $round ? round($decimal * 100) : $decimal * 100;

				return "$percent%";
			}),

			new TwigFilter('title_conditional', function(string $str): string {
				$lcStr = mb_strtolower($str);

				if( $lcStr === $str ) { // input string is all lower case
					return mb_convert_case($str, \MB_CASE_TITLE);
				}

				return $str;
			}),

			new TwigFilter('initials', function(string $name, string $separator = '', string $suffix = '', bool $all = false): string {
				$name = trim($name);

				if( str_contains($name, ' ') ) {
					$nameWords = explode(' ', $name);
					$initials = [];

					if( $all ) {
						foreach( $nameWords as $word ) {
							$initials[] = mb_substr($word, 0, 1) . $suffix;
						}
					} else {
						$firstWord = array_shift($nameWords);
						$lastWord = array_pop($nameWords);

						$initials[] = mb_substr($firstWord, 0, 1) . $suffix;
						$initials[] = mb_substr($lastWord, 0, 1) . $suffix;
					}

					return implode($separator, $initials);
				}

				return mb_substr($name, 0, 1) . $suffix;
			})
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('timezone', function(): string {
				return date_default_timezone_get();
			}),

			new TwigFunction('file_exists', function(string $filePath): bool
			{
				$fullPath = $this->appDir;

				if( !str_starts_with($filePath, '/') ) {
					$fullPath .= '/';
				}

				$fullPath .= $filePath;

				return is_file($fullPath) && is_readable($fullPath);
			})
		];
	}
}
