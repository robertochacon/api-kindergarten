<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kids extends Model
{
    use HasFactory;

    protected $table = 'kids';

    protected $fillable = [
        'id','applicant_id','concubine_id','name','last_name','gender','born_date','address','region','province','municipality','district','sections','neighborhood','classroom','insurance','insurance_number','allergies','medical_conditions','medications','pediatrician','pediatrician_phone'
    ];

    public function tutors(){
        return $this->belongsToMany(Tutors::class, 'kids_tutors', 'kid_id', 'tutor_id')->withTimestamps();
    }

    public function applicant(){
        return $this->belongsTo(Applicants::class);
    }

    public function concubine(){
        return $this->belongsTo(Concubines::class);
    }


    public function authorizations(): HasMany{
        return $this->hasMany(Authorizations::class, 'kid_id', 'id')->withTimestamps();
    }

}
