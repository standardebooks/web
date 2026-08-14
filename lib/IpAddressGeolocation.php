<?

class IpAddressGeolocation{
	public stdClass $RawResponse;

	public ?string $CountryCode = null;
	public ?string $ContinentCode = null;

	public bool $IsAbuser = false;
	public bool $IsAttacker = false;
	public bool $IsBogon = false;
	public bool $IsCloudProvider = false;
	public bool $IsProxy = false;
	public bool $IsRelay = false;
	public bool $IsTor = false;
	public bool $IsTorExit = false;
	public bool $IsVpn = false;

	public ?string $Isp = null;
	public ?Enums\IspType $IspType = null;
}
