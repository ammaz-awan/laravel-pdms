<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['patient_id', 'total_amount', 'issued_date', 'status'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'issued_date' => 'date',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
