<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $fillable = [
        'id','name','last_name','type_identification','identification','parent','marital_status','phone','residence_phone','address','military','institution','email','work_reference','personal_reference_1','personal_reference_2'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class, 'kids_applicants', 'applicant_id', 'kid_id')->withTimestamps();
    }
}
