#!/bin/bash

# Escape sequence and resets.
ESC_SEQ=$'\x1b['
RESET_ALL="${ESC_SEQ}0m"
RESET_BOLD="${ESC_SEQ}21m"
RESET_UL="${ESC_SEQ}24m"

# Foreground colors.
FG_BLACK="${ESC_SEQ}30m"
FG_RED="${ESC_SEQ}31m"
FG_GREEN="${ESC_SEQ}32m"
FG_YELLOW="${ESC_SEQ}33m"
FG_BLUE="${ESC_SEQ}34m"
FG_MAGENTA="${ESC_SEQ}35m"
FG_CYAN="${ESC_SEQ}36m"
FG_WHITE="${ESC_SEQ}37m"
FG_BR_BLACK="${ESC_SEQ}90m"
FG_BR_RED="${ESC_SEQ}91m"
FG_BR_GREEN="${ESC_SEQ}92m"
FG_BR_YELLOW="${ESC_SEQ}93m"
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

# Return whether color output should be disabled.
no_color(){
	[[ -n "${NO_COLOR}" || ! -t 1 ]]
}

# Return whether plain usage output should omit backticks.
very_plain_usage(){
	[[ ! -t 1 ]]
}

# Replace usage formatting tags with plain text markers.
# Param 1: The line to colorize.
# Param 2: Boolean to use "very plain" output.
plain_usage(){
	local inFormat
	local inHeader
	local line
	local output
	local veryPlain

	line="$1"
	veryPlain="${2:-false}"
	output=""
	inFormat="false"
	inHeader="false"

	while [[ -n "${line}" ]]; do
		case "${line}" in
			'[/]'*)
				if [[ "${inFormat}" == "true" && "${inHeader}" == "false" && "${veryPlain}" != "true" ]]; then
					output="${output}\`"
				fi

				inFormat="false"
				inHeader="false"
				line="${line:3}"
				;;
			'[header]'*|'[parameter]'*|'[email]'*|'[command]'*|'[path]'*|'[user]'*|'[url]'*)
				if [[ "${inFormat}" == "false" && "${line}" != '[header]'* && "${veryPlain}" != "true" ]]; then
					output="${output}\`"
				fi

				inFormat="true"
				inHeader="false"

				case "${line}" in
					'[header]'*)
						inHeader="true"
						line="${line:8}"
						;;
					'[parameter]'*)
						line="${line:11}"
						;;
					'[command]'*)
						line="${line:9}"
						;;
					'[path]'*)
						line="${line:6}"
						;;
					'[user]'*)
						line="${line:6}"
						;;
					'[url]'*)
						line="${line:5}"
						;;
					'[email]'*)
						line="${line:7}"
						;;
				esac
				;;
			*)
				output="${output}${line:0:1}"
				line="${line:1}"
				;;
		esac
	done

	printf '%s' "${output}"
}

# Return the printable length of text that may contain usage formatting tags.
get_string_length_without_formatting(){
	local char
	local index
	local text
	local width

	text="$1"

	if no_color; then
		text="$(plain_usage "${text}" "$(very_plain_usage && printf 'true' || printf 'false')")"
	else
		text="${text//\[header\]/}"
		text="${text//\[parameter\]/}"
		text="${text//\[command\]/}"
		text="${text//\[path\]/}"
		text="${text//\[url\]/}"
		text="${text//\[user\]/}"
		text="${text//\[email\]/}"
		text="${text//\[\/\]/}"
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

	printf '%s' "${width}"
}

# Return the current terminal width.
get_terminal_width(){
	local terminalSize
	local width

	terminalSize="$(stty size 2> /dev/null < /dev/tty || true)"
	width="${terminalSize##* }"

	if [[ -z "${width}" ]] || ((width <= 0)); then
		width="${COLUMNS}"
	fi

	if ([[ -z "${width}" ]] || ((width <= 0))) && [[ -t 1 ]]; then
		width="$(tput cols 2> /dev/null || true)"
	fi

	if [[ -n "${width}" ]] && ((width > 0)); then
		printf "${width}"
	fi
}

