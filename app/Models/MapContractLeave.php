<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapContractLeave extends Model
{
    use HasFactory, SoftDeletes ;

    protected $fillable = ['leave_type_id','contract_month','leave_days'];
}
