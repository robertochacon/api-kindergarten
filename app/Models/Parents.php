<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'id','name','last_name','identification','parent','phone','residence_phone','address','military','institution'
    ];

    public function kids(){
        return $this->belongsToMany(Kids::class, 'kids_parents', 'parent_id', 'kid_id')->withTimestamps();
    }
}
