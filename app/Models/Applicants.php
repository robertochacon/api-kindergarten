<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $fillable = [
        'id','name','last_name','type_identification','identification','parent','marital_status','phone','residence_phone','address','military','institution','range','email','work_reference','personal_reference_1','personal_reference_2'
    ];

    public function concubine(){
        return $this->hasMany(Concubines::class, 'applicant_id');
    }

    public function kid(){
        return $this->hasMany(Kids::class, 'applicant_id');
    }

}
