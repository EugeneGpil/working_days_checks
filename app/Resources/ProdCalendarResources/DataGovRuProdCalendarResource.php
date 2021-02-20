<?php

declare(strict_types=1);

namespace App\Resources\ProdCalendarResources;

use App\Resources\ProdCalendarResources\ProdCalendarInterface;
use App\Resources\ResourcesCache\ResourcesCacheInterface;
use App\Resources\ResourcesCache\SimpleCacheInterface;
use DateInterval;
use DateTime;

class DataGovRuProdCalendarResource implements ProdCalendarInterface
{
    /**
     * Data.gov.ru domain
     * 
     * @var string $domain
     */
    protected $domain = 'https://data.gov.ru/';

    /**
     * Access token
     * 
     * @var string $accessToken
     */
    protected $accessToken;

    /**
     * Proizvcalendar version
     * 
     * @var string $proizvcalendarVersion
     */
    protected $proizvcalendarVersion;

    /**
     * Cache time
     * 
     * @var int $cacheTime
     */
    protected $cacheTime;

    /**
     * Only int days off of month cache prefix
     * 
     * @var string ONLY_DAYS_OFF_OF_MONTH_INT_ARRAY_CACHE_PREFIX
     */
    protected const ONLY_DAYS_OFF_OF_MONTH_INT_ARRAY_CACHE_PREFIX = 'data_gov_ru__only_month_days_off_int_array_';

