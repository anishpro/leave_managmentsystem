<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_start',
        'contract_end',
        'contract_type_id',
        'user_id',
        'is_active',
        'no_of_months',
    ];

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }




    public function user()
    {
        return $this->belongsTo(User::class, );
    }
}
