<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'district',
        'upazila',
        'dag_no',
        'khatian_no',
        'land_percentage',
        'applicant_id_no',
        'status',
        'tracking_no',
        'amount',
        'submitted_by',
        'notes',
    ];

    protected $casts = [
        'land_percentage' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
}
