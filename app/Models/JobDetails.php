<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetails extends Model
{
    use HasFactory;


    
    // Make sure to specify the actual table name if it's not 'job_details'

    protected $table = 'job_details';  


    // Define the fillable attributes, ensuring it's a valid array of column names
    // protected $fillable = [
    //     'title', 
    //     'category_id',
    //     'job_type_id',
    //     'vacancy',
    //     'salary',
    //     'location',
    //     'description',
    //     'benefits',
    //     'responsibility',
    //     'qualifications',
    //     'keywords',
    //     'experience',
    //     'company_name',
    //     'company_location',
    //     'company_website',
    // ];  


    protected $fillable = [
        'title',
        'category_id',
        'job_type_id', // Correct spelling here
        'vacancy',
        'salary',
        'location',
        'description',
        'benefits',
        'responsibility',
        'qualifications',
        'keywords',
        'experience',
        'company_name',
        'company_location',
        'company_website',
        'status',
        'isFeatured',
    ];

// Replace with actual column names

    // If your table doesn't have the `created_at` and `updated_at` columns
    
    // public $timestamps = false;


    public function jobType(){
        return $this->belongsTo(JobType::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function applications(){
        return $this->hasMany(JobApplication::class);
    }

    

}
