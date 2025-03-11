<?php
namespace App\DTO;

use App\Models\Schedule;

class ScheduleResponseDTO
{

    /**
     * schedule DTO for consistent return data
     */
    public function __construct(public bool $open,public Schedule $schedule)
    {

    }


    public function fromArray(array $data): ScheduleResponseDTO
    {
        return new ScheduleResponseDTO(
            open: $data['open'],
            schedule: $data['schedule']
        );
    }

    public function toArray(): array
    {
        return [
            'open' => $this->open,
            'schedule' => $this->schedule
        ];
    }
}