<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $fillable = [
        'id','name','last_name','type_identification','identification','parent','marital_status','phone','residence_phone','address','military','institution','range','other','email','name_work_reference_1','phone_work_reference_1','name_work_reference_2','phone_work_reference_2','file'
    ];

    public function concubine(){
        return $this->hasMany(Concubines::class, 'applicant_id');
    }

    public function kid(){
        return $this->hasMany(Kids::class, 'applicant_id');
    }

}
