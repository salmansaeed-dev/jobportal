<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\SaveJobs;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\JobType;
use App\Models\JobDetails;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;

use function Termwind\style;

class AccountController extends Controller
{
    //
    public function registration(){
        return view('front.account.registration');
    }

    public function registerProsess(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password'=> 'required',
        ]);


        // if else
        if($validator->passes()){
            $user = new  User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            session()->flash('success', 'You have registered successfully!');
            $user->save();
            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function login(){
        
       return view('front.account.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                   return redirect()->route('account.profile');

            }else{
                return back();
            }

        }else{
            return redirect()->route('login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile(){
        $id = Auth::user()->id;
        
        $user = User::find($id);
        return view('front.account.profile',[
            'user'=> $user
        ]);
        
    }


    public function updatePropile(Request $request){
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id.',id',
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();
            session()->flash('success', 'Profile Update successfully!');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        }else{
          return response()->json([
              'status' => false,
              'errors' => $validator->errors()
          ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');

    }


    public function updateprofliePic(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
         'image' => 'required|image',
        ]);

        if($validator->passes()){
            $image = $request->image;
            $ext  = $image->getClientOriginalExtension();
            $imageName = $id. '-'. time(). '.'. $ext;
            $image->move(public_path('profilepic'),$imageName);
            User::where('id', $id)->update(['image'=>$imageName]);

            $path = public_path('profilepic',$imageName);

            // create new image instance (800 x 600)
            // $manager = new ImageManager(Driver::class);
            // $image = $manager->read($path);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            // $image->cover(150, 150);
            // $image->toPng()->save(public_path('profilepic/thumb/'.$imageName));

            session()->flash('success', 'Profile picture Update successfully!');

            File::delete(public_path('profilepic/').Auth::User()->image);

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }


    // Job Details

    public function createjob(){
        $category = Category::orderBy('name', 'asc')->Where('status', 1)->get();

        $jobsTypes  = JobType::orderBy('name', 'asc')->Where('status', 1)->get();
       
          
 
 
         return view('front.account.job.create',[
             'category'=> $category,
             'jobsTypes'=> $jobsTypes
         ]);
    }

    public function savejobs(Request $request){

        $validator = Validator::make($request->all(), [
            
            'title' => 'required',
            'category' => 'required',
            'jobtype' => 'required',
            'vacancy' => 'required|integer',

            'location' => 'required',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required',
        ]);

        if($validator->passes()){
              $job = new JobDetails;
              $job->title = $request->title;
              $job->category_id = $request->category;
              $job->job_type_id = $request->jobtype;
              $job->user_id = Auth::user()->id;
              $job->vacancy = $request->vacancy;
              $job->salary = $request->salary;
              $job->location = $request->location;
              $job->description	 = $request->description;
              $job->benefits = $request->benefits;
              $job->responsibility = $request->responsibility;
              $job->qualifications = $request->qualifications;
              $job->keywords = $request->keywords;
              $job->experience = $request->experience;
              $job->company_name = $request->company_name;
              $job->company_location = $request->company_location;
              $job->company_website = $request->website;
             $job->save();

            session()->flash('success', 'Job added  successfully!');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);


            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
    }

    public function myjobs(){
         $jobs = JobDetails::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);

         
         
       return view('front.account.job.my-jobs',[
         'jobs' => $jobs
       ]);
    }


    public function editJob(Request $request , $id){
          $job = JobDetails::where([
             'user_id'=> Auth::user()->id,
               'id'=> $id
          ])->first();

          if($job == null){
              abort(404);
          }
         
        $category = Category::orderBy('name', 'asc')->Where('status', 1)->get();

        $jobsTypes  = JobType::orderBy('name', 'asc')->Where('status', 1)->get();
        return view('front.account.job.edit',[
            'category'=> $category,
            'jobsTypes'=> $jobsTypes,
            'job'=> $job,
        ]);
    }


    // update job

    public function updatejobs(Request $request, $id){

        $validator = Validator::make($request->all(), [
            
            'title' => 'required',
            'category' => 'required',
            'jobtype' => 'required',
            'vacancy' => 'required|integer',

            'location' => 'required',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required',
        ]);

        if($validator->passes()){
              $job =  JobDetails::find($id);
              $job->title = $request->title;
              $job->category_id = $request->category;
              $job->job_type_id = $request->jobtype;
              $job->user_id = Auth::user()->id;
              $job->vacancy = $request->vacancy;
              $job->salary = $request->salary;
              $job->location = $request->location;
              $job->description	 = $request->description;
              $job->benefits = $request->benefits;
              $job->responsibility = $request->responsibility;
              $job->qualifications = $request->qualifications;
              $job->keywords = $request->keywords;
              $job->experience = $request->experience;
              $job->company_name = $request->company_name;
              $job->company_location = $request->company_location;
              $job->company_website = $request->website;
             $job->save();

            session()->flash('success', 'Job Update  successfully!');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);


            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
    }


    public function deleteJob(Request $request ){
        $job =JobDetails::where([
             'user_id'=> Auth::user()->id,
             'id'=> $request->JobId,
        ])->first();


        if($job == null){
            session()->flash('error', 'Job Delete Not Found');

            return response()->json([
                'status' => true,
                
            ]);
        }


        JobDetails::where('id', $request->JobId)->delete();
        session()->flash('success', 'Job Deleted Successfully');

            return response()->json([
                'status' => true,
                
            ]);



    }

    public function myjobsApplication(){
       $jobApplication = JobApplication::where('user_id', 
       Auth::user()->id)
       ->with(['job', 'job.jobType'])
       ->paginate(10);
       
        return view('front.account.job.my-jobs-application',[
            'jobApplication'=> $jobApplication,
        ]);
    }

    public function removejobs(Request $request){
       $jobApplication = JobApplication::where(['id'=>$request->id, 'user_id'=>Auth::user()->id])->first();

       if($jobApplication == null){
        session()->flash('error', 'Job Application Not Found');
            return response()->json([
               'status'=> false,
           ]);
       }

       JobApplication::find($request->id)->delete();

       session()->flash('success', 'Job Application Removed Successfully');
            return response()->json([
               'status'=> true,
        ]);


    }

    public function savedjobs(){

        $savejob = SaveJobs::where('user_id', 
       Auth::user()->id)
       ->with(['job', 'job.jobType'])
       ->paginate(10);
       
        return view('front.account.job.saved-jobs',[
            'savejob'=> $savejob,
        ]);
   }

   //  remove

   public function removesavejob(Request $request){
    $savejob = SaveJobs::where(['id'=>$request->id, 'user_id'=>Auth::user()->id])->first();

    if($savejob == null){
     session()->flash('error', 'Job  Not Found');
         return response()->json([
            'status'=> false,
        ]);
    }

    SaveJobs::find($request->id)->delete();

    session()->flash('success', 'Job  Removed Successfully');
         return response()->json([
            'status'=> true,
     ]);


 }


 // update password
 public function updatepassword(Request $request){
    $validator = Validator::make($request->all(), [
            
        'old_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password', 
    ]);

    if($validator->fails()){
        return response()->json([
            'status'=> false,
            'errors'=>  $validator->errors(),
        ]);

    }


    if(Hash::check($request->old_password,Auth::user()->password)== false){
        session()->flash('error', 'old passsword is incorrect');
        return response()->json([
            'status'=> true,
            
        ]);
    }


    $user = User::find(Auth::user()->id);
    $user->password = $request->new_password;
    $user->save();


    session()->flash('success', 'password updated successfully ');
    return response()->json([
        'status'=> true,
        
    ]);
 }

 // forgot password
   
 

}

