<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

class ClassNamingUtility extends \TYPO3\CMS\Core\Utility\ClassNamingUtility
{
    /**
     * @param string $modelName
     * @return string
     */
    public static function translateModelNameToPresenterName(string $modelName): string
    {
        return str_replace(
            '\\Domain\\Model',
            '\\Domain\\Presenter',
            $modelName
        ) . 'Presenter';
    }
}
