<?php

declare(strict_types=1);

namespace Libeo\LboAnonymizer\Command;

use Libeo\LboAnonymizer\Command\Operation\OperationStrategy;
use Libeo\LboAnonymizer\Command\Operation\TruncateOperation;
use Libeo\LboAnonymizer\Command\Operation\UpdateOperation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;

class AnonymizeCommand extends Command
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Anonymize data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if(Environment::getContext()->isProduction()){
            $continue = $io->confirm(
                'The TYPO3 context is set to Production. Do you REALLY want to anonymize the database? This action is irreversible!',
                false
            );
            if(!$continue){
                $io->warning('Anonymization canceled.');
                return Command::SUCCESS;
            }
        }

        $databaseConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['lbo_anonymizer']['DB']['Default'] ?? [];

        if(empty($databaseConfiguration)){
            $io->warning('No anonymization configuration found. Anonymization canceled.');
            return Command::SUCCESS;
        }
        
        $io->info('Anonymizing the database...');

        foreach ($databaseConfiguration as $tableName => $tableConfiguration) {
            $operation = $tableConfiguration['operation'];
            $strategy = $this->getStrategy($operation);
            if ($strategy) {
                $strategy->execute($tableName, $tableConfiguration, $this->connectionPool, $io);
            } else {
                $io->error('Operation ' . $operation . ' does not exist');
            }
        }
        
        $io->info('Database anonymization completed.');

        return Command::SUCCESS;
    }

    private function getStrategy(string $operation): ?OperationStrategy
    {
        return match ($operation) {
            'truncate' => new TruncateOperation(),
            'update' => new UpdateOperation(),
            default => null,
        };
    }
}
