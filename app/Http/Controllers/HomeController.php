<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobDetails;

class HomeController extends Controller
{
    //

    public function index(){
        $catagory = Category::where('status',1)->orderBy('name', 'ASC')->take(8)->get();

        $newcatagory = Category::where('status',1)->orderBy('name', 'ASC')->get();

        $isfeatured = JobDetails::where('status',1)
        ->orderBy('created_at', 'DESC')
        ->with('jobType')
        ->where('isFeatured',1)->take(6)->get();


        $latestJob = JobDetails::where('status',1)
        ->with('jobType')
        ->orderBy('created_at', 'DESC')
        ->take(6)->get();


        return view('front.home',[
            'catagory'=> $catagory,
            'isfeatured'=> $isfeatured,
            'latestJob'=> $latestJob,
            'newcatagory'=> $newcatagory,
        ]);
    }
}
