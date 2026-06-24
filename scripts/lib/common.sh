#!/bin/bash
# shellcheck disable=SC2034

# Helper functions printing color and formatting in command line scripts.
# This functionaliry is duplicated in `lib/Cli.php` for use in PHP scripts.

# Escape sequence and resets.
ESC_SEQ=$'\x1b['
RESET_ALL="${ESC_SEQ}0m"
RESET_BOLD="${ESC_SEQ}21m"
RESET_UL="${ESC_SEQ}24m"
FS_URL=$'\x1b]8;;'
FS_URL_ST=$'\a'
FS_URL_CLOSE="${FS_URL}${FS_URL_ST}"

# Foreground colors.
FG_BLACK="${ESC_SEQ}30m"
FG_RED="${ESC_SEQ}31m"
FG_GREEN="${ESC_SEQ}32m"
FG_YELLOW="${ESC_SEQ}33m"
FG_BLUE="${ESC_SEQ}34m"
FG_MAGENTA="${ESC_SEQ}35m"
FG_CYAN="${ESC_SEQ}36m"
FG_WHITE="${ESC_SEQ}37m"
FG_PURPLE="${ESC_SEQ}38;5;129m"
FG_HOT_PINK="${ESC_SEQ}38;5;206m"
FG_ORANGE1="${ESC_SEQ}38;5;214m"
FG_DARK_SEA_GREEN1="${ESC_SEQ}38;5;193m"
FG_DARK_GOLDENROD="${ESC_SEQ}38;5;136m"
FG_CORNFLOWER_BLUE="${ESC_SEQ}38;5;69m"
FG_DARK_ORANGE="${ESC_SEQ}38;5;208m"
FG_DARK_GRAY="${ESC_SEQ}90m"
FG_GRAY69="${ESC_SEQ}38;2;176;176;176m"
FG_BLUE_VIOLET="${ESC_SEQ}38;2;138;43;226m"
FG_PALE_GREEN3="${ESC_SEQ}38;2;124;205;124m"
FG_BR_BLACK="${ESC_SEQ}90m"
FG_BR_RED="${ESC_SEQ}91m"
FG_BR_GREEN="${ESC_SEQ}92m"
FG_BR_YELLOW="${ESC_SEQ}93m"
FG_BRIGHT_BLUE="${ESC_SEQ}94m"
FG_LIGHT_BLUE="${ESC_SEQ}94m"
FG_BR_BLUE="${ESC_SEQ}94m"
FG_BR_MAGENTA="${ESC_SEQ}95m"
FG_BR_CYAN="${ESC_SEQ}96m"
FG_BR_WHITE="${ESC_SEQ}97m"

# Background colours.
BG_BLACK="${ESC_SEQ}40m"
BG_RED="${ESC_SEQ}41m"
BG_GREEN="${ESC_SEQ}42m"
BG_YELLOW="${ESC_SEQ}43m"
BG_BLUE="${ESC_SEQ}44m"
BG_MAGENTA="${ESC_SEQ}45m"
BG_CYAN="${ESC_SEQ}46m"
BG_WHITE="${ESC_SEQ}47m"

# Font styles.
FS_REG="${ESC_SEQ}22m"
FS_BOLD="${ESC_SEQ}1m"
FS_UL="${ESC_SEQ}4m"

# Return whether color output should be enabled.
IsColor(){
	[[ -z "${NO_COLOR:-}" && -t 1 ]]
}

# Return whether output without colors should omit backticks.
IsVeryPlain(){
	[[ ! -t 1 ]]
}

# Replace link tags with terminal hyperlinks.
# Param 1: The line to format.
FormatLinks(){
	local inLink
	local line
	local output
	local url

	line="$1"
	output=""
	inLink=false

	if [[ "${line}" != *"[link="* ]]; then
		printf "%s" "${line}"
		return
	fi

	while [[ -n "${line}" ]]; do
		if [[ "${line}" =~ ^\[link=([^]]+)\](.*)$ ]]; then
			url="${BASH_REMATCH[1]}"
			output="${output}${FS_URL}${url}${FS_URL_ST}"
			line="${BASH_REMATCH[2]}"
			inLink=true
		elif [[ "${line}" == "[/]"* ]] && ${inLink}; then
			output="${output}${FS_URL_CLOSE}[/]"
			line="${line:3}"
			inLink=false
		else
			if [[ "${line}" == "["* ]]; then
				output="${output}${line:0:1}"
				line="${line:1}"
			elif [[ "${line}" == *"["* ]]; then
				output="${output}${line%%\[*}"
				line="[${line#*\[}"
			else
				output="${output}${line}"
				line=""
			fi
		fi
	done

	if ${inLink}; then
		output="${output}${FS_URL_CLOSE}"
	fi

	printf "%s" "${output}"
}

