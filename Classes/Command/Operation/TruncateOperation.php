<?php

namespace Libeo\LboAnonymizer\Command\Operation;

use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;

class TruncateOperation implements OperationStrategy
{
    public function execute(string $tableName, array $tableConfiguration, ConnectionPool $connectionPool, SymfonyStyle $io): void
    {
        $queryBuilder = $connectionPool->getConnectionForTable($tableName);
        $queryBuilder->truncate($tableName);
    }
}
