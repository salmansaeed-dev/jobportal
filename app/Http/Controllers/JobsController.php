<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\JobDetails;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\SaveJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    //  this method will Show Jops page

    public function index(Request $request){
       
        $Categories = Category::where('status',1)->get();
        $JobType = JobType::where('status',1)->get();
        $Jobsdetsils = JobDetails::where('status',1);


        if(!empty($request->keywords)){
            $Jobsdetsils = $Jobsdetsils->where(function($query)use($request){
                $query->orWhere('title', 'like', '%'. $request->keywords . '%');
                $query->orWhere('keywords', 'like', '%'. $request->keywords . '%');
            });
        }

        // location

        if(!empty($request->location)){
            $Jobsdetsils = $Jobsdetsils->where('location', $request->location);
        }

        // category

        if(!empty($request->category)){
            $Jobsdetsils = $Jobsdetsils->where('category_id', $request->category);
        }

        


        if (!empty($request->job_type)) {
            
            $jobTypeArray = explode(',', $request->job_type);
            $Jobsdetsils = $Jobsdetsils->whereIn('job_type_id', $jobTypeArray);
        }

        // experience
        if(!empty($request->experience)){
            $Jobsdetsils = $Jobsdetsils->where('experience', $request->experience);
        }

        $Jobsdetsils = $Jobsdetsils->with(['jobType', 'category'])->orderBy('created_at', 'DESC')->paginate(9);

       return view('front.jobs',[
             'Categories'=> $Categories,
             'JobType'=> $JobType,
             'Jobsdetsils' => $Jobsdetsils
       ]);

    }


    public function details($id){

        $job = JobDetails::where([
            'id'=> $id, 
             'status'=> 1
        ])->with(['jobType', 'category'])->first();


       

        if($job == null){
            abort(404);
        }
          $count = 0;
          if(Auth::user()){
                $count = SaveJobs::where([
                    'user_id'=>Auth::user()->id,
                    'job_id' => $id,
                ])->count();
          }

          // fetch application 


        //   $application = JobApplication::where('job_id', $id)->with('user')->get();

        $application = JobApplication::where('job_id', $id)->with('user')->get();
// dd($application);
            
          
         return view('front.jobDetails',[
            'job'=> $job,
            'count'=> $count,
            'application'=> $application,
         ]);
    }

   // apply
    // public function applyJob(Request $request){
    //     $id = $request->id;

    //     $job = JobDetails::where('id', $id)->first();

    //     if($job == null){
    //         session()->flash('error', 'Job dose not exist!');
    //          return response()->json([
    //              'status'=> false,
    //               'message'=> 'Job dose not exist'
    //          ]);
    //     }

    //     $employer_id = $job->user_id;

    //     if($employer_id == Auth::user()->id){
    //         session()->flash('error', 'You can not Apply on Your own job !');
    //         return response()->json([
    //             'status'=> false,
    //              'message'=> 'You can not Apply on Your own job'
    //         ]);
    //     }

    //     $application = new JobApplication();

    //     $application->job_id = $id;
    //     $application->user_id = Auth::user()->id;
    //     $application->employer_id = $employer_id;
    //     $application->save();


    //     session()->flash('success', 'You have Successfully applied');
    //         return response()->json([
    //             'status'=> true,
    //              'message'=> 'You have Successfully applied'
    //         ]);




    // }


    public function applyJob(Request $request)
{
    $request->validate([
        'id' => 'required|exists:job_details,id'
    ]);

    $id = $request->id;
    $job = JobDetails::find($id);

    if (!$job) {
        session()->flash('error', 'Job does not exist!');
        return response()->json([
            'status' => false,
            'message' => 'Job does not exist!'
        ]);
    }

    $employer_id = $job->user_id;

    // if ($employer_id == Auth::user()->id) {
    //     session()->flash('error', 'You cannot apply for your own job!');
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'You cannot apply for your own job!'
    //     ]);
    // }

    // Check if the user has already applied
    $existingApplication = JobApplication::where('job_id', $id)
        ->where('user_id', Auth::user()->id)
        ->first();

    if ($existingApplication) {
        session()->flash('error', 'You have already applied for this job!');
        return response()->json([
            'status' => false,
            'message' => 'You have already applied for this job!'
        ]);
    }

    $application = new JobApplication();
    $application->job_id = $id;
    $application->user_id = Auth::user()->id;
    $application->employer_id = $employer_id;
    $application->save();


    // SAND EMAIL

    $employer = User::where('id', $employer_id)->first();
    $emailData = [
        'employer'=> $employer,
        'user'=>Auth::user(),
        'job'=> $job,

    ];


    Mail::to()->send(new JobNotificationEmail($emailData));


    session()->flash('success', 'You have successfully applied!');
    return response()->json([
        'status' => true,
        'message' => 'You have successfully applied!'
    ]);
}


  // save jobs
  public function savejobs(Request $request){
       $id = $request->id;
       $job = JobDetails::find($id);


    //    session()->flash('error', 'job not Found');

       if($job == null){
          return response()->json([
              'status'=> false, 
          ]);
       }

       $count = SaveJobs::where([
           'user_id'=>Auth::user()->id,
            'job_id' => $id,
       ])->count();

       if($count > 0){
        session()->flash('error', 'New Alreay  Applied on this Job ');
            return response()->json([
                'status'=> false, 
            ]);
       }

       $SaveJobs = new SaveJobs();
            $SaveJobs->job_id = $id;
            $SaveJobs->user_id= Auth::user()->id;

            $SaveJobs->save();


            session()->flash('success', 'Job save Successfuly ! ');
            return response()->json([
                'status'=> true, 
            ]);
       
  }

 
   



  

}