# Replace formatting tags with backticks.
# Param 1: The line to colorize.
# Param 2: Boolean to use "very plain" output, i.e., no backticks.
# Param 3: Link handling mode. `links` replaces link tags with terminal hyperlinks; `plain` removes the tags without adding backticks.
RemoveFormatting(){
	local inFormat
	local inHeader
	local inLink
	local linkMode
	local line
	local output
	local url
	local veryPlain

	line="$1"
	veryPlain=${2:-false}
	linkMode="${3:-none}"
	output=""
	inFormat=false
	inHeader=false
	inLink=false

	while [[ -n "${line}" ]]; do
		case "${line}" in
			"[/]"*)
				if ${inLink}; then
					if [[ "${linkMode}" == "links" ]]; then
						output="${output}${FS_URL_CLOSE}"
					fi

					inLink=false
					line="${line:3}"
				elif ${inFormat} && ! ${inHeader} && ! ${veryPlain}; then
					output="${output}\`"

					inFormat=false
					inHeader=false
					line="${line:3}"
				else
					inFormat=false
					inHeader=false
					line="${line:3}"
				fi
				;;
			"[link="*"]"*)
				if [[ "${linkMode}" == "links" ]]; then
					if [[ "${line}" =~ ^\[link=([^]]+)\](.*)$ ]]; then
						url="${BASH_REMATCH[1]}"

						output="${output}${FS_URL}${url}${FS_URL_ST}"
						line="${BASH_REMATCH[2]}"
						inLink=true
					else
						output="${output}${line:0:1}"
						line="${line:1}"
					fi
				elif [[ "${linkMode}" == "plain" ]]; then
					line="${line#*\]}"
					inLink=true
				else
					line="${line#*\]}"
					inLink=true
				fi
				;;
				"[header]"*|"[parameter]"*|"[email]"*|"[command]"*|"[subcommand]"*|"[branch]"*|"[path]"*|"[user]"*|"[url]"*|"[error]"*|"[warning]"*|"[queued]"*|"[running]"*|"[finished]"*|"[dim]"*|"[flag]"*|"[xhtml]"*|"[xml]"*|"[val]"*|"[attr]"*|"[class]"*|"[text]"*|"[css]"*)
				if ${inLink}; then
					case "${line}" in
						"[header]"*)
							line="${line:8}"
							;;
						"[parameter]"*)
							line="${line:11}"
							;;
						"[command]"*)
							line="${line:9}"
							;;
						"[subcommand]"*)
							line="${line:12}"
							;;
						"[branch]"*)
							line="${line:8}"
							;;
						"[path]"*)
							line="${line:6}"
							;;
						"[user]"*)
							line="${line:6}"
							;;
						"[url]"*)
							line="${line:5}"
							;;
						"[error]"*)
							line="${line:7}"
							;;
						"[warning]"*)
							line="${line:9}"
							;;
						"[queued]"*)
							line="${line:8}"
							;;
						"[running]"*)
							line="${line:9}"
							;;
						"[finished]"*)
							line="${line:10}"
							;;
						"[dim]"*)
							line="${line:5}"
							;;
						"[email]"*)
							line="${line:7}"
							;;
						"[flag]"*)
							line="${line:6}"
							;;
						"[xhtml]"*)
							line="${line:7}"
							;;
						"[xml]"*)
							line="${line:5}"
							;;
						"[val]"*)
							line="${line:5}"
							;;
						"[attr]"*)
							line="${line:6}"
							;;
						"[class]"*)
							line="${line:7}"
							;;
						"[text]"*)
							line="${line:6}"
							;;
						"[css]"*)
							line="${line:5}"
							;;
					esac
				elif ! ${inFormat} && [[ "${line}" != "[header]"* ]] && ! ${veryPlain}; then
					output="${output}\`"
					inFormat=true
					inHeader=false
				else
					inFormat=true
					inHeader=false
				fi

				if ! ${inLink}; then
					case "${line}" in
						"[header]"*)
							inHeader=true
							line="${line:8}"
							;;
						"[parameter]"*)
							line="${line:11}"
							;;
						"[command]"*)
							line="${line:9}"
							;;
						"[subcommand]"*)
							line="${line:12}"
							;;
						"[branch]"*)
							line="${line:8}"
							;;
						"[path]"*)
							line="${line:6}"
							;;
						"[user]"*)
							line="${line:6}"
							;;
						"[url]"*)
							line="${line:5}"
							;;
						"[error]"*)
							line="${line:7}"
							;;
						"[warning]"*)
							line="${line:9}"
							;;
						"[queued]"*)
							line="${line:8}"
							;;
						"[running]"*)
							line="${line:9}"
							;;
						"[finished]"*)
							line="${line:10}"
							;;
						"[dim]"*)
							line="${line:5}"
							;;
						"[email]"*)
							line="${line:7}"
							;;
						"[flag]"*)
							line="${line:6}"
							;;
						"[xhtml]"*)
							line="${line:7}"
							;;
						"[xml]"*)
							line="${line:5}"
							;;
						"[val]"*)
							line="${line:5}"
							;;
						"[attr]"*)
							line="${line:6}"
							;;
						"[class]"*)
							line="${line:7}"
							;;
						"[text]"*)
							line="${line:6}"
							;;
						"[css]"*)
							line="${line:5}"
							;;
					esac
				fi
			;;
		*)
			if [[ "${line}" == "["* ]]; then
				output="${output}${line:0:1}"
				line="${line:1}"
			elif [[ "${line}" == *"["* ]]; then
				output="${output}${line%%\[*}"
				line="[${line#*\[}"
			else
				output="${output}${line}"
				line=""
			fi
			;;
		esac
	done

	if ${inLink} && [[ "${linkMode}" == "links" ]]; then
		output="${output}${FS_URL_CLOSE}"
	fi

	printf "%s" "${output}"
}

