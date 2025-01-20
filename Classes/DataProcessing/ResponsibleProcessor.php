<?php

declare(strict_types=1);

namespace In2code\In2responsible\DataProcessing;

use In2code\In2responsible\Domain\Service\PageRecord;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ResponsibleProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $pageRecord = GeneralUtility::makeInstance(PageRecord::class);
        $processedData[$processorConfiguration['as'] ?? 'responsibleData'] =
            $pageRecord->getInheritedFromPage($this->getCurrentPageIdentifier());
        return $processedData;
    }

    public function getCurrentPageIdentifier(): int
    {
        return $this->getTyposcriptFrontendController() ?? 0;
    }

    /**
     * @return int|null
     */
    protected function getTyposcriptFrontendController(): int|null
    {
        $routing = $GLOBALS['TYPO3_REQUEST']->getAttribute('routing');
        return $routing ? $routing->getPageId() : null;
    }
}
