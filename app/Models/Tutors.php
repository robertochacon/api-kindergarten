<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutors extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'id','name','identification','parent','phone','address'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class)->withTimestamps();
    }

}
