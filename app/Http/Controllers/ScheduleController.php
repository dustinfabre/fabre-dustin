<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleRequest;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();
        return Inertia::render('schedules/ScheduleIndex', [
            'schedules' => $schedules,
        ]);
    }

    public function store(ScheduleRequest $request)
    {
        $schedule = Schedule::create($request->all());
        return redirect()->route('schedules.index');
    }

    public function show(Schedule $schedule)
    {
        return Inertia::render('Schedule', [
            'schedule' => $schedule,
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->all());
        return redirect()->route('schedules.index');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        
        if ($schedule->trashed()) {
            return response()->json(null, 204);
        }

        return response()->json(null, 500);
    }
}