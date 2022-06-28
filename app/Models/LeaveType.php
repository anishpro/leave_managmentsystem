<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['leave_type','mapping_required'];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['mapContractLeave'];


    public function mapContractLeave()
    {
        return $this->hasMany(MapContractLeave::class);
    }
}
