<?

use function Safe\preg_match;
use function Safe\preg_split;
use function Safe\exec;
use function Safe\fwrite;

/**
 * Helper functions printing color and formatting in command line scripts.
 * This functionaliry is duplicated in `scripts/lib/common.sh` for use in Bash scripts.
 */
class Cli{
	protected static string $_EscSeq = "\x1b[";
	protected static string $_ResetAll = "\x1b[0m";
	protected static string $_ResetBold = "\x1b[21m";
	protected static string $_ResetUl = "\x1b[24m";
	protected static string $_FsUrl = "\x1b]8;;";
	protected static string $_FsUrlSt = "\x07";
	protected static string $_FsUrlClose = "\x1b]8;;\x07";
	protected static string $_FgBlack = "\x1b[30m";
	protected static string $_FgRed = "\x1b[31m";
	protected static string $_FgGreen = "\x1b[32m";
	protected static string $_FgYellow = "\x1b[33m";
	protected static string $_FgBlue = "\x1b[34m";
	protected static string $_FgMagenta = "\x1b[35m";
	protected static string $_FgCyan = "\x1b[36m";
	protected static string $_FgWhite = "\x1b[37m";
	protected static string $_FgPurple = "\x1b[38;5;129m";
	protected static string $_FgHotPink = "\x1b[38;5;206m";
	protected static string $_FgDarkOrange = "\x1b[38;5;208m";
	protected static string $_FgBrBlack = "\x1b[90m";
	protected static string $_FgBrRed = "\x1b[91m";
	protected static string $_FgBrGreen = "\x1b[92m";
	protected static string $_FgBrYellow = "\x1b[93m";
	protected static string $_FgBrightBlue = "\x1b[94m";
	protected static string $_FgBrBlue = "\x1b[94m";
	protected static string $_FgBrMagenta = "\x1b[95m";
	protected static string $_FgBrCyan = "\x1b[96m";
	protected static string $_FgBrWhite = "\x1b[97m";
	protected static string $_BgBlack = "\x1b[40m";
	protected static string $_BgRed = "\x1b[41m";
	protected static string $_BgGreen = "\x1b[42m";
	protected static string $_BgYellow = "\x1b[43m";
	protected static string $_BgBlue = "\x1b[44m";
	protected static string $_BgMagenta = "\x1b[45m";
	protected static string $_BgCyan = "\x1b[46m";
	protected static string $_BgWhite = "\x1b[47m";
	protected static string $_FsReg = "\x1b[22m";
	protected static string $_FsBold = "\x1b[1m";
	protected static string $_FsUl = "\x1b[4m";

	/**
	 * Print formatted text, matching the shell helper's `PrintHelp()` behavior.
	 *
	 * @param ?array<array{name: string, description: string}> $arguments
	 * @param ?array<array{name: string, description: string}> $options
	 */
	public static function PrintHelp(string $usage, string $description, ?array $arguments = null, ?array $options = null, ?string $examples = null): void{
		self::FormatHelp("[header]USAGE[/]\n\n");
		self::FormatHelp(self::Indent(self::AddHelpOptionToUsage($usage)), true);
		self::FormatHelp("\n[header]DESCRIPTION[/]\n\n");
		self::FormatHelp(self::Indent($description));

		if($arguments !== null && sizeof($arguments) > 0){
			self::FormatHelp("\n[header]POSITIONAL ARGUMENTS[/]\n\n");
			self::FormatHelp(self::Indent(self::FormatHelpEntries($arguments)));
		}

		$options = self::AddHelpOptionToOptions($options);
		self::FormatHelp("\n[header]OPTIONS[/]\n\n");
		self::FormatHelp(self::Indent(self::FormatHelpEntries($options)));

		if($examples !== null && $examples !== ''){
			self::FormatHelp("\n[header]EXAMPLES[/]\n\n");
			self::FormatHelp(self::Indent($examples));
		}

		exit();
	}

