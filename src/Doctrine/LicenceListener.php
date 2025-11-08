<?php

namespace Beerfranz\LicenceClientBundle\Doctrine;

use Beerfranz\LicenceClientBundle\Entity\LicenceInstance;
use Beerfranz\LicenceClientBundle\Scheduler\SchedulerProvider;

use Doctrine\ORM\Event\PostUpdateEventArgs;

use Psr\Log\LoggerInterface;


class LicenceListener
{
    public function __construct(
        private LoggerInterface $logger,
        private SchedulerProvider $scheduler,

    )
    {}

    public function postUpdate(LicenceInstance $entity, PostUpdateEventArgs $event)
    {

        $entityManager = $event->getObjectManager();
        $diffs = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);

        if (isset($diffs['configs']))
        {
            $newRecurrings = $diffs['configs'][1]['recurrings'];
            $this->logger->info('Reconfigure scheduler');
            $this->scheduler->clear();
            foreach($newRecurrings as $recurring)
            {
                $this->logger->error(json_encode($recurring));
                if ($recurring['enabled'] === true)
                {
                    $this->scheduler->addRecurringMessage(
                        $recurring['message'],
                        $recurring['trigger'],
                        $recurring['freq'],
                        $recurring['transport'],
                        $recurring['jitter'],
                    );
                }
            }
        }
    }
}
