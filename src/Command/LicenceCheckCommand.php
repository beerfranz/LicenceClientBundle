<?php

namespace Beerfranz\LicenceClientBundle\Command;

use Beerfranz\LicenceClientBundle\Service\LicenceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'licence:check', description: 'Check if new version is available')]
class LicenceCheckCommand extends Command
{
    public function __construct(
        private LicenceService $service,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $entity = $this->service->getOrCreate();
            $this->service->endpointSync($entity);

            $output->writeln("Check done");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln('<error>Failed to send: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
