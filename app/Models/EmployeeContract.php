<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    use HasFactory;
    protected $fillable   = [ 
        'id', 'group_id', 'name', 'position', 'phone', 'email ', 'created_at', 'updated_at'
    ];
}
