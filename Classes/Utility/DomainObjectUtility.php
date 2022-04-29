<?php

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

class DomainObjectUtility
{
    /**
     * @param iterable $objects
     * @return array
     */
    public static function uids(iterable $objects): array
    {
        return self::properties($objects, 'uid');
    }

    /**
     * @param iterable $objects
     * @param string $propertyPath
     * @return array
     */
    public static function properties(iterable $objects, string $propertyPath): array
    {
        $properties = [];

        /** @var AbstractDomainObject $domainObject */
        foreach ($objects as $domainObject) {
            $properties[] = ObjectAccess::getPropertyPath($domainObject, $propertyPath);
        }

        return $properties;
    }

    /**
     * @param iterable $objects
     * @param string $propertyPath
     * @return array
     */
    public static function groupBy(iterable $objects, string $propertyPath): array
    {
        $grouped = [];

        /** @var AbstractDomainObject $domainObject */
        foreach ($objects as $domainObject) {
            $grouped[ObjectAccess::getPropertyPath($domainObject, $propertyPath)][] = $domainObject;
        }

        return $grouped;
    }
}
