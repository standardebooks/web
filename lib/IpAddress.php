<?
use function Safe\hex2bin;
use function Safe\inet_ntop;
use function Safe\inet_pton;

class IpAddress{
	/** Is this `IpAddress` and IPv6 address? */
	public bool $IsIpv6;
	/** The binary representation of this `IpAddress`. */
	public string $Binary;
	public string $GeolocationUrl;

	private string $_Address;

	/**
	 * @throws Exceptions\IpAddressInvalidException If the passed IP address is not valid.
	 */
	public function __construct(string $ipAddress, bool $isIpAddressBinary = false){
		if($isIpAddressBinary){
			try{
				$this->_Address = inet_ntop($ipAddress);
			}
			catch(\Exception){
				throw new Exceptions\IpAddressInvalidException();
			}

			$this->Binary = $ipAddress;
		}
		else{
			// General plan: User can connect either over IPv4 or IPv6. IPv4 addresses are also often represented in IPv6 notation.
			// We prefer IPv4 notation when possible, so first convert that to IPv4 if we got an IPv6 address.
			// Then store the binary representation in a `varbinary` field in the database.
			// To retrieve from the database, use `inet6_nota()`, which works with both types of address.

			$this->_Address = $ipAddress;

			// Known prefix.
			$ipv4MappedPrefixBinary = hex2bin('00000000000000000000ffff');

			// Parse the address.
			try{
				// Throws a warning on top of returning **`FALSE`** on error, so silence that.
				@$ipBinary = inet_pton($this->_Address);
			}
			catch(\Exception){
				throw new Exceptions\IpAddressInvalidException();
			}

			// Check prefix.
			if(substr($ipBinary, 0, strlen($ipv4MappedPrefixBinary)) == $ipv4MappedPrefixBinary){
				// Strip prefix.
				$ipBinary = substr($ipBinary, strlen($ipv4MappedPrefixBinary));
			}

			$this->Binary = $ipBinary;
		}

		$this->IsIpv6 = filter_var($this->_Address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;

		$this->GeolocationUrl = 'https://api.ipregistry.co/' . rawurlencode($this->_Address) . '?key=' . IPREGISTRY_API_KEY;
	}

	public function __toString(): string{
		return $this->_Address;
	}

	/**
	 * @throws Exceptions\IpAddressInvalidException If the IP address is invalid.
	 */
	public static function FromRow(stdClass $row): IpAddress{
		return new IpAddress($row->IpAddress, true);
	}

	/**
	 * @throws Exceptions\GeolocationFailedException If the request to geolocate this IP address failed.
	 */
	public function Geolocate(): IpAddressGeolocation{
		try{
			$response = HttpRequest::Execute(Enums\HttpMethod::Get, $this->GeolocationUrl . '&fields=connection.organization,connection.type,security');
			/** @var stdClass $data */
			$data = $response->GetJson();

			if(!$response->HttpCode->IsSuccess()){
				throw new Exceptions\GeolocationFailedException('IP address geolocation failed. Address: ' . $this->_Address . '. Response: HTTP ' . $response->HttpCode->value . ', ' . print_r($data, true));
			}

			$ipAddressGeolocation = new IpAddressGeolocation();
			$ipAddressGeolocation->RawResponse = $data;
			$ipAddressGeolocation->ContinentCode = $data->location->continent->code ?? null;
			$ipAddressGeolocation->CountryCode = $data->location->country->code ?? null;
			$ipAddressGeolocation->IsAbuser = $data->security->is_abuser ?? false;
			$ipAddressGeolocation->IsAttacker = $data->security->is_attacker ?? false;
			$ipAddressGeolocation->IsBogon = $data->security->is_bogon ?? false;
			$ipAddressGeolocation->IsCloudProvider = $data->security->is_cloud_provider ?? false;
			$ipAddressGeolocation->IsProxy = $data->security->is_proxy ?? false;
			$ipAddressGeolocation->IsRelay = $data->security->is_relay ?? false;
			$ipAddressGeolocation->IsTor = $data->security->is_tor ?? false;
			$ipAddressGeolocation->IsTorExit = $data->security->is_tor_exit ?? false;
			$ipAddressGeolocation->IsVpn = $data->security->is_vpn ?? false;
			$ipAddressGeolocation->Isp = $data->connection->organization ?? null;

			switch($data->connection->type ?? null){
				case 'isp':
					$ipAddressGeolocation->IspType = Enums\IspType::Isp;
					break;

				case 'hosting':
					$ipAddressGeolocation->IspType = Enums\IspType::DataProvider;
					break;
			}

			return $ipAddressGeolocation;
		}
		catch(Exceptions\HttpRequestException $ex){
			throw new Exceptions\GeolocationFailedException('IP address geolocation failed. Address: ' . $this->_Address . '. HTTP request exception: ' . $ex->getMessage());
		}
	}

	/**
	 * Ban this IP address.
	 */
	public function Ban(string $reason): void{
		Db::Query('insert into BannedIpAddresses (IpAddress, Reason) values (?, ?) on duplicate key update Reason = values(Reason), CreatedAt = utc_timestamp()', [$this->Binary, $reason]);
	}

	/**
	 * Is this IP banned?
	 *
	 * @return ?string The reason why this IP address is banned, or `NULL` if it's not banned.
	 */
	public function IsBanned(): ?string{
		$bannedIpAddress = Db::Query('select Reason from BannedIpAddresses where IpAddress = ?', [$this->Binary]);

		if(sizeof($bannedIpAddress) > 0){
			return $bannedIpAddress[0]->Reason;
		}

		if(SITE_STATUS == SITE_STATUS_DEV){
			return null;
		}

		try{
			$geolocation = $this->Geolocate();

			// Treat any IP address with a security flag as spam.
			foreach([
				'is_abuser' => $geolocation->IsAbuser,
				'is_attacker' => $geolocation->IsAttacker,
				'is_bogon' => $geolocation->IsBogon,
				'is_cloud_provider' => $geolocation->IsCloudProvider,
				'is_proxy' => $geolocation->IsProxy,
				'is_relay' => $geolocation->IsRelay,
				'is_tor' => $geolocation->IsTor,
				'is_tor_exit' => $geolocation->IsTorExit,
				'is_vpn' => $geolocation->IsVpn,
			] as $securityFlag => $isFlagged){
				if($isFlagged){
					$reason = 'IP security flag: ' . $securityFlag . ' (' . $this->_Address . ').';
					$this->Ban($reason);
					return $reason;
				}
			}

			// We also ban people not using an ISP to sign up. These are probably bots.
			if(isset($geolocation->IspType) && $geolocation->IspType == Enums\IspType::DataProvider){
				$reason = 'Banned ISP: ' . $geolocation->Isp . '.';
				$this->Ban($reason);
				return $reason;
			}
		}
		catch(Exceptions\GeolocationFailedException){
			// Pass.
		}

		return null;
	}
}
