<?php

namespace Core\Domains;

use Illuminate\Support\Carbon;
use Lucid\Units\Job;

abstract class BaseJob extends Job
{
    abstract public function handle();

    /**
     * Get start and end off week
     *
     * @return array
     */
    public function getStartAndEndOfWeek(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateTimeString();
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateTimeString();

        return [$startOfWeek, $endOfWeek];
    }

    /**
     * Get start and end off day
     *
     * @return array
     */
    public function getStartAndEndOfDay(): array
    {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();
        $endOfDay = Carbon::now()->endOfDay()->toDateTimeString();

        return [$startOfDay, $endOfDay];
    }
}
