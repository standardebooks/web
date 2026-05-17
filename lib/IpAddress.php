<?
use function Safe\hex2bin;
use function Safe\inet_ntop;
use function Safe\inet_pton;

class IpAddress{
	/** Is this `IpAddress` and IPv6 address? */
	public bool $IsIpv6;
	/** The binary representation of this `IpAddress`. */
	public string $Binary;

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
}
