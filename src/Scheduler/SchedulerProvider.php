<?php

namespace Beerfranz\LicenceClientBundle\Scheduler;

use Beerfranz\LicenceClientBundle\Repository\LicenceInstanceRepository;
use Beerfranz\LicenceClientBundle\Scheduler\Message;

use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Message\RedispatchMessage;

#[AsSchedule]
class SchedulerProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;

    public function __construct(
        private CacheInterface $cache,
        private LockFactory $lockFactory,
        private LicenceInstanceRepository $repo,
    ) {}

    public function getSchedule(): Schedule
    {
        if ($this->schedule !== null) {
            return $this->schedule;
        }

        $this->schedule = (new Schedule())
            ->stateful($this->cache)
            ->processOnlyLastMissedRun(true)
            ->lock($this->lockFactory->createLock('licence_check'))
        ;

        // Add recurring messages
        $recurrings = $this->repo->getRecurrings();
        foreach($recurrings as $recurring)
        {
            $this->addRecurringMessage(
                $recurring['message'],
                $recurring['trigger'],
                $recurring['freq'],
                $recurring['transport'],
                $recurring['jitter']
            );
        }
        
        return $this->schedule;
    }

    public function clear()
    {
        $this->schedule?->clear();
    }

    public function addRecurringMessage(string $messageClass, string $trigger, string $frequency, string $transport = 'async', int $jitter = 30)
    {
        $allowedTriggers = [ 'cron', 'every' ];
        if (!in_array($trigger, $allowedTriggers))
        {
            throw new \Exception('Can not add RecurringMessage with trigger type ' . $trigger . ', allowed values are '. implode(',', $allowedTriggers) . '.');
        }

        $this->schedule?->add(
            RecurringMessage::$trigger($frequency, new RedispatchMessage(new $messageClass(), $transport))->withJitter($jitter)
        );
    }
}
