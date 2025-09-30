<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    // kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    /**
     * Relasi ke model User
     * Setiap pengajuan cuti dimiliki oleh 1 user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
