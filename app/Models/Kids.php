<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kids extends Model
{
    use HasFactory;

    protected $table = 'kids';

    protected $fillable = [
        'id','applicant_id','concubine_id','code','name','last_name','gender','born_date','address','region','province','municipality','district','sections','neighborhood','classroom','insurance','insurance_number','allergies','medical_conditions','medications','pediatrician_id','file','insurance_file','vaccines_file'
    ];

    public function authorized_persons(){
        return $this->hasMany(AuthorizedPersons::class, 'applicant_id', 'applicant_id');
    }

    public function applicant(){
        return $this->belongsTo(Applicants::class);
    }

    public function concubine(){
        return $this->belongsTo(Concubines::class);
    }

    public function pediatrician(){
        return $this->belongsTo(Pediatrician::class, 'pediatrician_id', 'id');
    }

}
