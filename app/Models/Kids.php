<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'id','name','gender','born_date','address'
    ];

    public function tutors(){
        return $this->belongsToMany(Tutors::class)->withTimestamps();
    }

}
