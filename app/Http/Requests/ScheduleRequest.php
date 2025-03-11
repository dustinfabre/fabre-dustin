<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day_of_week' => 'required|string|unique:schedules,day_of_week',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'lunch_start' => 'required|date_format:H:i',
            'lunch_end' => 'required|date_format:H:i',
            'every_other_week' => 'boolean',
            'eow_start_date' => 'required_if:every_other_week,true|date',
        ];
    }
}
