<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::all();
        $statuses = ['paid', 'unpaid', 'failed'];
        $methods = ['cash', 'card', 'online'];

        for ($i = 0; $i < 20; $i++) {
            Payment::create([
                'appointment_id' => $appointments->random()->id,
                'amount' => fake()->randomFloat(2, 500, 5000),
                'status' => fake()->randomElement($statuses),
                'method' => fake()->randomElement($methods),
                'transaction_id' => fake()->uuid(),
            ]);
        }
    }
}
