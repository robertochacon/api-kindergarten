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
        'id','name','last_name','gender','born_date','address','region','province','municipality','district','sections','neighborhood','classroom','insurance','insurance_number','allergies','medical_conditions','medications'
    ];

    public function tutors(){
        return $this->belongsToMany(Tutors::class, 'kids_tutors', 'kid_id', 'tutor_id')->withTimestamps();
    }

    public function applicants(){
        return $this->belongsToMany(Applicants::class, 'kids_applicants', 'kid_id', 'applicant_id')->withTimestamps();
    }

    public function authorizations(): HasMany{
        return $this->hasMany(Authorizations::class, 'kid_id', 'id')->withTimestamps();
    }

}
