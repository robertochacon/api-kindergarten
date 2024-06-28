<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorizations extends Model
{
    use HasFactory;

    protected $table = 'authorizations';

    protected $fillable = [
        'id','kid_id','tutor_id'
    ];

    public function tutor(){
        return $this->hasMany(Tutors::class, 'id', 'tutor_id');
    }

    public function kid(){
        return $this->hasMany(Kids::class, 'id', 'kid_id',);
    }
}
