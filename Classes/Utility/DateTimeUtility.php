<?php


namespace NL\NlUtils\Utility;


class DateTimeUtility
{
    const YMD = 'Y-m-d';

    /**
     * @param \DateTime|null $dateTime
     * @param int $years
     * @return \DateTime
     */
    public static function addYears(\DateTime $dateTime = null, int $years = 1): \DateTime
    {
        return ($dateTime ?? self::now())->add(self::createYearsInterval($years));
    }

    /**
     * @param \DateTime|null $dateTime
     * @param int $years
     * @return \DateTime
     */
    public static function subYears(\DateTime $dateTime = null, int $years = 1): \DateTime
    {
        return ($dateTime ?? self::now())->sub(self::createYearsInterval($years));
    }

    /**
     * @param int $years
     * @return \DateInterval
     */
    protected static function createYearsInterval(int $years = 1): \DateInterval
    {
        return new \DateInterval("P{$years}Y");
    }

    /**
     * @param string $datetime
     * @param string $format
     * @param \DateTimeZone|null $timezone
     * @return \DateTime
     */
    public static function dateFromFormat(string $datetime, string $format = self::YMD, \DateTimeZone $timezone = null): \DateTime
    {
        return self::startOfTheDay(\DateTime::createFromFormat($format, $datetime, $timezone));
    }

    /**
     * @param \DateTime|null $dateTime
     * @return int
     */
    public static function year(\DateTime $dateTime = null): int
    {
        return ($dateTime ?? self::now())->format('Y');
    }

    /**
     * @return \DateTime
     */
    public static function now(): \DateTime
    {
        return (new \DateTime());
    }

    /**
     * @param \DateTime|null $dateTime
     * @return int
     */
    public static function dayNumber(\DateTime $dateTime = null): int
    {
        return ($dateTime ?? self::now())->format('N');
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \DateTime
     */
    public static function startOfTheDay(\DateTime $dateTime = null): \DateTime
    {
        return ($dateTime ?? self::now())->setTime(0, 0);
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \DateTime
     */
    public static function endOfTheDay(\DateTime $dateTime = null): \DateTime
    {
        return ($dateTime ?? self::now())->setTime(23,59,59);
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \DateTime
     */
    public static function startOfTheWeek(\DateTime $dateTime = null): \DateTime
    {
        return self::startOfTheDay(($dateTime ?? self::now())->modify('Monday this week'));
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \DateTime
     */
    public static function endOfTheWeek(\DateTime $dateTime = null): \DateTime
    {
        return self::endOfTheDay(($dateTime ?? self::now())->modify('Sunday this week'));
    }
}
