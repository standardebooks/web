#!/bin/bash

exec_request() {
	echo "$1" >> tests-output

	if [[ "$1" == *"HEAD"* ]]; then
		curl --insecure -sS -D - --head ${1/HEAD/""} -o /dev/null >> tests-output
	else
		curl --insecure -sS -D - -X $1 -o /dev/null >> tests-output
	fi
}

echo -n "" > tests-output

# Static file that exists
exec_request "GET https://standardebooks.test/robots.txt" # 200
exec_request "HEAD https://standardebooks.test/robots.txt" # 200
exec_request "DELETE https://standardebooks.test/robots.txt" # 405 + allow: GET,HEAD,OPTIONS
exec_request "OPTIONS https://standardebooks.test/robots.txt" # 204 + allow: GET,HEAD,OPTIONS

# Static file that doesn't exists
exec_request "GET https://standardebooks.test/doesnt-exist.txt" # 404
exec_request "HEAD https://standardebooks.test/doesnt-exist.txt" # 404
exec_request "DELETE https://standardebooks.test/doesnt-exist.txt" # 404
exec_request "OPTIONS https://standardebooks.test/doesnt-exist.txt" # 404

# Resource that exists
exec_request "GET https://standardebooks.test/artworks/george-w-joy/the-kelpie" # 200
exec_request "HEAD https://standardebooks.test/artworks/george-w-joy/the-kelpie" # 200
exec_request "PATCH https://standardebooks.test/artworks/george-w-joy/the-kelpie" # 200
exec_request "DELETE https://standardebooks.test/artworks/george-w-joy/the-kelpie" # 405 + allow: GET,HEAD,OPTIONS,PATCH
exec_request "OPTIONS https://standardebooks.test/artworks/george-w-joy/the-kelpie" # 204 + allow: GET,HEAD,OPTIONS,PATCH

# Resource that doesn't exist
exec_request "GET https://standardebooks.test/artworks/not/not" # 404
exec_request "HEAD https://standardebooks.test/artworks/not/not" # 404
exec_request "PATCH https://standardebooks.test/artworks/not/not" # 404
exec_request "DELETE https://standardebooks.test/artworks/not/not" # 404
exec_request "OPTIONS https://standardebooks.test/artworks/not/not" # 404

# Resource creation endpoint
exec_request "GET https://standardebooks.test/artworks" # 200
exec_request "HEAD https://standardebooks.test/artworks" # 200
exec_request "POST https://standardebooks.test/artworks" # 302 to login
exec_request "PATCH https://standardebooks.test/artworks" # 405 + allow: GET,HEAD,OPTIONS,POST
exec_request "DELETE https://standardebooks.test/artworks" # 405 + allow: GET,HEAD,OPTIONS,POST
exec_request "OPTIONS https://standardebooks.test/artworks" # 204 + allow: GET,HEAD,OPTIONS,POST
exec_request "GET https://standardebooks.test/sessions" # 405 + allow: OPTIONS,POST

# Resource creation form
exec_request "GET https://standardebooks.test/artworks/new" # 302 to login
exec_request "HEAD https://standardebooks.test/artworks/new" # 302 to login
exec_request "POST https://standardebooks.test/artworks/new" # 405 + allow: GET,HEAD,OPTIONS
exec_request "OPTIONS https://standardebooks.test/artworks/new" # 204 + allow: GET,HEAD,OPTIONS

# Dynamic non-resource page that exists
exec_request "GET https://standardebooks.test/contribute/collections-policy" # 200
exec_request "HEAD https://standardebooks.test/contribute/collections-policy" # 200
exec_request "PATCH https://standardebooks.test/contribute/collections-policy" # 405 + allow: GET,HEAD,OPTIONS
exec_request "OPTIONS https://standardebooks.test/contribute/collections-policy" # 204 + allow: GET,HEAD,OPTIONS

# Remove headers that can change
sed --in-place --regexp-extended "/^(date|etag|set-cookie|expires|cache-control|pragma|last-modified):/d" tests-output

if ! diff --ignore-all-space tests-golden tests-output > /dev/null; then
	echo "Test outputs don't match."
	exit 1
else
	rm tests-output
fi
