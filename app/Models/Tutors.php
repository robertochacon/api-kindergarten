<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutors extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'id','kid_id','name','identification','parent','phone','address'
    ];

    public function kid()
    {
    	return $this->belongsTo('App\Models\Kids', 'kid_id');
    }
}
