<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\BaseException;
use App\Service\ProductOptionsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author Karol Gancarczyk
 *
 */
#[AsCommand(name: 'app:get-product-options')]
class GetProductOptionsCommand extends Command
{
    public function __construct(
        protected readonly ProductOptionsService $productOptionsService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln($this->productOptionsService->getSerializedProductOptions());

            return Command::SUCCESS;
        } catch (BaseException $e) {
            $output->writeln('<info>Error while running an app:</info>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }
    }
}
