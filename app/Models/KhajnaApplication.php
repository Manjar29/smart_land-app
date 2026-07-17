<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhajnaApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'district',
        'upazila',
        'dag_no',
        'khatian_no',
        'land_percentage',
        'tax_year',
        'mobile',
        'nid',
        'receipt_no',
        'amount',
        'status',
        'submitted_by',
    ];

    protected $casts = [
        'land_percentage' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
}
