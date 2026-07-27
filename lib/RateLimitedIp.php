<?

use Safe\DateTimeImmutable;

class RateLimitedIp{
	public string $IpAddress;
	public DateTimeImmutable $CreatedAt;

	/**
	 * @return array<RateLimitedIp>
	 */
	public static function GetAll(): array{
		return Db::Query('
				select *
				from RateLimitedIps
			', [], RateLimitedIp::class);
	}

	/**
	 * @throws Exceptions\RateLimitedIpInvalidException
	 */
	public function Validate(): void{
		$error = new Exceptions\RateLimitedIpInvalidException();

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
	 * @throws Exceptions\RateLimitedIpInvalidException
	 */
	public function Create(): void{
		$this->Validate();

		$this->CreatedAt = NOW;

		Db::Query('
			insert into RateLimitedIps (IpAddress, CreatedAt)
			values (?,
				?)
			on duplicate key update
				CreatedAt = value(CreatedAt)
		', [$this->IpAddress, $this->CreatedAt]);
	}
}
