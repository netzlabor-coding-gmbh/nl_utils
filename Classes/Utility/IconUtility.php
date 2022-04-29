<?php

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IconUtility
{
    const DEFAULT_ICONS_PATH = 'Resources/Public/Icons';

    /**
     * @param array $icons
     * @param string $extKey
     * @param string $iconProviderClassName
     * @param string $iconsPath
     * @return void
     */
    public static function registerIcons(
        array $icons,
        string $extKey,
        string $iconProviderClassName = SvgIconProvider::class,
        string $iconsPath = self::DEFAULT_ICONS_PATH
    ): void
    {
        $iconRegistry = self::getIconRegistry();

        foreach ($icons as list($filename, $identifier)) {
            if (!$identifier) {
                $identifier = GeneralUtility::trimExplode('.', $filename, true, 1)[0];
            }

            $iconRegistry->registerIcon(
                $identifier,
                $iconProviderClassName,
                ['source' => self::getIconSource($extKey, $filename, $iconsPath)]
            );
         }
    }

    /**
     * @param string $extKey
     * @param string $filename
     * @param string $iconsPath
     * @return string
     */
    public static function getIconSource(string $extKey, string $filename, string $iconsPath = self::DEFAULT_ICONS_PATH): string
    {
        return "EXT:$extKey/" . trim($iconsPath, '/') . '/' . trim($filename, '/');
    }

    /**
     * @return IconRegistry
     */
    protected static function getIconRegistry(): IconRegistry
    {
        return GeneralUtility::makeInstance(IconRegistry::class);
    }
}
