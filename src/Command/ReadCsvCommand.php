<?php

namespace App\Command;

use App\Service\CustomerCsvService;
use App\Service\PurchaseCsvService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCsvCommand extends Command
{
    protected static $defaultName = 'app:read-csv';
    protected static $defaultDescription = 'Import csv files for customers and purchases';

    /** @var CustomerCsvService */
    private $customerCsvService;

    /** @var PurchaseCsvService */
    private $purchaseCsvService;

    public function __construct(CustomerCsvService $customerCsvService, PurchaseCsvService $purchaseCsvService, string $name = null)
    {
        parent::__construct($name);
        $this->customerCsvService = $customerCsvService;
        $this->purchaseCsvService = $purchaseCsvService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->customerCsvService->start();

        $this->purchaseCsvService->start();

        return Command::SUCCESS;
    }
}
