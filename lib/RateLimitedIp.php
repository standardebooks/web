<?

use Safe\DateTimeImmutable;

class RateLimitedIp{
	public string $IpAddress;
	public DateTimeImmutable $Created;

	/**
	 * @return array<RateLimitedIp>
	 */
	public static function GetAll(): array{
		return Db::Query('
				SELECT *
				from RateLimitedIps
			', [], RateLimitedIp::class);
	}

	/**
	 * @throws Exceptions\InvalidRateLimitedIpException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidRateLimitedIpException();

		if(!isset($this->IpAddress)){
			$error->Add(new Exceptions\RateLimitedIpAddressRequiredException());
		}

		if(Formatter::ToIpv6($this->IpAddress)){
			$this->IpAddress = Formatter::ToIpv6($this->IpAddress);
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidRateLimitedIpException
	 */
	public function Create(): void{
		$this->Validate();

		$this->Created = NOW;

		Db::Query('
			INSERT into RateLimitedIps (IpAddress, Created)
			values (?,
				?)
			on duplicate key update
				Created = value(Created)
		', [$this->IpAddress, $this->Created]);
	}
}
