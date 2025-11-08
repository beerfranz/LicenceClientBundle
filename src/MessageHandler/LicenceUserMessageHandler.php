<?php

namespace Beerfranz\LicenceClientBundle\MessageHandler;

use Beerfranz\LicenceClientBundle\Entity\LicenceUserMessage as Entity;
use Beerfranz\LicenceClientBundle\Message\LicenceUserMessage;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LicenceUserMessageHandler extends LicenceMessageHandlerAbstract
{
	public function __invoke(LicenceUserMessage $message)
    {
    	$entity = new Entity($message->getId(), $message->getMessage());
    	$this->em->persist($entity);
        $this->em->flush();
       	$this->commit($message, 0, 'Saved');
    }

}
