<?php

namespace Beerfranz\LicenceClientBundle\Scheduler;

use Beerfranz\LicenceClientBundle\Service\LicenceService;
use Beerfranz\LicenceClientBundle\Scheduler\Message;

class Handler
{
    public function __construct(
        private LicenceService $service
    ) {}

    public function __invoke(Message $message): void
    {
        $this->service->refresh();
    }
}
