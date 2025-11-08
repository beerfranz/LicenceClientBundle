<?php

namespace Beerfranz\LicenceClientBundle\MessageHandler;

use Beerfranz\LicenceClientBundle\Repository\LicenceInstanceRepository;
use Beerfranz\LicenceClientBundle\Message\LicenceMessageInterface;

use Doctrine\ORM\EntityManagerInterface;

abstract class LicenceMessageHandlerAbstract
{

	protected $licenceInstanceRepo;
	protected $em;

	public function __construct(
		LicenceInstanceRepository $licenceInstanceRepo,
		EntityManagerInterface $em,
	) {
		$this->licenceInstanceRepo = $licenceInstanceRepo;
		$this->em = $em;
	}

	public function commit(LicenceMessageInterface $message, int $returnCode = 0, string $returnMessage = 'Success')
	{
		$instance = $this->licenceInstanceRepo->findSingleton();
        $instance->commitMessage($message->getId(), $returnCode, $returnMessage);
        $this->em->flush();
	}

}
