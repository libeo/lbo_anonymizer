<?php

namespace Libeo\LboAnonymizer\Command\Operation;

use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;

interface OperationStrategy
{
    public function execute(
        string $tableName,
        array $tableConfiguration,
        ConnectionPool $connectionPool,
        SymfonyStyle $io
    ): void;
}
