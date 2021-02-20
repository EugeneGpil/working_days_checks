<?php

declare(strict_types = 1);

namespace App\Resources\ProdCalendarResources;

interface ProdCalendarInterface
{
    /**
     * Описание: функция проверяет является ли $date рабочим днём.
     * Для задаваемой даты проверить является ли этот день рабочим или выходным/праздничным.
     * Если $date = null, то берём текущую дату.
     *
     * @param string|null $date
     * 
     * @return bool
     */
    public function isWorkingDate($date = null): bool;

    /**
     * Описание: функция возвращает следующий рабочий день начиная с $date
     * Если день является выходным/праздничным, то вернуть дату следующего рабочего дня.
     * Если $date = null, то берём текущую дату.
     *
     * @param string|null $date
     * 
     * @return string
     */
    public function nextWorkingDate($date = null): string;

    /**
     * Описание: функция возвращает порядковый номер рабочего дня месяца начиная с $date
     * Вернуть порядковый номер рабочего дня месяца начиная с $date. Но если $date является рабочим днем,
     * то вернуть его порядковый номер в месяце.
     * Если $date = null, то берём текущую дату.
     * 
     * @param string|null $date
     * 
     * @return int
     */
    public function getWorkingDayNum($date = null): int;
}
