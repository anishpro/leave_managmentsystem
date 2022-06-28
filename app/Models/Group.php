<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDO;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable   = [
        'id', 'group_name', 'weekend','created_at', 'updated_at'
    ];

    protected $casts = [
        'weekend' => 'array',
    ];

    public function holidays()
    {
        return $this->hasMany(PublicHolidays::class);
    }
}