	/**
	 * Add the standard help option to the script usage string.
	 */
	private static function AddHelpOptionToUsage(string $usage): string{
		$usageParts = explode('[/]', $usage, 2);

		if(sizeof($usageParts) === 2){
			return $usageParts[0] . '[/] [[flag]-h[/],[flag]--help[/]]' . $usageParts[1];
		}

		return $usage . ' [[flag]-h[/],[flag]--help[/]]';
	}

	/**
	 * Add the standard help option as the first option in the options list.
	 *
	 * @param ?array<array{name: string, description: string}> $options
	 * @return array<array{name: string, description: string}>
	 */
	private static function AddHelpOptionToOptions(?array $options): array{
		return [
			[
				'name' => '[flag]-h[/],[flag]--help[/]',
				'description' => 'Show this help message and exit.',
			],
			...($options ?? []),
		];
	}

	/**
	 * Format a list of help entries into a string suitable for output.
	 *
	 * @param array<array{name: string, description: string}> $entries
	 */
	protected static function FormatHelpEntries(array $entries): string{
		$output = '';

		foreach($entries as $entry){
			if($output != ''){
				$output .= "\n\n\n";
			}

			$output .= $entry['name'] . "\n\n\t" . str_replace("\n", "\n\t", $entry['description']);
		}

		return $output;
	}

	/**
	 * Print a formatted error message to standard error and exit with the given code.
	 */
	public static function ExitWithError(string $message, int $code = 1): void{
		if(!defined('STDERR')){
			exit($code);
		}

		if(!self::IsColor()){
			fwrite(STDERR, 'Error: ' . self::RemoveFormatting($message, self::IsVeryPlain()) . "\n");
		}
		else{
			fwrite(STDERR, self::ColorizeString(self::$_BgRed . self::$_FgBrWhite . self::$_FsBold . ' Error ' . self::$_ResetAll . ' ' . $message));
		}

		exit($code);
	}

	/**
	 * Return whether color output should be enabled.
	 */
	protected static function IsColor(): bool{
		$noColor = getenv('NO_COLOR');
		return ($noColor === false || $noColor === '') && self::IsStdoutTty();
	}

	/**
	 * Return whether output without colors should omit backticks.
	 */
	protected static function IsVeryPlain(): bool{
		return !self::IsStdoutTty();
	}

	/**
	 * Return whether standard output is attached to a terminal.
	 */
	protected static function IsStdoutTty(): bool{
		if(!defined('STDOUT')){
			return false;
		}

		/** @var resource $stdout */
		$stdout = STDOUT;
		return posix_isatty($stdout);
	}

	/**
	 * Replace URL tags with terminal hyperlinks while preserving other formatting tags.
	 */
	protected static function FormatUrlLinks(string $line): string{
		$inLink = false;
		$output = '';

		while($line !== ''){
			if(preg_match('/^\[url=([^\]]+)\](.*)$/us', $line, $matches) === 1 && isset($matches[1], $matches[2])){
				$output .= self::$_FsUrl . $matches[1] . self::$_FsUrlSt;
				$line = $matches[2];
				$inLink = true;
			}
			elseif(str_starts_with($line, '[/]') && $inLink){
				$output .= self::$_FsUrlClose . '[/]';
				$line = substr($line, 3);
				$inLink = false;
			}
			else{
				$output .= mb_substr($line, 0, 1);
				$line = mb_substr($line, 1);
			}
		}

		if($inLink){
			$output .= self::$_FsUrlClose;
		}

		return $output;
	}

