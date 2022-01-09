<?php

namespace Jean\TimePeriods;

use Carbon\Carbon;

class Interval
{
    public Carbon $end_at;

    public function __construct(
        public Carbon $start_at,
        public Carbon $birth_date,
        public ?int $interval = 1
    ) {
        $this->end_at = $this->getEndAt();
    }

    public function toString(): string
    {
        return sprintf(
            '%s - %s',
            $this->start_at->format('Y-m-d H:i:s'),
            $this->end_at->format('Y-m-d H:i:s')
        );
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    protected function getEndAt(): Carbon
    {
        $end_at = $this->start_at
            ->copy()
            ->addMonthsNoOverflow($this->interval)
            ->startOfDay();

        $day = $this->birth_date->day;

        if ($end_at->day !== $day) {
            $end_of_month = $end_at->copy()->endOfMonth();

            if ($end_of_month->day < $day) {
                $end_at->setDay($end_of_month->day);
            } else {
                $end_at->setDay($day);
            }
        }

        return $end_at;
    }
}
