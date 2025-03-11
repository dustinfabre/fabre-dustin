<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Actions\CheckSchedule;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $schedule = (new CheckSchedule())($request->input('date') ?? null);
        

        return response()->json($schedule);
    }
}