	/**
	 * Replace formatting tags with plain text markers.
	 */
	protected static function RemoveFormatting(string $line, bool $veryPlain = false, string $linkMode = 'none'): string{
		$inFormat = false;
		$inHeader = false;
		$inLink = false;
		$output = '';

		while($line !== ''){
			if(str_starts_with($line, '[/]')){
				if($inLink){
					if($linkMode === 'links'){
						$output .= self::$_FsUrlClose;

						if(!$veryPlain){
							$output .= '`';
						}
					}

					$inLink = false;
				}
				elseif($inFormat && !$inHeader && !$veryPlain){
					$output .= '`';
				}

				$inFormat = false;
				$inHeader = false;
				$line = substr($line, 3);
			}
			elseif(str_starts_with($line, '[url=')){
				if($linkMode === 'links'){
					if(preg_match('/^\[url=([^\]]+)\](.*)$/us', $line, $matches) === 1 && isset($matches[1], $matches[2])){
						if(!$veryPlain){
							$output .= '`';
						}

						$output .= self::$_FsUrl . $matches[1] . self::$_FsUrlSt;
						$line = $matches[2];
						$inLink = true;
					}
					else{
						$output .= mb_substr($line, 0, 1);
						$line = mb_substr($line, 1);
					}
				}
				elseif($linkMode === 'plain'){
					if(preg_match('/^\[url=[^\]]+\](.*)$/us', $line, $matches) === 1 && isset($matches[1])){
						$line = $matches[1];
						$inLink = true;
					}
					else{
						$output .= mb_substr($line, 0, 1);
						$line = mb_substr($line, 1);
					}
				}
				else{
					if(!$inFormat && !$veryPlain){
						$output .= '`';
					}

					$inFormat = true;
					$inHeader = false;

					if(preg_match('/^\[url=[^\]]+\](.*)$/us', $line, $matches) === 1 && isset($matches[1])){
						$line = $matches[1];
					}
					else{
						$line = mb_substr($line, 1);
					}
				}
			}
			elseif(str_starts_with($line, '[header]') || str_starts_with($line, '[parameter]') || str_starts_with($line, '[email]') || str_starts_with($line, '[command]') || str_starts_with($line, '[path]') || str_starts_with($line, '[user]') || str_starts_with($line, '[url]') || str_starts_with($line, '[flag]') || str_starts_with($line, '[xhtml]') || str_starts_with($line, '[xml]') || str_starts_with($line, '[val]') || str_starts_with($line, '[attr]') || str_starts_with($line, '[class]') || str_starts_with($line, '[text]') || str_starts_with($line, '[css]')){
				if($inLink){
					if(str_starts_with($line, '[header]')){
						$line = substr($line, 8);
					}
					elseif(str_starts_with($line, '[parameter]')){
						$line = substr($line, 11);
					}
					elseif(str_starts_with($line, '[command]')){
						$line = substr($line, 9);
					}
					elseif(str_starts_with($line, '[path]')){
						$line = substr($line, 6);
					}
					elseif(str_starts_with($line, '[user]')){
						$line = substr($line, 6);
					}
					elseif(str_starts_with($line, '[url]')){
						$line = substr($line, 5);
					}
					elseif(str_starts_with($line, '[email]')){
						$line = substr($line, 7);
					}
					elseif(str_starts_with($line, '[flag]')){
						$line = substr($line, 6);
					}
					elseif(str_starts_with($line, '[xhtml]')){
						$line = substr($line, 7);
					}
					elseif(str_starts_with($line, '[xml]')){
						$line = substr($line, 5);
					}
					elseif(str_starts_with($line, '[val]')){
						$line = substr($line, 5);
					}
					elseif(str_starts_with($line, '[attr]')){
						$line = substr($line, 6);
					}
					elseif(str_starts_with($line, '[class]')){
						$line = substr($line, 7);
					}
					elseif(str_starts_with($line, '[text]')){
						$line = substr($line, 6);
					}
					elseif(str_starts_with($line, '[css]')){
						$line = substr($line, 5);
					}
				}
				else{
					if(!$inFormat && !str_starts_with($line, '[header]') && !$veryPlain){
						$output .= '`';
					}

					$inFormat = true;
					$inHeader = false;
				}

				if(!$inLink && str_starts_with($line, '[header]')){
					$inHeader = true;
					$line = substr($line, 8);
				}
				elseif(!$inLink && str_starts_with($line, '[parameter]')){
					$line = substr($line, 11);
				}
				elseif(!$inLink && str_starts_with($line, '[command]')){
					$line = substr($line, 9);
				}
				elseif(!$inLink && str_starts_with($line, '[path]')){
					$line = substr($line, 6);
				}
				elseif(!$inLink && str_starts_with($line, '[user]')){
					$line = substr($line, 6);
				}
				elseif(!$inLink && str_starts_with($line, '[url]')){
					$line = substr($line, 5);
				}
				elseif(!$inLink && str_starts_with($line, '[email]')){
					$line = substr($line, 7);
				}
				elseif(!$inLink && str_starts_with($line, '[flag]')){
					$line = substr($line, 6);
				}
				elseif(!$inLink && str_starts_with($line, '[xhtml]')){
					$line = substr($line, 7);
				}
				elseif(!$inLink && str_starts_with($line, '[xml]')){
					$line = substr($line, 5);
				}
				elseif(!$inLink && str_starts_with($line, '[val]')){
					$line = substr($line, 5);
				}
				elseif(!$inLink && str_starts_with($line, '[attr]')){
					$line = substr($line, 6);
				}
				elseif(!$inLink && str_starts_with($line, '[class]')){
					$line = substr($line, 7);
				}
				elseif(!$inLink && str_starts_with($line, '[text]')){
					$line = substr($line, 6);
				}
				elseif(!$inLink && str_starts_with($line, '[css]')){
					$line = substr($line, 5);
				}
			}
			else{
				$output .= mb_substr($line, 0, 1);
				$line = mb_substr($line, 1);
			}
		}

		if($inLink && $linkMode === 'links'){
			$output .= self::$_FsUrlClose;

			if(!$veryPlain){
				$output .= '`';
			}
		}

		return $output;
	}

