<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concubines extends Model
{
    use HasFactory;

    protected $table = 'concubines';

    protected $fillable = [
        'id','name','last_name','type_identification','identification','parent','marital_status','phone','residence_phone','address','military','institution','email','name_work_reference_1','phone_work_reference_1','name_work_reference_2','phone_work_reference_2','file'
    ];

    public function kid(){
        return $this->hasMany(Kids::class, 'concubine_id');
    }
}
