<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Reflection\Exception\PropertyNotAccessibleException;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

class PaginationUtility
{
    /**
     * @param int|null $page
     * @param int $total
     * @param int $perPage
     * @return array
     */
    public static function calculate(?int $page, int $total, int $perPage = 10): array
    {
        $pagination['total'] = $total;
        $pagination['limit'] = $perPage;
        $pagination['pages'] = ceil($total / $pagination['limit']);
        $pagination['page'] = min($pagination['pages'], filter_var($page, FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
        $pagination['offset'] = max(($pagination['page'] - 1) * $pagination['limit'], 0);
        $pagination['start'] = $pagination['offset'] + 1;
        $pagination['end'] = min(($pagination['offset'] + $pagination['limit']), $total);
        $pagination['prev'] = ($pagination['page'] > 1) ? ($pagination['page'] - 1) : null;
        $pagination['next'] = ($pagination['page'] < $pagination['pages']) ? ($pagination['page'] + 1) : null;

        return $pagination;
    }

    /**
     * @param array $raw
     * @param string $class
     * @param array|object|null $current
     * @return array
     * @throws PropertyNotAccessibleException
     */
    public static function browseNavigation(array $raw, string $class, array|object $current = null): array
    {
        $navigation = [
            'total' => count($raw),
            'pos' => null,
            'current' => null,
            'prev' => null,
            'next' => null,
        ];

        if ($navigation['total'] > 0) {
            /** @var DataMapper $dataMapper */
            $dataMapper = GeneralUtility::makeInstance(DataMapper::class);

            $navigation['pos'] = ($current ? (int) array_search(ObjectAccess::getProperty($current, 'uid'), array_column($raw, 'uid')) : 0) + 1;
            $navigation['current'] = $dataMapper->map($class, [$raw[$navigation['pos'] - 1]])[0];
            $navigation['prev'] = ($navigation['pos'] > 1) ? ($dataMapper->map($class, [$raw[$navigation['pos'] - 2]])[0]) : null;
            $navigation['next'] = ($navigation['pos'] < $navigation['total']) ? ($dataMapper->map($class, [$raw[$navigation['pos']]])[0]) : null;
        }

        return $navigation;
    }

}
