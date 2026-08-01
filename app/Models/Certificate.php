<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['calibration_request_id', 'certificate_number', 'file_path', 'issued_at', 'email_sent_at'])]
class Certificate extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'email_sent_at' => 'datetime',
        ];
    }

    public function calibrationRequest()
    {
        return $this->belongsTo(CalibrationRequest::class);
    }
}