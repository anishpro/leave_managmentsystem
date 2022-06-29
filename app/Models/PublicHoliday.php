<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicHoliday extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable   = [
        'id', 'holiday_name', 'leave_date','created_at', 'updated_at'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
