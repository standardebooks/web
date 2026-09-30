<?
class Tag{
	public int $TagId;
	public string $Name;
	public string $UrlName;
	public Enums\TagType $Type;
	public protected(set) string $Url {get => $this->Url; }
}
