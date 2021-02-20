<?php

declare(strict_types = 1);

namespace App\Services\WorkingDays;

use App\Resources\ProdCalendarResources\ProdCalendarInterface;

class WorkingDaysService
{
    /**
     * Prod calendar resource
     * 
     * @var ProdCalendarInterface
     */
    protected $prodCalendarResource;

    /**
     * Constructor
     * 
     * @param ProdCalendarInterface
     */
    public function __construct(
        ProdCalendarInterface $prodCalendarResource
    ) {
        $this->prodCalendarResource = $prodCalendarResource;
    }

    /**
     * Check is it working day
     * 
     * @param string|null $day Date in 2020-12-31 format
     * 
     * @return array
     */
    public function isItWorkingDay(?string $day): array
    {
        return [
            'status' => true,
            'payload' => $this->prodCalendarResource
                ->isWorkingDate($day)
        ];
    }

    /**
     * Get next working day
     * 
     * @param string|null $day Date in 2020-12-31 format
     * 
     * @return array
     */
    public function getNextWorkingDay(?string $day): array
    {
        return [
            'status' => true,
            'payload' => $this->prodCalendarResource
                ->nextWorkingDate($day)
        ];
    }

    /**
     * Get working day number
     * 
     * @param string|null $day Date in 2020-12-31 format
     * 
     * @return array
     */
    public function getWorkingDayNumber(?string $day): array
    {
        return [
            'status' => true,
            'payload' => $this->prodCalendarResource
                ->getWorkingDayNum($day)
        ];
    }
}
