<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['name' => 'Aspirin', 'dosage' => '500mg', 'frequency' => '2x daily'],
            ['name' => 'Paracetamol', 'dosage' => '650mg', 'frequency' => '3x daily'],
            ['name' => 'Ibuprofen', 'dosage' => '400mg', 'frequency' => '2x daily'],
            ['name' => 'Amoxicillin', 'dosage' => '250mg', 'frequency' => '3x daily'],
            ['name' => 'Metformin', 'dosage' => '500mg', 'frequency' => '2x daily'],
        ];

        $appointments = Appointment::where('status', 'completed')->get();

        foreach ($appointments->take(15) as $appointment) {
            $medicinesToAdd = fake()->randomElements($medicines, fake()->numberBetween(1, 3));
            
            Prescription::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'notes' => fake()->sentence(),
                'medicines' => json_encode($medicinesToAdd),
            ]);
        }
    }
}
