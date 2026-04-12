<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['user_id', 'age', 'gender', 'blood_group', 'is_payment_method_verified', 'phone', 'dob', 'address', 'is_verified'];

    protected function casts(): array
    {
        return [
            'is_payment_method_verified' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
