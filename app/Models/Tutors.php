<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutors extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'id','name','last_name','identification','parent','phone','residence_phone','address','military','file'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class, 'kids_tutors', 'tutor_id', 'kid_id')->withTimestamps();
    }

}
