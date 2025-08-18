<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedPersons extends Model
{
    use HasFactory;

    protected $table = 'authorized_persons';

    protected $fillable = [
        'id','applicant_id','name','last_name','identification','parent','phone','residence_phone','address','military','file','other'
    ];

    public function applicant(){
        return $this->belongsTo(Applicants::class);
    }

}
