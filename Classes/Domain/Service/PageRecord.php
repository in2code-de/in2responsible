<?php

declare(strict_types=1);

namespace In2code\In2responsible\Domain\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageRecord
{

    /**
     * @return array returns an empty array if no inheritance is found
     */
    public function getInheritedFromPage(int $pageIdentifier): array
    {
        $pageRecord = $this->getPageRecord($pageIdentifier);
        $parentPageWithAuthor = $this->findParentWithAuthor($pageRecord);

        if (empty($parentPageWithAuthor) || ($parentPageWithAuthor['tx_in2responsible_check'] === 1 && $parentPageWithAuthor['uid'] !== $pageIdentifier)) {
            return [];
        }

        return $parentPageWithAuthor;
    }

    protected function findParentWithAuthor(array $record): array
    {
        if ($record['author'] !== '') {
            return $record;
        }

        // no author at all
        if ($record['pid'] <= 0 || $record['tx_in2responsible_check'] === 1) {
            return [];
        }

        return $this->findParentWithAuthor($this->getPageRecord($record['pid']));
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
