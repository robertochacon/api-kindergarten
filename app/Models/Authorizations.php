<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorizations extends Model
{
    use HasFactory;

    protected $table = 'authorizations';

    protected $fillable = [
        'id','kid_id','name','lastname','identification','parent','phone','address'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class)->withTimestamps();
    }
}
