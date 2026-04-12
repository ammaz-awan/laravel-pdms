<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'phone', 'specialization', 'experience', 'fees', 'clinic_name', 'address', 'is_verified', 'rating_avg'];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'fees' => 'decimal:2',
            'rating_avg' => 'float',
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
}
