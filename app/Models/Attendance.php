<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'authorizations';

    protected $fillable = [
        'id','kid_id','user_id','attendance'
    ];

    public function user(){
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function kid(){
        return $this->hasMany(Kids::class, 'id', 'kid_id',);
    }
}
