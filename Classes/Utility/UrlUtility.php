<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class UrlUtility
{
    /**
     * @param string $uri
     * @param array $args
     * @return string
     */
    public static function addQueryArgs(string $uri, array $args = []): string
    {
        $parts = self::toParts($uri, true);

        $parts['query'] = array_merge($parts['query'], $args);

        return self::buildFromParts($parts);
    }

    /**
     * @param array $parts
     * @return string
     */
    public static function buildFromParts(array $parts): string
    {
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
        $host = isset($parts['host']) ? $parts['host'] : '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $user = isset($parts['user']) ? $parts['user'] : '';
        $pass = isset($parts['pass']) ? ':' . $parts['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parts['path']) ? $parts['path'] : '/';
        $query = isset($parts['query']) ? '?' . (is_array($parts['query']) ? http_build_query($parts['query']) : $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * @param string $uri
     * @param bool $queryAsArray
     * @return array
     */
    public static function toParts(string $uri, bool $queryAsArray = true): array
    {
        $parts = parse_url($uri);

        if (true === $queryAsArray) {
            $query = [];

            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);
            }

            $parts['query'] = $query;
        }

        return $parts;
    }

    /**
     * @param string $parameter
     * @param array $conf
     * @return string
     */
    public static function typolinkUrl(string $parameter, array $conf = []): string
    {
        $conf['parameter'] = $parameter;

        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        return $configurationManager->getContentObject()->typoLink_URL($conf);
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function isAbsolute(string $url): bool
    {
        $pattern = "/^(?:ftp|https?|feed):\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
    (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
    (?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
    (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";

        return (bool) preg_match($pattern, $url);
    }

    /**
     * @param string $url
     * @return string
     */
    public static function absolute(string $url): ?string
    {
        return self::isAbsolute($url) ? $url : PathUtility::getAbsoluteWebPath(substr($url,0, 1) === "/" ? $url : "/$url");
    }
}