    /**
     * Months
     * 
     * @var array $months
     */
    protected $months = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];

    /**
     * Resources cache
     * 
     * @var ResourcesCacheInterface
     */
    protected $resourcesCache;

    /**
     * Simple cache
     * 
     * @var SimpleCacheInterface
     */
    protected $simpleCache;

    /**
     * Constructor
     * 
     * @param ResourcesCacheInterface
     */
    public function __construct()
    {
        $this->simpleCache = app(SimpleCacheInterface::class);
        $this->resourcesCache = app(ResourcesCacheInterface::class);
        $this->accessToken = config('resource.data_gov_ru.access_token');
        $this->proizvcalendarVersion = config('resource.data_gov_ru.proizvcalendar_version');
        $this->cacheTime = config('resource.data_gov_ru.cache_time');
    }

    /**
     * Описание: функция проверяет является ли $date рабочим днём.
     * Для задаваемой даты проверить является ли этот день рабочим или выходным/праздничным.
     * Если $date = null, то берём текущую дату.
     *
     * @param string|null $date
     * 
     * @return bool
     */
    public function isWorkingDate($date = null): bool
    {
        $dateTime = $this->getDateTimeObject($date);
        return $this->isWorkingDateTime($dateTime);
    }

    /**
     * Is it working day? By DateTime obj
     * 
     * @var DateTime $dateTime
     * 
     * @return bool
     */
    protected function isWorkingDateTime(DateTime $dateTime): bool
    {
        $onlyDaysOffIntArray = $this->getOnlyMonthDaysOffIntArray($dateTime);

        $isItDayOff = in_array((int) $dateTime->format('d'), $onlyDaysOffIntArray);

        return !$isItDayOff;
    }

    /**
     * Описание: функция возвращает следующий рабочий день начиная с $date
     * Если день является выходным/праздничным, то вернуть дату следующего рабочего дня.
     * Если $date = null, то берём текущую дату.
     *
     * @param string|null $date
     * 
     * @return string
     */
    public function nextWorkingDate($date = null): string
    {
        $dateTime = $this->getDateTimeObject($date);
        $dateInterval = new DateInterval('P1D');

        while (true) {
            $dateTime->add($dateInterval);
            if ($this->isWorkingDateTime($dateTime) === true) {
                return $dateTime->format('Y-m-d');
            }
        }
    }

    /**
     * Описание: функция возвращает порядковый номер рабочего дня месяца
     * Вернуть порядковый номер рабочего дня месяца.
     * 
     * Если $date является рабочим днем, то вернуть его порядковый номер в месяце.
     * Если $date - выходной, вернуть 0.
     * Если $date = null, то берём текущую дату.
     * 
     * @param string|null $date
     * 
     * @return int
     */
    public function getWorkingDayNum($date = null): int
    {
        $dateTime = $this->getDateTimeObject($date);

        if ($this->isWorkingDateTime($dateTime) === false) {
            return 0;
        }

        $firstDayOfMonthString = $dateTime->format('Y-m-d');
        $firstDayOfMonthString[8] = '0';
        $firstDayOfMonthString[9] = '1';

        $firstDayOfMonthDateTime = $this->getDateTimeObject($firstDayOfMonthString);
        $dateInterval = new DateInterval('P1D');

        $countOfWorkingDays = 0;
        $currentDayOfMonthDayTime = $firstDayOfMonthDateTime;
        while (
            strcmp(
                $currentDayOfMonthDayTime->format('Y-m-d'),
                $dateTime->format('Y-m-d')
            ) <= 0
        ) {
            if ($this->isWorkingDateTime($currentDayOfMonthDayTime)) {
                ++$countOfWorkingDays;
            }
            $currentDayOfMonthDayTime->add($dateInterval);
        }

        return $countOfWorkingDays;
    }

    /**
     * Fetch data.gov.ru api
     * 
     * @param string $url
     * 
     * @return array
     */
    protected function fetchDataGovRuApi(string $url): array
    {
        $fullUrl = $this->domain . $url;
        $fullUrl .= strpos($url, '?') !== false ? '&' : '?';
        $fullUrl .= 'access_token=' . $this->accessToken;

        $responseFromCache = $this->resourcesCache
            ->get($fullUrl);

        if ($responseFromCache !== null) {
            return $responseFromCache;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $fullUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responseString = curl_exec($curl);
        curl_close($curl);
        $responseArray = json_decode($responseString, true);

        $this->resourcesCache
            ->setex(
                $fullUrl,
                $responseArray,
                $this->cacheTime
            );

        return $responseArray;
    }

    /**
     * Get dateTime object by string
     * 
     * @param string|null $date
     * 
     * @return $date
     */
    protected function getDateTimeObject(?string $date): DateTime
    {
        return $date !== null ? new DateTime($date) : new DateTime();
    }

    /**
     * Get only days off month int array
     * 
     * @param DateTime $dateTime
     * 
     * @return array
     */
    protected function getOnlyMonthDaysOffIntArray(DateTime $dateTime): array
    {
        $onlyDaysOffIntArrayFromCache = $this->simpleCache
            ->get(
                self::ONLY_DAYS_OFF_OF_MONTH_INT_ARRAY_CACHE_PREFIX
                    . $dateTime->format('Y-m')
            );

        if ($onlyDaysOffIntArrayFromCache !== null) {
            return $onlyDaysOffIntArrayFromCache;
        }

        $year = $dateTime->format('Y');

        $response = $this->fetchDataGovRuApi(
            'api/json/dataset/7708660670-proizvcalendar/version/'
                . $this->proizvcalendarVersion
                . '/content?search=' . $year
        );

        foreach ($response as $yearFromResponse) {
            if (strcmp($yearFromResponse['Год/Месяц'], (string) $year) === 0) {
                $currentYearInfo = $yearFromResponse;
                break;
            }
        }

        $monthNumber = (int) $dateTime->format('m');
        $currentMonthInfo = $currentYearInfo[$this->months[$monthNumber - 1]];

        $datesArray = explode(',', $currentMonthInfo);
        $onlyDaysOffArray = array_filter($datesArray, function ($value) {
            return strpos($value, '*') === false;
        });
        $onlyDaysOffIntArray = array_map(function ($value) {
            return (int) $value;
        }, $onlyDaysOffArray);

        $this->simpleCache
            ->set(
                self::ONLY_DAYS_OFF_OF_MONTH_INT_ARRAY_CACHE_PREFIX
                    . $dateTime->format('Y-m'),
                $onlyDaysOffIntArray
            );

        return $onlyDaysOffIntArray;
    }
}
