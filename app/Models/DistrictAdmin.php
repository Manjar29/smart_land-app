<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'district',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];
}