# Replace usage formatting tags with terminal colors.
# Param 1: The line to colorize.
# Param 2 (optional): boolean to use "very plain" output, i.e., if `true` and color output is disabled, don't replace colors with backticks. Useful when outputting example CLI commands that are meant to be copied and pasted.
colorize_usage(){
	local line
	local veryPlain

	line="$1"
	veryPlain="${2:-false}"

	if no_color; then
		if very_plain_usage; then
			veryPlain="true"
		fi

		plain_usage "${line}" "${veryPlain}"
		return
	fi

	line="${line//\[header\]/${FG_GREEN}${FS_BOLD}}"
	line="${line//\[parameter\]/${FG_CYAN}}"
	line="${line//\[command\]/${FG_GREEN}}"
	line="${line//\[path\]/${FG_BLUE}${FS_UL}}"
	line="${line//\[user\]/${FG_MAGENTA}}"
	line="${line//\[url\]/${FG_BLUE}}"
	line="${line//\[email\]/${FG_MAGENTA}}"
	line="${line//\[\/\]/${RESET_ALL}}"

	printf '%s' "${line}"
}

# Wrap one usage line to the current terminal width, ignoring formatting tags when measuring line length.
wrap_usage_line(){
	local availableWidth
	local chunk
	local currentLine
	local indent
	local line
	local remainingLine
	local width

	line="$1"
	width="$2"

	if (($(get_string_length_without_formatting "${line}") <= width)); then
		colorize_usage "${line}"
		printf "\n"
		return
	fi

	if [[ "${line}" =~ ^([[:space:]]*)(.*)$ ]]; then
		indent="${BASH_REMATCH[1]}"
		line="${BASH_REMATCH[2]}"
	else
		indent=""
	fi

	availableWidth=$((width - $(get_string_length_without_formatting "${indent}")))

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

		if [[ -z "${currentLine}" ]]; then
			currentLine="${chunk}"
		elif (($(get_string_length_without_formatting "${currentLine}${chunk}") <= availableWidth)); then
			currentLine="${currentLine}${chunk}"
		elif [[ "${chunk}" =~ ^[[:space:]]+$ ]]; then
			colorize_usage "${indent}${currentLine}"
			printf "\n"
			currentLine=""
		else
			colorize_usage "${indent}${currentLine}"
			printf "\n"
			currentLine="${chunk}"
		fi
	done

	if [[ -n "${currentLine}" ]]; then
		colorize_usage "${indent}${currentLine}"
	fi

	printf "\n"
}

# Replace usage formatting tags with terminal colors.
format_usage(){
	local line
	local width

	width="$(get_terminal_width)"

	while IFS= read -r line; do
		if [[ -z "${line}" ]]; then
			printf "\n"
		elif [[ -z "${width}" ]]; then
			colorize_usage "${line}"
			printf "\n"
		else
			wrap_usage_line "${line}" "${width}"
		fi
	done
}

# Indent each line in the string with a tab, then format using `format_usage()`.
# Param 1: The string.
format_usage_parameter(){
	local line

	while IFS= read -r line || [[ -n "${line}" ]]; do
		format_usage <<< "	${line}"
	done <<< "$1"
}

# Param 1: Example CLI usage.
# Param 2: Description.
# Param 3 (optional): List of options.
# Param 4 (optional): List of examples.
print_help(){
	echo -n
	format_usage <<EOF
[header]USAGE[/]

EOF

	format_usage_parameter "$1"

	format_usage <<EOF

[header]DESCRIPTION[/]

EOF

	format_usage_parameter "$2"

	format_usage <<EOF
EOF

	if [[ -n "$3" ]]; then
		format_usage <<EOF

[header]OPTIONS[/]

EOF

		format_usage_parameter "$3"
	fi

	if [[ -n "$4" ]]; then
		format_usage <<EOF

[header]EXAMPLES[/]

EOF

		format_usage_parameter "$4"
	fi

	exit 1
}

# Param 1: Error message.
# Param 2 (optional): Error code, defaults to `1`.
die(){
	if no_color; then
		printf "Error: $(plain_usage "${1}" "$(very_plain_usage && printf 'true' || printf 'false')")\n" 1>&2
	else
		printf "$(colorize_usage "${BG_RED}${FG_BR_WHITE}${FS_BOLD} Error ${RESET_ALL} ${1}")\n" 1>&2
	fi

	code=1
	if [ -n "$2" ]; then
		code=$2;
	fi

	exit ${code}
}

# Param 1: Command to require.
# Param 2 (optional): Suggestion for how to install the required command.
require(){
	command -v "$1" > /dev/null 2>&1 || {
		suggestion="";
		if [ -n "$2" ]; then
			suggestion=" $2";
		fi
		die "[command]$1[/] isn't installed.${suggestion}";
	}
}
