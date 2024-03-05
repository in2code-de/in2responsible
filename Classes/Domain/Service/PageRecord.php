<?php

declare(strict_types=1);

namespace In2code\In2responsible\Domain\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageRecord
{

    public function getClosestPageRecord(int $pageIdentifier): array
    {
        $row = $this->getPageRecord($pageIdentifier);
        if ($row['author'] === '' && $row['tx_in2responsible_check'] === 0 && $row['pid'] > 0) {
            $row = $this->getClosestPageRecord($row['pid']);
        }
        return $row;
    }

    protected function getPageRecord(int $pageIdentifier): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        return (array)$queryBuilder
            ->select('*')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($pageIdentifier, Connection::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();
    }
}