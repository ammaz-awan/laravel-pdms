<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        for ($i = 0; $i < 25; $i++) {
            Rating::create([
                'doctor_id' => $doctors->random()->id,
                'patient_id' => $patients->random()->id,
                'rating' => fake()->numberBetween(1, 5),
                'review' => fake()->sentence(),
            ]);
        }
    }
}
