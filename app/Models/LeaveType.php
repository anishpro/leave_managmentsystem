<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = ['leave_type','mapping_required'];

    public function mapContractLeave()
    {
        return $this->hasMany(MapContractLeave::class);
    }
}
