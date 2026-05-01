<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'status',
        'fee_snapshot',
        'notes',
        'payment_status',
        'paid_at',
        'payout_status',
        'refunded_at',
        'call_started_at',
        'completed_at',
        'agora_channel',
        'agora_uid',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'datetime:H:i',
            'fee_snapshot' => 'decimal:2',
            'call_started_at' => 'datetime',
            'completed_at' => 'datetime',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function getDateAttribute()
    {
        return $this->appointment_date;
    }

    public function getTimeAttribute()
    {
        return $this->appointment_time;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
