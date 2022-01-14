<?php

namespace Jean\TimePeriods;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PeriodIterator
{
    public Carbon $until;

    public function __construct(
        public Carbon $since,
        public int $interval,
        public int $years
    ) {
        $this->until = $this->since->copy()->addYears($this->years);
    }

    public function periods(): iterable
    {
        foreach ($this->getPeriod() as $date) {
            if (! is_null($date)) {
                yield new Period(
                    $this->fixDate($date),
                    $this->since,
                    $this->interval
                );
            }
        }
    }

    protected function getPeriod(): CarbonPeriod
    {
        return CarbonPeriod::since($this->since->copy()->startOfMonth())
            ->until($this->until)
            ->months($this->interval)
            ->excludeEndDate();
    }

    protected function fixDate(Carbon $date): Carbon
    {
        $day = $this->since->day;

        if ($date->day === $day) {
            return $date;
        }

        $end_of_month = $date->copy()->endOfMonth();

        if ($day > $end_of_month->day) {
            return $date->setDay($end_of_month->day);
        }

        return $date->setDay($day);
    }
}
