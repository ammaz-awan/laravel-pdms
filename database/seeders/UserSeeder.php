<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        Admin::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'permissions' => json_encode(['manage_users', 'manage_doctors', 'manage_reports']),
            ]
        );

        // Create Doctors
        $doctorSpecializations = ['Cardiology', 'Neurology', 'Orthopedics', 'Dermatology', 'Pediatrics'];
        for ($i = 1; $i <= 5; $i++) {
            $email = "doctor{$i}@example.com";
            $doctorUser = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Dr. " . fake()->firstName(),
                    'password' => bcrypt('password'),
                    'role' => 'doctor',
                    'is_active' => true,
                ]
            );

            Doctor::firstOrCreate(
                ['user_id' => $doctorUser->id],
                [
                    'specialization' => $doctorSpecializations[($i - 1) % count($doctorSpecializations)],
                    'experience' => fake()->numberBetween(1, 25),
                    'fees' => fake()->numberBetween(500, 2000),
                    'is_verified' => $i <= 3,
                    'rating_avg' => 0,
                ]
            );
        }

        // Create Patients  
        $bloodGroups = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
        for ($i = 1; $i <= 10; $i++) {
            $email = "patient{$i}@example.com";
            $patientUser = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => fake()->firstName() . ' ' . fake()->lastName(),
                    'password' => bcrypt('password'),
                    'role' => 'patient',
                    'is_active' => true,
                ]
            );

            Patient::firstOrCreate(
                ['user_id' => $patientUser->id],
                [
                    'age' => fake()->numberBetween(18, 75),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'blood_group' => fake()->randomElement($bloodGroups),
                    'is_payment_method_verified' => $i % 2 == 0,
                ]
            );
        }
    }
}
