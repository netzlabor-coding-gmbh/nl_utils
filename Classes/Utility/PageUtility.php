<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class PageUtility
{
    /**
     * @param int $pageUid
     * @return int
     */
    public static function getRootPageUid(int $pageUid): int
    {
        /** @var RootlineUtility $page */
        $page = GeneralUtility::makeInstance(RootlineUtility::class, $pageUid);
        $rootLines = $page->get();

        if (!empty($rootLines)) {
            foreach ($rootLines as $rootLine) {
                if (!empty($rootLine['is_siteroot'])) {
                    return (int) $rootLine['uid'];
                }
            }
        }

        return 0;
    }
}
