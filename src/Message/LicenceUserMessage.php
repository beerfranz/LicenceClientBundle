<?php

namespace Beerfranz\LicenceClientBundle\Message;

use Beerfranz\LicenceClientBundle\Message\LicenceMessageAbstract;

/**
 * Usage: new LicenceUserMessage($id, $args)
 * args:
 * 	* message
 * 	* level
 **/
class LicenceUserMessage extends LicenceMessageAbstract
{
	protected $message;

	public function getMessage(): string
	{
		return $this->message;
	}

}
