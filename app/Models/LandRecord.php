<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'district',
        'upazila',
        'dag_no',
        'khatian_no',
        'mouza',
        'owner_name',
        'area_percentage',
        'khajna_status',
        'mutation_status',
        'previous_khajna_amount',
        'previous_mutation_reference',
    ];

    protected $casts = [
        'area_percentage' => 'decimal:2',
        'previous_khajna_amount' => 'decimal:2',
    ];
}