	/**
	 * Return the printable length of text that may contain formatting tags.
	 */
	protected static function GetStringLengthWithoutFormatting(string $text): int{
		if(self::IsColor() || self::IsVeryPlain()){
			$text = self::RemoveFormatting($text, true);
		}
		else{
			$text = self::RemoveFormatting($text, false, 'plain');
		}

		$width = 0;

		for($index = 0; $index < mb_strlen($text); $index++){
			$char = mb_substr($text, $index, 1);

			if($char === "\t"){
				$width += 8 - ($width % 8);
			}
			else{
				$width++;
			}
		}

		return $width;
	}

	/**
	 * Return the current terminal width if it can be detected.
	 */
	protected static function GetTerminalWidth(): ?int{
		$output = [];
		$exitCode = 0;
		exec('stty size 2> /dev/null < /dev/tty', $output, $exitCode);
		$terminalSize = $exitCode === 0 && isset($output[0]) ? $output[0] : '';
		$terminalSizeParts = preg_split('/\s+/', trim($terminalSize));
		$width = self::GetPositiveInteger($terminalSizeParts[sizeof($terminalSizeParts) - 1]);

		if($width === null){
			$width = self::GetPositiveInteger(getenv('COLUMNS'));
		}

		if($width === null && self::IsStdoutTty()){
			$output = [];
			$exitCode = 0;
			exec('tput cols 2> /dev/null', $output, $exitCode);
			$width = self::GetPositiveInteger($exitCode === 0 && isset($output[0]) ? $output[0] : '');
		}

		return $width;
	}

	/**
	 * Return a positive integer from a string value if one is present.
	 */
	protected static function GetPositiveInteger(string|false $value): ?int{
		if($value === false || !ctype_digit($value)){
			return null;
		}

		$integer = (int)$value;

		if($integer <= 0){
			return null;
		}

		return $integer;
	}

