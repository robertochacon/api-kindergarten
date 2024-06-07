<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorizations extends Model
{
    use HasFactory;

    protected $table = 'authorizations';

    protected $fillable = [
        'id','name','last_name','identification','parent','phone','address'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class, 'kids_authorizations', 'authorization_id', 'kid_id')->withTimestamps();
    }
}
