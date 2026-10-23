<?
namespace Enums;

enum PaymentProcessorType: string{
	case FracturedAtlas = 'fractured_atlas';
	case Stripe = 'stripe';

	public function GetDisplayName(): string{
		return match($this){
			self::FracturedAtlas => 'Fractured Atlas',
			self::Stripe => 'Stripe',
		};
	}
}