# Calculate the printable length of text that may contain formatting tags and set the global variable `STRING_LENGTH_WITHOUT_FORMATTING` to that value.
# Param 1: The text to measure.
SetStringLengthWithoutFormatting(){
	local char
	local index
	local text
	local width

	text="$1"

	if [[ "${text}" != *"["* && "${text}" != *$'\t'* ]]; then
		STRING_LENGTH_WITHOUT_FORMATTING="${#text}"
		return
	fi

	if IsColor || IsVeryPlain; then
		text="$(RemoveFormatting "${text}" true)"
	else
		text="$(RemoveFormatting "${text}" false "plain")"
	fi

	if [[ "${text}" != *$'\t'* ]]; then
		STRING_LENGTH_WITHOUT_FORMATTING="${#text}"
		return
	fi

	width="0"

	for ((index = 0; index < ${#text}; index++)); do
		char="${text:index:1}"

		if [[ "${char}" == $'\t' ]]; then
			width=$((width + 8 - (width % 8)))
		else
			width=$((width + 1))
		fi
	done

	STRING_LENGTH_WITHOUT_FORMATTING="${width}"
}

# Return the current terminal width.
GetTerminalWidth(){
	local terminalSize
	local width

	terminalSize="$(stty size 2> /dev/null < /dev/tty || true)"
	width="${terminalSize##* }"

	if [[ -z "${width}" ]] || ((width <= 0)); then
		width="${COLUMNS:-}"
	fi

	if ([[ -z "${width}" ]] || ((width <= 0))) && [[ -t 1 ]]; then
		width="$(tput cols 2> /dev/null || true)"
	fi

	if [[ -n "${width}" ]] && ((width > 0)); then
		printf "%s" "${width}"
	fi
}

# Replace formatting tags with terminal colors.
# Param 1: The line to format.
ApplyFormattingTags(){
	local closeTag
	local line

	line="$1"

	if [[ "${line}" != *"["* ]]; then
		printf "%s" "${line}"
		return
	fi

	closeTag="[/]"
	line="${line//"[header]"/"${FG_GREEN}${FS_BOLD}"}"
	line="${line//"[parameter]"/"${FG_CYAN}"}"
	line="${line//"[command]"/"${FG_GREEN}"}"
	line="${line//"[subcommand]"/"${FG_DARK_SEA_GREEN1}"}"
	line="${line//"[branch]"/"${FG_DARK_GOLDENROD}"}"
	line="${line//"[xhtml]"/"${FG_PURPLE}"}"
	line="${line//"[xml]"/"${FG_PURPLE}"}"
	line="${line//"[val]"/"${FG_BRIGHT_BLUE}"}"
	line="${line//"[attr]"/"${FG_HOT_PINK}"}"
	line="${line//"[class]"/"${FG_HOT_PINK}"}"
	line="${line//"[path]"/"${FG_BLUE}${FS_BOLD}${FS_UL}"}"
	line="${line//"[user]"/"${FG_MAGENTA}"}"
	line="${line//"[url]"/"${FG_BRIGHT_BLUE}"}"
	line="${line//"[error]"/"${FG_RED}"}"
	line="${line//"[warning]"/"${FG_ORANGE1}"}"
	line="${line//"[queued]"/"${FG_GRAY69}"}"
	line="${line//"[running]"/"${FG_BLUE_VIOLET}"}"
	line="${line//"[finished]"/"${FG_PALE_GREEN3}"}"
	line="${line//"[dim]"/"${FG_GRAY69}"}"
	line="${line//"[text]"/"${FG_DARK_ORANGE}"}"
	line="${line//"[css]"/"${FG_BRIGHT_BLUE}"}"
	line="${line//"[flag]"/"${FG_BRIGHT_BLUE}"}"
	line="${line//"[email]"/"${FG_MAGENTA}"}"
	line="${line//"${closeTag}"/"${RESET_ALL}"}"

	printf "%s" "${line}"
}

# Replace formatting tags with terminal colors.
# Param 1: The line to colorize.
# Param 2 (optional): boolean to print a newline after the string.
# Param 3 (optional): boolean to use "very plain" output, i.e., if `true` and color output is disabled, don't replace colors with backticks. Useful when outputting example CLI commands that are meant to be copied and pasted.
ColorizeString(){
	local currentLine
	local isFirstLine
	local line
	local printNewline
	local veryPlain

	line="$1"
	printNewline=${2:-true}
	veryPlain=${3:-false}

	if [[ "${line}" == *$'\n'* ]]; then
		isFirstLine=true

		while IFS= read -r currentLine || [[ -n "${currentLine}" ]]; do
			if ${isFirstLine}; then
				isFirstLine=false
			else
				printf "\n"
			fi

			ColorizeString "${currentLine}" false "${veryPlain}"
		done <<< "${line}"

		if ${printNewline}; then
			printf "\n"
		fi

		return
	fi

	if ! IsColor; then
		if IsVeryPlain; then
			veryPlain=true
			RemoveFormatting "${line}" "${veryPlain}"
		else
			RemoveFormatting "${line}" "${veryPlain}" "links"
		fi

		if ${printNewline}; then
			printf "\n"
		fi

		return
	fi

	if [[ "${line}" == *"[link="* ]]; then
		line="$(FormatLinks "${line}")"
	fi

	ApplyFormattingTags "${line}"

	if ${printNewline}; then
		printf "\n"
	fi
}

# Wrap one line to the current terminal width, ignoring formatting tags when measuring line length.
WrapLine(){
	local availableWidth
	local chunk
	local chunkWidth
	local currentLine
	local currentLineWidth
	local indent
	local indentWidth
	local line
	local lineWidth
	local remainingLine
	local veryPlain
	local width

	line="$1"
	width="$2"
	veryPlain=${3:-false}

	if [[ "${line}" =~ ^[[:space:]]*$ ]]; then
		printf "\n"
		return
	fi

	SetStringLengthWithoutFormatting "${line}"
	lineWidth="${STRING_LENGTH_WITHOUT_FORMATTING}"

	if [[ ! "${lineWidth}" =~ ^[0-9]+$ ]]; then
		return 130 # ctrl + c interrupt signal
	fi

	if ((lineWidth <= width)); then
		ColorizeString "${line}" true "${veryPlain}"
		return
	fi

	if [[ "${line}" =~ ^([[:space:]]*)(.*)$ ]]; then
		indent="${BASH_REMATCH[1]}"
		line="${BASH_REMATCH[2]}"
	else
		indent=""
	fi

	SetStringLengthWithoutFormatting "${indent}"
	indentWidth="${STRING_LENGTH_WITHOUT_FORMATTING}"

	if [[ ! "${indentWidth}" =~ ^[0-9]+$ ]]; then
		return 130 # ctrl + c interrupt signal
	fi

	availableWidth=$((width - indentWidth))

	if ((availableWidth < 20)); then
		availableWidth=20
	fi

	currentLine=""
	remainingLine="${line}"

	while [[ -n "${remainingLine}" ]]; do
		if [[ "${remainingLine}" =~ ^([[:space:]]+)(.*)$ ]]; then
			chunk="${BASH_REMATCH[1]}"
			remainingLine="${BASH_REMATCH[2]}"
		elif [[ "${remainingLine}" =~ ^([^[:space:]]+)(.*)$ ]]; then
			chunk="${BASH_REMATCH[1]}"
			remainingLine="${BASH_REMATCH[2]}"
		else
			chunk="${remainingLine}"
			remainingLine=""
		fi

		SetStringLengthWithoutFormatting "${chunk}"
		chunkWidth="${STRING_LENGTH_WITHOUT_FORMATTING}"

		if [[ ! "${chunkWidth}" =~ ^[0-9]+$ ]]; then
			return 130 # ctrl + c interrupt signal
		fi

		if [[ -z "${currentLine}" ]]; then
			currentLine="${chunk}"
			currentLineWidth="${chunkWidth}"
		else
			if (((currentLineWidth + chunkWidth) <= availableWidth)); then
				currentLine="${currentLine}${chunk}"
				currentLineWidth=$((currentLineWidth + chunkWidth))
			elif [[ "${chunk}" =~ ^[[:space:]]+$ ]]; then
				ColorizeString "${indent}${currentLine}" true "${veryPlain}"
				currentLine=""
				currentLineWidth=0
			else
				ColorizeString "${indent}${currentLine}" true "${veryPlain}"
				currentLine="${chunk}"
				currentLineWidth="${chunkWidth}"
			fi
		fi
	done

	if [[ -n "${currentLine}" ]]; then
		ColorizeString "${indent}${currentLine}" true "${veryPlain}"
	fi
}

# Replace formatting tags with terminal colors.
# Param 1: The text to format.
# Param 2: Terminal width.
# Param 3 (optional): Boolean to use "very plain" output, i.e., no backticks.
FormatHelp(){
	local line
	local text
	local veryPlain
	local width

	text="$1"
	width="$2"
	veryPlain=${3:-false}

	while IFS= read -r line; do
		if [[ -z "${line}" ]]; then
			printf "\n"
		elif [[ -z "${width}" ]]; then
			ColorizeString "${line}" true "${veryPlain}"
		else
			WrapLine "${line}" "${width}" "${veryPlain}"
		fi
	done <<< "${text}"
}

# Indent each line in the string with a tab.
# Param 1: The string.
Indent(){
	local line

	while IFS= read -r line || [[ -n "${line}" ]]; do
		printf "	%s\n" "${line}"
	done <<< "$1"
}

# Param 1: Example CLI usage.
# Param 2: Description.
# Param 3 (optional): List of positional arguments.
# Param 4 (optional): List of options.
# Param 5 (optional): List of examples.
PrintHelp(){
	local helpOption
	local options
	local usage
	local width

	width="$(GetTerminalWidth)"
	usage="${1/\[\/\]/[/] [[flag]-h[/],[flag]--help[/]]}"
	helpOption="[flag]-h[/],[flag]--help[/]

	Show this help message and exit."
	options="${helpOption}"

	if [[ -n "${4:-}" ]]; then
		options+=$'\n\n\n'"$4"
	fi

	echo -n
	FormatHelp "[header]USAGE[/]
" "${width}"
	FormatHelp "$(Indent "${usage}")" "${width}" true

	FormatHelp "
[header]DESCRIPTION[/]
" "${width}"

	FormatHelp "$(Indent "$2")" "${width}" ""

	if [[ -n "${3:-}" ]]; then
		FormatHelp "
[header]POSITIONAL ARGUMENTS[/]
" "${width}"

		FormatHelp "$(Indent "$3")" "${width}" ""
	fi

	FormatHelp "
[header]OPTIONS[/]
" "${width}"

	FormatHelp "$(Indent "${options}")" "${width}" ""

	if [[ -n "${5:-}" ]]; then
		FormatHelp "
[header]EXAMPLES[/]
" "${width}"

		FormatHelp "$(Indent "$5")" "${width}" ""
	fi

	exit
}

# Param 1: Error message.
# Param 2 (optional): Error code, defaults to `1`.
ExitWithError(){
	if ! IsColor; then
		if IsVeryPlain; then
			printf "Error: %s\n" "$(RemoveFormatting "${1}" true)" 1>&2
		else
			printf "Error: %s\n" "$(RemoveFormatting "${1}" false)" 1>&2
		fi
	else
		ColorizeString "${BG_RED}${FG_BR_WHITE}${FS_BOLD} Error ${RESET_ALL} ${1}" true 1>&2
	fi

	local code

	code=1
	if [ -n "${2:-}" ]; then
		code="$2";
	fi

	exit "${code}"
}

# Param 1: Command to require.
# Param 2 (optional): Suggestion for how to install the required command.
Require(){
	command -v "$1" > /dev/null 2>&1 || {
		suggestion="";
		if [ -n "$2" ]; then
			suggestion=" $2";
		fi
		ExitWithError "[command]$1[/] isn't installed.${suggestion}";
	}
}
