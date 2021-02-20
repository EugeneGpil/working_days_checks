<?php

declare(strict_types = 1);

namespace App\Http\Controllers\WorkingDays;

use App\Http\Controllers\WorkingDays\Requests\DayRequest;
use App\Http\Controllers\Controller;
use App\Services\WorkingDays\WorkingDaysService;
use Illuminate\Http\JsonResponse;

class WorkingDaysController extends Controller
{
    /**
     * Working days service
     * 
     * @var WorkingDaysService
     */
    protected $workingDaysService;

    /**
     * Constructor
     * 
     * @param WorkingDaysService
     */
    public function __construct(WorkingDaysService $workingDaysService)
    {
        $this->workingDaysService = $workingDaysService;
    }

    /**
     * Check is it working day. Date format 2020-12-31.
     * /api/is_it_working_day?day=2021-02-22
     * 
     * @param DayRequest
     * 
     * @return JsonResponse
     */
    public function isItWorkingDay(DayRequest $request): JsonResponse
    {
        return response()->json(
            $this->workingDaysService
                ->isItWorkingDay(
                    $request->day ?? null
                )
        );
    }

    /**
     * Get next working day. Date format 2020-12-31.
     * /api/get_next_working_day?day=2021-02-20
     * 
     * @param DayRequest
     * 
     * @return JsonResponse
     */
    public function getNextWorkingDay(DayRequest $request): JsonResponse
    {
        return response()->json(
            $this->workingDaysService
                ->getNextWorkingDay(
                    $request->day ?? null
                )
        );
    }

    /**
     * Get working day number. Date format 2020-12-31.
     * /api/get_working_day_number?day=2021-02-08
     * 
     * @param DayRequest
     * 
     * @return JsonResponse
     */
    public function getWorkingDayNumber(DayRequest $request): JsonResponse
    {
        return response()->json(
            $this->workingDaysService
                ->getWorkingDayNumber(
                    $request->day ?? null
                )
        );
    }
}
