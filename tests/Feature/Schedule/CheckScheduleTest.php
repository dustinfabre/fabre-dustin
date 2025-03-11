<?php

use App\Actions\CheckSchedule;
use App\DTO\ScheduleResponseDTO;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns open schedule for current day', function () {
    // Arrange
    $schedule = Schedule::create([
        'day_of_week' => Carbon::now()->dayName,
        'open_time' => '09:00:00',
        'close_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'every_other_week' => false,
    ]);

    $checkSchedule = new CheckSchedule();

    // Act
    $response = $checkSchedule();

    // Assert
    expect($response)->toBeInstanceOf(ScheduleResponseDTO::class);
    expect($response->open)->toBeTrue();
    expect($response->schedule->id)->toBe($schedule->id);
});

it('returns closed schedule for current day', function () {
    // Arrange
    $schedule = Schedule::create([
        'day_of_week' => Carbon::now()->addDay()->dayName,
        'open_time' => '09:00:00',
        'close_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'every_other_week' => false,
    ]);

    $checkSchedule = new CheckSchedule();

    // Act
    $response = $checkSchedule();

    // Assert
    expect($response)->toBeInstanceOf(ScheduleResponseDTO::class);
    expect($response->open)->toBeFalse();
    expect($response->schedule->id)->toBe($schedule->id);
});

it('returns open schedule for every other week', function () {
    // Arrange
    $start = Carbon::now()->subWeeks(2);
    $schedule = Schedule::create([
        'day_of_week' => $start->dayName,
        'open_time' => '09:00:00',
        'close_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'every_other_week' => true,
        'eow_start_date' => $start->format('Y-m-d'),
    ]);

    $checkSchedule = new CheckSchedule();

    // Act
    $response = $checkSchedule();

    // Assert
    expect($response)->toBeInstanceOf(ScheduleResponseDTO::class);
    expect($response->open)->toBeTrue();
});

it('returns closed schedule for every other week', function () {
    // Arrange
    $start = Carbon::now()->subWeeks(1);
    $schedule = Schedule::create([
        'day_of_week' => $start->dayName,
        'open_time' => '09:00:00',
        'close_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'every_other_week' => true,
        'eow_start_date' => $start->format('Y-m-d'),
    ]);

    $checkSchedule = new CheckSchedule();

    // Act
    $response = $checkSchedule();

    // Assert
    expect($response)->toBeInstanceOf(ScheduleResponseDTO::class);
    expect($response->open)->toBeFalse();
});

it('returns next open date if closed', function () {
    // Arrange
    $schedule = Schedule::create([
        'day_of_week' => Carbon::now()->addDay()->dayName,
        'open_time' => '09:00:00',
        'close_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'every_other_week' => false,
    ]);

    $checkSchedule = new CheckSchedule();

    // Act
    $response = $checkSchedule();

    // Assert
    expect($response)->toBeInstanceOf(ScheduleResponseDTO::class);
    expect($response->open)->toBeFalse();
    expect($response->schedule->id)->toBe($schedule->id);
});