<?php

namespace Libeo\LboAnonymizer\Command\Operation;

use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use InvalidArgumentException;

class UpdateOperation implements OperationStrategy
{
    public function execute(string $tableName, array $tableConfiguration, ConnectionPool $connectionPool, SymfonyStyle $io): void
    {
        $faker = \Faker\Factory::create('fr_CA');
        $queryBuilder = $connectionPool->getConnectionForTable($tableName);
        try {
            $result = $connectionPool
                ->getConnectionForTable($tableName)
                ->select(
                    ['uid'],
                    $tableName
                );

            while ($row = $result->fetchAssociative()) {
                $updateValues = [];
                if ($tableConfiguration['fields']) {
                    foreach ($tableConfiguration['fields'] as $fieldName => $fieldConfiguration) {
                        if (isset($fieldConfiguration['fakerFunction'])) {
                            $updateValues[$fieldName] = $faker->{$fieldConfiguration['fakerFunction']}();
                        }
                    }
                }
                $queryBuilder
                    ->update(
                        $tableName,
                        $updateValues,
                        [
                            'uid' => $row['uid'],
                        ]
                    );
            }
        } catch (InvalidArgumentException $e) {
            $io->error('ERRORS: Error with faker function ' . $fieldConfiguration['fakerFunction'] . chr(10));
        } catch (InvalidFieldNameException $e) {
            $io->error('ERRORS: Error with column name ' . $fieldName . chr(10));
        }
    }
}