	/**
	 * Replace formatting tags with terminal colors, or with plain markers when color is disabled.
	 */
	protected static function ColorizeString(string $line, bool $printNewline = true, bool $veryPlain = false): string{
		if(!self::IsColor()){
			if(self::IsVeryPlain()){
				$veryPlain = true;
				$output = self::RemoveFormatting($line, $veryPlain);
				return $printNewline ? $output . "\n" : $output;
			}

			$output = self::RemoveFormatting($line, $veryPlain, 'links');
			return $printNewline ? $output . "\n" : $output;
		}

		$line = self::FormatUrlLinks($line);

		$output = str_replace(
			['[header]', '[/]', '[parameter]', '[command]', '[xhtml]', '[xml]', '[val]', '[attr]', '[class]', '[path]', '[user]', '[url]', '[text]', '[css]', '[email]', '[flag]'],
			[self::$_FgGreen . self::$_FsBold, self::$_ResetAll, self::$_FgCyan, self::$_FgGreen, self::$_FgPurple, self::$_FgPurple, self::$_FgBrightBlue, self::$_FgHotPink, self::$_FgHotPink, self::$_FgBrightBlue . self::$_FsUl, self::$_FgMagenta, self::$_FgBrightBlue, self::$_FgDarkOrange, self::$_FgBrightBlue, self::$_FgMagenta, self::$_FgBrightBlue],
			$line
		);

		return $printNewline ? $output . "\n" : $output;
	}

	/**
	 * Wrap one line to the current terminal width, ignoring formatting tags when measuring line length.
	 */
	protected static function WrapLine(string $line, int $width, bool $veryPlain = false): void{
		if(self::GetStringLengthWithoutFormatting($line) <= $width){
			printf('%s', self::ColorizeString($line, true, $veryPlain));
			return;
		}

		if(preg_match('/^(\s*)(.*)$/', $line, $matches) === 1 && isset($matches[1], $matches[2])){
			$indent = $matches[1];
			$line = $matches[2];
		}
		else{
			$indent = '';
		}

		$availableWidth = $width - self::GetStringLengthWithoutFormatting($indent);

		if($availableWidth < 20){
			$availableWidth = 20;
		}

		$currentLine = '';
		$remainingLine = $line;

		while($remainingLine !== ''){
			if(preg_match('/^(\s+)(.*)$/', $remainingLine, $matches) === 1 && isset($matches[1], $matches[2])){
				$chunk = $matches[1];
				$remainingLine = $matches[2];
			}
			elseif(preg_match('/^([^\s]+)(.*)$/', $remainingLine, $matches) === 1 && isset($matches[1], $matches[2])){
				$chunk = $matches[1];
				$remainingLine = $matches[2];
			}
			else{
				$chunk = $remainingLine;
				$remainingLine = '';
			}

			if($currentLine === ''){
				$currentLine = $chunk;
			}
			elseif(self::GetStringLengthWithoutFormatting($currentLine . $chunk) <= $availableWidth){
				$currentLine .= $chunk;
			}
			elseif(preg_match('/^\s+$/', $chunk) === 1){
				printf('%s', self::ColorizeString($indent . $currentLine, true, $veryPlain));
				$currentLine = '';
			}
			else{
				printf('%s', self::ColorizeString($indent . $currentLine, true, $veryPlain));
				$currentLine = $chunk;
			}
		}

		if($currentLine !== ''){
			printf('%s', self::ColorizeString($indent . $currentLine, false, $veryPlain));
		}

		printf("\n");
	}

	/**
	 * Format a block of text and print it.
	 */
	protected static function FormatHelp(string $text, bool $veryPlain = false): void{
		$width = self::GetTerminalWidth();
		$lines = explode("\n", $text);

		if(str_ends_with($text, "\n")){
			array_pop($lines);
		}

		foreach($lines as $line){
			if($line === ''){
				printf("\n");
			}
			elseif($width === null){
				printf('%s', self::ColorizeString($line, true, $veryPlain));
			}
			else{
				self::WrapLine($line, $width, $veryPlain);
			}
		}
	}

	/**
	 * Indent each line in a string with a tab, then format it as usage text.
	 */
	protected static function Indent(string $text): string{
		$lines = explode("\n", $text);
		$output = '';

		foreach($lines as $line){
			$output .= "\t" . $line . "\n";
		}

		return $output;
	}
}
