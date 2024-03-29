<?php
declare(strict_types=1);

namespace In2code\In2responsible\Hook;

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Class ModuleHook
 * as fallback for TYPO3 11
 * Todo: Can be removed once TYPO3 11 support is dropped
 */
class ModuleHookDeprecated
{
    /**
     * @return string
     */
    public function moduleBodyPostProcess(): string
    {
        $pageId = 0;

        $showResponsiblePerson = false;

        if (isset($_GET['edit']) && isset($_GET['edit']['pages'])) {
            foreach ($_GET['edit']['pages'] as $key => $value) {
                if ($value == 'edit') {
                    $pageId = (integer)$key;
                }
            }
        } elseif (isset($_GET['id'])) {
            $pageId = (integer)$_GET['id'];
        }

        if ($pageId > 0) {
            $pageTSConfig = BackendUtility::getPagesTSconfig($pageId);
            if ($pageTSConfig['tx_in2responsible.']['showMessage'] == '1') {
                $name = $pageTSConfig['tx_in2responsible.']['name.']['field'];
                $email = $pageTSConfig['tx_in2responsible.']['email.']['field'];
                $check = $pageTSConfig['tx_in2responsible.']['check.']['field'];

                $pageInfo = BackendUtility::getRecord('pages', $pageId, $name . ',' . $email . ',' . $check);
                if (trim($pageInfo[$name]) == '') {
                    $rootLine = BackendUtility::BEgetRootLine($pageId);
                    $rootLineDepth = count($rootLine);
                    for ($x = 2; $x < $rootLineDepth; $x++) {
                        $pageInfo = BackendUtility::getRecord('pages', $rootLine[$rootLineDepth - $x]['uid'], $name . ',' . $email . ',' . $check);
                        if (trim($pageInfo[$name]) != '') {
                            if ($pageInfo[$check] == '0') {
                                $showResponsiblePerson = true;
                            }
                            break;
                        }
                    }
                } else {
                    $showResponsiblePerson = true;
                }

                if ($showResponsiblePerson) {
                    $mailTo = ((!empty($pageInfo[$email])) ? $pageInfo[$email] : $pageTSConfig['tx_in2responsible.']['email']);
                    $messageHeadline = $pageTSConfig['tx_in2responsible.']['message'];
                    $messageBody = (!empty($pageInfo[$name])) ? $pageInfo[$name] : $pageTSConfig['tx_in2responsible.']['name'];

                    if ($mailTo) {
                        $messageBody = '<a target="_blank" href="mailto:' . $mailTo . '">' . $messageBody . '</a>';
                    }

                    return '
						<div class="alert alert-info">
							<div class="media">
								<div class="media-left">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-info fa-stack-1x"></i>
									</span>
								</div>
								<div class="media-body">
									<h4 class="alert-title">' . $messageHeadline . '</h4>
									<div class="alert-message">' . $messageBody . '</div>
								</div>
							</div>
						</div>';
                }
            }
        }

        return '';
    }
}
