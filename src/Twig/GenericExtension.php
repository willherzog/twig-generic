<?php

namespace WHTwig\Twig;

use Twig\Extension\AbstractExtension;
use Twig\{TwigFilter,TwigFunction};

use WHPHP\Util\StringUtil;

/**
 * @author Will Herzog <willherzog@gmail.com>
 */
class GenericExtension extends AbstractExtension
{
	public function __construct(
		protected readonly string $appDir,
		protected readonly int $indentSpaces = 0
	) {}

	/**
	 * @inheritDoc
	 */
	public function getFilters(): array
	{
		return [
			new TwigFilter('underscore_to_dash', function(string $str, bool $preserveDoubleUnderscores = true): string {
				return StringUtil::convertUnderscoresToDashes($str, $preserveDoubleUnderscores);
			}),

			new TwigFilter('indent_lines', function(string $str, int $level = 1, bool $applyIndentAtStart = true): string {
				$prototype = $this->indentSpaces > 0 ? str_repeat(' ', $this->indentSpaces) : "\t";
				$indent = str_repeat($prototype, max($level, 0));

				if( $applyIndentAtStart ) {
					$str = $indent . $str;
				}

				return rtrim(str_replace(["\r\n","\n"], ["\n","\n$indent"], $str), "\t");
			}, ['is_safe' => ['html']]),

			new TwigFilter('trim_trailing_newlines', function(string $str, string $newlineChars = "\n\r"): string {
				return rtrim($str, $newlineChars);
			}, ['is_safe' => ['html']]),

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
			}),

			new TwigFilter('limit_words', function(string $str, int $maxWords): string {
				if( $maxWords > 0 && $str !== '' ) {
					$words = explode(' ', preg_replace('/ +/', ' ', $str));
					$nonWords = ['&','/','+','-','=','<','>','–'];
					$sentenceEndChars = ['.','!','?'];
					$cardinalMaximum = $maxWords;

					foreach( $words as $key => $word ) {
						if( $key === $maxWords ) {
							break;
						} elseif( in_array($word, $nonWords, true) ) {
							$cardinalMaximum++;
						}
					}

					$str = implode(' ', array_slice($words, 0, $cardinalMaximum));
					$lastChar = mb_strcut($str, -1, 1);

					if( $cardinalMaximum < count($words) && !in_array($lastChar, $sentenceEndChars, true) ) {
						if( $pos = mb_strrpos($str, ' (') ) {
							$str = mb_strcut($str, 0, $pos);
							$lastChar = mb_strcut($str, -1, 1);
						}

						if( in_array($lastChar, ['.',',',':',';','!','?','-'], true) ) {
							$str = mb_strcut($str, 0, mb_strlen($str) - 1);
						}

						$str .= '…';
					}
				}

				return $str;
			}),

			new TwigFilter('enum_value', function(\BackedEnum|null $enum): int|string {
				return $enum?->value ?? '';
			})
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('php_info', function(): string {
				ob_start();

				phpinfo();

				$phpInfo = ob_get_contents();

				ob_end_clean();

				return $phpInfo;
			}),

			new TwigFunction('timezone', function(): string {
				return date_default_timezone_get();
			}),

			new TwigFunction('file_exists', function(string $filePath): bool
			{
				$fullPath = $this->appDir;

				if( !str_ends_with($fullPath, '/') ) {
					$fullPath .= '/';
				}

				$fullPath .= ltrim($filePath, '/\\');

				return is_file($fullPath) && is_readable($fullPath);
			})
		];
	}
}
