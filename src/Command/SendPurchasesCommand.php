<?php

namespace App\Command;

use App\Service\SendPurchaseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPurchasesCommand extends Command
{
    protected static $defaultName = 'app:send-purchases';
    protected static $defaultDescription = 'Send all purchases waiting to be sent.';

    /** @var SendPurchaseService */
    private $sendPurchaseService;
    public function __construct(SendPurchaseService $sendPurchaseService, string $name = null)
    {
        parent::__construct($name);
        $this->sendPurchaseService = $sendPurchaseService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sendPurchaseService->send();

        return Command::SUCCESS;
    }
}
