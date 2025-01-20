<?php
declare(strict_types=1);

namespace In2code\In2responsible\Hook;

use In2code\In2responsible\Domain\Service\PageRecord;
use Throwable;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PageLayout
{
    /**
     * Page record of the closest page with value in pages.author
     *
     * @var array
     */
    protected array $pageRecord = [];
    protected array $pageTsConfig = [];

    public function modify(ModifyPageLayoutContentEvent $event): void
    {
        $this->initialize($event);
        if (!empty($this->pageRecord)) {
            // @extensionScannerIgnoreLine
            $event->addHeaderContent($this->renderContent());
        }
    }

    protected function renderContent(): string
    {
        /** @var ViewFactoryInterface $viewFactory */
        $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
        /** @var ViewFactoryData $viewFactoryData */
        $viewFactoryData = new ViewFactoryData(
            templatePathAndFilename: 'EXT:in2responsible/Resources/Private/Templates/Note.html'
        );
        $view = $viewFactory->create($viewFactoryData);
        $view->assignMultiple($this->getVariables());
        return $view->render();
    }

    protected function getVariables(): array
    {
        return [
            'active' => $this->isActive(),
            'data' => $this->pageRecord,
        ];
    }

    protected function isActive(): bool
    {
        return $this->pageRecord['author'] !== ''
            && $this->gePageTsConfigByPath('tx_in2responsible./note./active') === '1';
    }

    /**
     * @param string $path
     * @return int|string|array
     */
    protected function gePageTsConfigByPath(string $path): int|array|string
    {
        try {
            return ArrayUtility::getValueByPath($this->pageTsConfig, $path);
        } catch (Throwable $exception) {
            unset($exception);
        }
        return '';
    }

    protected function initialize(ModifyPageLayoutContentEvent $event): void
    {
        $queryParams = $event->getRequest()->getQueryParams();
        $pageIdentifier = (int)($queryParams['id'] ?? 0);
        $this->pageTsConfig = BackendUtility::getPagesTSconfig($pageIdentifier);
        $pageRecord = GeneralUtility::makeInstance(PageRecord::class);
        $this->pageRecord = $pageRecord->getInheritedFromPage($pageIdentifier);
    }
}
