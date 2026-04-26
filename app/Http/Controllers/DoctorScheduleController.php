<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorScheduleController extends Controller
{
    private const SLOT_INTERVAL_MINUTES = 30;

    public function store(Request $request)
    {
        abort_unless(Auth::user()?->role === 'doctor', 403);

        $doctor = Auth::user()->doctor;

        $validated = $request->validate([
            'available_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $overlapExists = DoctorSchedule::where('doctor_id', $doctor->id)
            ->whereDate('available_date', $validated['available_date'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if ($overlapExists) {
            throw ValidationException::withMessages([
                'available_date' => 'This schedule overlaps with an existing availability window.',
            ]);
        }

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'available_date' => $validated['available_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return back()->with('success', 'Availability added successfully.');
    }

    public function getScheduleByDoctor(Request $request, Doctor $doctor)
    {
        $doctor->load(['user', 'schedules' => function ($query) {
            $query->orderBy('available_date')->orderBy('start_time');
        }]);

        $response = [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user?->name,
                'fees' => $doctor->fees,
                'specialization' => $doctor->specialization,
                'is_verified' => $doctor->is_verified,
            ],
            'events' => $this->formatScheduleEvents($doctor),
            'dates' => $doctor->schedules
                ->pluck('available_date')
                ->map(fn ($date) => Carbon::parse($date)->toDateString())
                ->unique()
                ->values(),
        ];

        if ($request->filled('date')) {
            $request->validate([
                'date' => ['required', 'date', 'after_or_equal:today'],
            ]);

            $response['slots'] = $this->getAvailableSlotsForDate($doctor, $request->date);
        }

        return response()->json($response);
    }

    private function getAvailableSlotsForDate(Doctor $doctor, string $date): array
    {
        $schedules = $doctor->schedules
            ->filter(fn ($schedule) => Carbon::parse($schedule->available_date)->toDateString() === $date)
            ->sortBy('start_time');

        if ($schedules->isEmpty()) {
            return [];
        }

        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('appointment_time')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
            ->all();

        $slots = [];

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($date . ' ' . Carbon::parse($schedule->start_time)->format('H:i'));
            $end = Carbon::parse($date . ' ' . Carbon::parse($schedule->end_time)->format('H:i'));

            while ($start->lt($end)) {
                $slot = $start->format('H:i');

                if (! in_array($slot, $bookedTimes, true)) {
                    $slots[] = $slot;
                }

                $start->addMinutes(self::SLOT_INTERVAL_MINUTES);
            }
        }

        return array_values(array_unique($slots));
    }

    private function formatScheduleEvents(Doctor $doctor): array
    {
        return $doctor->schedules
            ->map(function ($schedule) {
                $date = Carbon::parse($schedule->available_date)->toDateString();

                return [
                    'title' => Carbon::parse($schedule->start_time)->format('g:i A') . ' - ' . Carbon::parse($schedule->end_time)->format('g:i A'),
                    'start' => $date . 'T' . Carbon::parse($schedule->start_time)->format('H:i:s'),
                    'end' => $date . 'T' . Carbon::parse($schedule->end_time)->format('H:i:s'),
                    'allDay' => false,
                ];
            })
            ->values()
            ->all();
    }
}
