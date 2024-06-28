<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    use HasFactory;

    protected $table = 'kids';

    protected $fillable = [
        'id','name','last_name','gender','born_date','address','classroom','insurance','insurance_number','allergies','medical_conditions','medications'
    ];

    public function tutors(){
        return $this->belongsToMany(Tutors::class, 'kids_tutors', 'kid_id', 'tutor_id')->withTimestamps();
    }

    public function parents(){
        return $this->belongsToMany(Parents::class, 'kids_parents', 'kid_id', 'parent_id')->withTimestamps();
    }

    public function authorizations(){
        return $this->belongsToMany(Authorizations::class, 'kids_authorizations', 'kid_id', 'authorization_id')->withTimestamps();
    }

}
