<?php
namespace App\Actions;

use App\DTO\ScheduleResponseDTO;
use App\Models\Schedule;
use Carbon\Carbon;

class CheckSchedule
{
    
    public function __invoke($datetime = null): ScheduleResponseDTO
    {
        /**
         * Get the current date and time.
         *
         * If a datetime string is provided, it will parse it using Carbon.
         * Otherwise, it will use the current date and time.
         */
        $date = $datetime ? Carbon::parse($datetime) : now();

        \Log::info(__METHOD__, ['date' => $date, 'datetime' => $datetime]);

        // get schedule for the current day
        $schedule = Schedule::where('day_of_week', $date->dayName)->first();

        if ($schedule) {

            if ($schedule->every_other_week) {

                $start = Carbon::parse($schedule->eow_start_date);
                /**
                 * Calculate the week number based on the difference in weeks from the start date.
                 * The abs function ensures the week number is always positive.
                 */
                $weekNumber = abs($date->diffInWeeks($start));

                if ($weekNumber % 2 === 0) {
                    return new ScheduleResponseDTO(open: true, schedule: $schedule);
                }
                
                return new ScheduleResponseDTO(open: false, schedule: $schedule);
            }


            return new ScheduleResponseDTO(open: true, schedule: $schedule);
        }

        return new ScheduleResponseDTO(open: false, schedule: $this->getNextOpenDate($date));
    }


    
    /**
     * Determine if the current time falls within the lunch break period.
     *
     * This function is provided for reference purposes. Lunch break checks for display
     * will be handled on the front end.
     * 
     * @param \Carbon\Carbon $date The current time.
     * @param \App\Models\Schedule $schedule The schedule containing lunch break times.
     * @return bool True if the current time is within the lunch break period, false otherwise.
     */
    protected function isLunchBreak($date, $schedule): bool
    {
        return $date->between($schedule->lunch_start, $schedule->lunch_end);
    }


    /**
     * Get the next open date from the given date.
     *
     * @param \Carbon\Carbon $date The date from which to check for the next open date.
     * @return \App\Models\Schedule The next open schedule.
     */
    protected function getNextOpenDate(Carbon $date): Schedule
    {
        $nextOpenDate = Schedule::where('day_of_week', $date->addDay()->dayName)->first();

        if ($nextOpenDate) {
            return $nextOpenDate;
        }

        return $this->getNextOpenDate($date);
    }
}