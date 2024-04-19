<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    use HasFactory;

    protected $table = 'kids';

    protected $fillable = [
        'id','name','last_name','gender','born_date','address','insurance','insurance_number','allergies','medical_conditions','medications'
    ];

    public function tutors(){
        return $this->belongsToMany(Tutors::class)->withTimestamps();
    }

    public function parents(){
        return $this->belongsToMany(Parents::class)->withTimestamps();
    }

    public function authorizations(){
        return $this->belongsToMany(Authorizations::class)->withTimestamps();
    }

}
