<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionUtility
{
    const DEFAULT_EXCLUDE_LIST = 'layout,select_key,pages,recursive';
    const DEFAULT_INCLUDE_LIST = 'pi_flexform';

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @param string|null $excludeList
     * @param string|null $addList
     */
    public static function registerPlugin(string $extensionName, string $pluginName, ?string $excludeList = self::DEFAULT_EXCLUDE_LIST, ?string $addList = self::DEFAULT_INCLUDE_LIST): void
    {
        $extensionKey = self::getExtensionKey($extensionName);
        $pluginSignature = self::getPluginSignature($extensionName, $pluginName);

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extensionName,
            $pluginName,
            "LLL:EXT:$extensionKey/Resources/Private/Language/locallang_db.xlf:tx_$pluginSignature.name",
            IconUtility::getIconSource($extensionKey, self::getIconFilename($pluginName))
        );

        if ($excludeList) {
            // Disable the display of layout and select_key fields for the plugins
            // provided by the extension
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = $excludeList;
        }

        if ($addList) {
            // Activate the display of the plug-in flexform field and set FlexForm definition
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = $addList;

            if (str_contains($addList, 'pi_flexform')) {
                ExtensionManagementUtility::addPiFlexFormValue(
                    $pluginSignature,
                    "FILE:EXT:$extensionKey/Configuration/FlexForms/flexform_$pluginSignature.xml"
                );
            }
        }
    }

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @param array $controllerActions
     * @param array $nonCacheableControllerActions
     * @param string $pluginType
     * @param bool $addPageTSConfig
     * @param bool $registerIcon
     * @return void
     */
    public static function configurePlugin(
        string $extensionName,
        string $pluginName,
        array $controllerActions,
        array $nonCacheableControllerActions = [],
        string $pluginType = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN,
        bool $addPageTSConfig = true,
        bool $registerIcon = true
    ): void
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionName,
            $pluginName,
            $controllerActions,
            $nonCacheableControllerActions,
            $pluginType
        );

        if (true === $addPageTSConfig) {
            self::addPageTsConfig($extensionName, $pluginName);
        }

        if (true === $registerIcon) {
            self::registerIcon($extensionName, $pluginName);
        }
    }

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @return void
     */
    protected static function addPageTsConfig(string $extensionName, string $pluginName): void
    {
        $extensionKey = self::getExtensionKey($extensionName);
        $pluginKey = self::getPluginKey($pluginName);
        $pluginSignature = self::getPluginSignature($extensionName, $pluginName);
        $iconIdentifier = self::getIconIdentifier($extensionName, $pluginName);

        ExtensionManagementUtility::addPageTSConfig(
            "mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    $pluginKey {
                        iconIdentifier = $iconIdentifier
                        title = LLL:EXT:$extensionKey/Resources/Private/Language/locallang_db.xlf:tx_$pluginSignature.name
                        description = LLL:EXT:$extensionKey/Resources/Private/Language/locallang_db.xlf:tx_$pluginSignature.description
                        tt_content_defValues {
                            CType = list
                            list_type = $pluginSignature
                        }
                    }
                }
                show = *
            }
       }"
        );
    }

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @return void
     */
    protected static function registerIcon(string $extensionName, string $pluginName): void
    {
        $extensionKey = self::getExtensionKey($extensionName);
        $pluginKey = self::getPluginKey($pluginName);
        $iconIdentifier = self::getIconIdentifier($extensionName, $pluginName);

        IconUtility::registerIcons([
            [self::getIconFilename($pluginKey), $iconIdentifier]
        ], $extensionKey);
    }

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @return string
     */
    protected static function getIconIdentifier(string $extensionName, string $pluginName): string
    {
        $extensionKey = self::getExtensionKey($extensionName);
        $pluginKey = self::getPluginKey($pluginName);

        return "$extensionKey-plugin-$pluginKey";
    }

    /**
     * @param string $pluginName
     * @return string
     */
    protected static function getIconFilename(string $pluginName): string
    {
        $pluginKey = self::getPluginKey($pluginName);

        return "user_plugin_$pluginKey.svg";
    }

    /**
     * @param string $pluginName
     * @return string
     */
    protected static function getPluginKey(string $pluginName): string
    {
        return strtolower($pluginName);
    }

    /**
     * @param string $extensionName
     * @return string
     */
    protected static function getExtensionKey(string $extensionName): string
    {
        return GeneralUtility::camelCaseToLowerCaseUnderscored($extensionName);
    }

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @return string
     */
    protected static function getPluginSignature(string $extensionName, string $pluginName): string
    {
        $extensionName = self::getExtensionName($extensionName);

        return strtolower($extensionName . '_' . $pluginName);
    }

    /**
     * @param string $extensionName
     * @return string
     */
    protected static function getExtensionName(string $extensionName): string
    {
        // Check if vendor name is prepended to extensionName in the format {vendorName}.{extensionName}
        $vendorName = null;
        $delimiterPosition = strrpos($extensionName, '.');
        if ($delimiterPosition !== false) {
            $vendorName = str_replace('.', '\\', substr($extensionName, 0, $delimiterPosition));
            trigger_error(
                'Calling method ' . __METHOD__ . ' with argument $extensionName ("' . $extensionName . '") containing the vendor name ("' . $vendorName . '") is deprecated and will stop working in TYPO3 11.0.',
                E_USER_DEPRECATED
            );
            $extensionName = substr($extensionName, $delimiterPosition + 1);

            if (!empty($vendorName)) {
                self::checkVendorNameFormat($vendorName, $extensionName);
            }
        }
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionName)));
    }

    /**
     * Check a given vendor name for CGL compliance.
     * Log a deprecation message if it is not.
     *
     * @param string $vendorName The vendor name to check
     * @param string $extensionName The extension name that is affected
     */
    protected static function checkVendorNameFormat(string $vendorName, string $extensionName): void
    {
        if (preg_match('/^[A-Z]/', $vendorName) !== 1) {
            trigger_error('The vendor name from tx_' . $extensionName . ' must begin with a capital letter.', E_USER_DEPRECATED);
        }
    }

    /**
     * @param array $tables
     * @param string $extKey
     * @param bool $addLLrefForTCAdescr
     * @return void
     */
    public static function allowTablesOnStandardPages(array $tables, string $extKey, bool $addLLrefForTCAdescr = true): void
    {
        foreach ($tables as $table) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);

            if (true === $addLLrefForTCAdescr) {
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr($table, "EXT:$extKey/Resources/Private/Language/locallang_csh_$table.xlf");
            }
        }
    }
}
