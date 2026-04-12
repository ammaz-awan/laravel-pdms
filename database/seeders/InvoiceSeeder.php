<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $statuses = ['paid', 'pending'];

        for ($i = 0; $i < 10; $i++) {
            Invoice::create([
                'patient_id' => $patients->random()->id,
                'total_amount' => fake()->randomFloat(2, 1000, 10000),
                'issued_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'status' => fake()->randomElement($statuses),
            ]);
        }
    }
}
