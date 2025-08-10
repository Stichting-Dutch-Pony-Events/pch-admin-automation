<?php

namespace App\Command;

use App\Application\Service\ImportOrderApplicationService;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('import:order')]
class ImportOrderCommand extends Command
{
    public function __construct(
        private ImportOrderApplicationService $importOrderApplicationService
    ) {
        parent::__construct();
    }

    public function __invoke(#[Argument('The order code')] string $orderCode, OutputInterface $output): int
    {
        $this->importOrderApplicationService->importOrder($orderCode);
        return Command::SUCCESS;
    }
}
