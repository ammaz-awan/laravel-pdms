<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $statuses = ['pending', 'completed', 'cancelled'];

        for ($i = 0; $i < 20; $i++) {
            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'date' => fake()->dateTimeBetween('+1 days', '+30 days')->format('Y-m-d'),
                'time' => fake()->time('H:i'),
                'status' => fake()->randomElement($statuses),
                'notes' => fake()->sentence(),
            ]);
        }
    }
}
