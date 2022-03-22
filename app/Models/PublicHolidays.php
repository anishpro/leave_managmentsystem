<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHolidays extends Model
{
    use HasFactory; 
    protected $fillable   = [
        'id', 'holidays', 'leave_date', 'group_id', 'created_at', 'updated_at'
    ];
}
