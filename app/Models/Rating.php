<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['doctor_id', 'patient_id', 'rating', 'review'];

    protected static function booted()
    {
        static::saved(function ($rating) {
            $rating->doctor->update(['rating_avg' => $rating->doctor->ratings()->avg('rating')]);
        });

        static::deleted(function ($rating) {
            $rating->doctor->update(['rating_avg' => $rating->doctor->ratings()->avg('rating') ?? 0]);
        });
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
