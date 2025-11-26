<?
/**
 * @property string $Url
 */
class Newsletter{
	use Traits\Accessor;

	public int $NewsletterId;
	public string $Name;
	public string $UrlName;
	public ?string $Description;

	protected string $_Url;

	/**
	 * @throws Exceptions\NewsletterNotFoundException If the `Newsletter` can't be found.
	 */
	public static function GetByNewsletterMailingId(int $newsletterMailingId): Newsletter{
		return Db::Query('select n.* from Newsletters n inner join NewsletterMailings using (NewsletterId) where NewsletterMailingId = ?', [$newsletterMailingId], Newsletter::class)[0] ?? throw new Exceptions\NewsletterNotFoundException();
	}

	/**
	 * @throws Exceptions\NewsletterNotFoundException If the `Newsletter` can't be found.
	 */
	public static function GetByUrlName(?string $urlName): Newsletter{
		if($urlName === null){
			throw new Exceptions\NewsletterNotFoundException();
		}

		return Db::Query('select * from Newsletters where UrlName = ?', [$urlName], Newsletter::class)[0] ?? throw new Exceptions\NewsletterNotFoundException();
	}

	/**
	 * @throws Exceptions\NewsletterNotFoundException If the `NewsletterContact` can't be found.
	 */
	public static function Get(?int $newsletterId): Newsletter{
		if($newsletterId === null){
			throw new Exceptions\NewsletterNotFoundException();
		}

		return Db::Query('select * from Newsletters where NewsletterId = ?', [$newsletterId], Newsletter::class)[0] ?? throw new Exceptions\NewsletterNotFoundException();
	}

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/newsletters/' . $this->UrlName;
		}

		return $this->_Url;
	}
}
