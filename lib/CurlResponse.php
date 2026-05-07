<?
class CurlResponse{
	public Enums\HttpCode $HttpCode;
	/** @var array<string, string> $Headers */
	public array $Headers;
	/** The final URL of the request, considering any redirects that occurred during processing. */
	public string $FinalUrl;
}
