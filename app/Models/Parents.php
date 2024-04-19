<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'id','name','last_name','identification','parent','phone','address','military'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class)->withTimestamps();
    }
}
