<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class HtmlUtility
{
    /**
     * @param string $value
     * @param string $parseFuncTSPath
     * @return string
     */
    public static function format(string $value, string $parseFuncTSPath = 'lib.parseFunc_RTE'): string
    {
        if (TYPO3_MODE === 'BE') {
            EnvironmentUtility::simulateFrontendEnvironment();
        }

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([]);
        $content = $contentObject->parseFunc($value, [], '< ' . $parseFuncTSPath);

        if (TYPO3_MODE === 'BE') {
            EnvironmentUtility::resetFrontendEnvironment();
        }

        return $content;
    }
}
