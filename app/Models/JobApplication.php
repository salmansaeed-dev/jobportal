<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_application';  


    protected $fillable = [
        'job_id',
        'user_id',
        'employer_id', // Correct spelling here
        
    ];

    public function job(){
        return $this->belongsTo(JobDetails::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
