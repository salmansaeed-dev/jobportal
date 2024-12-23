@extends('front.layouts.app')

@section('main')


<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now">
                                    <a class="heart_mark" href="#"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Job description</h4>
                            
                               <p>{{ $job->description }}</p>
                        </div>
                        
                            @if (!empty($job->responsibility ))
                            <div class="single_wrap">
                                <h4>Responsibility</h4>
                               <p>{{ $job->responsibility }}</p>
                            </div>
                            @endif
                            
                       
                       
                            @if (!empty($job->qualifications ))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                <p>{{ $job->qualifications }}</p>
                                </div>
                            @endif
                            
                        
                        
                            
                            @if (!empty($job->benefits ))
                            <div class="single_wrap">
                                <h4>Benefits</h4>
                               <p>{{ $job->benefits }}</p>
                            </div>
                            @endif
                        
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            {{-- <a href="#" class="btn btn-secondary">Save</a> --}}
                            @if (Auth::check())
                             <a href="#" onclick="saveJop({{ $job->id }})" class="btn btn-secondary">Save</a>
                            @else
                            <a href="javascript:void(0)"  class="btn btn-secondary disabled">Login to save</a>
                            @endif
                            {{--  --}}
                            @if (Auth::check())
                             <a href="#" onclick="ApplyJop({{ $job->id }})" class="btn btn-primary">Apply</a>
                            @else
                            <a href="javascript:void(0)"  class="btn btn-primary disabled">Login to Apply</a>
                            @endif
                            
                        </div>
                    </div>
                </div>

                
            </div>
            
              <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summery</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ $job->created_at->format('d-m-Y') }}</span></li>
                                <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                @if (!empty($job->salary ))
                                 <li>Salary: <span>{{ $job->salary }}</span></li>
                                @endif
                                <li>Location: <span>{{ $job->location }}</span></li>
                                <li>Job Nature: <span> {{ $job->jobType->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>{{ $job->company_name }}</span></li>
                                @if (!empty($job->company_location ))
                                   <li>Locaion: <span>{{ $job->company_location }}</span></li>
                                @endif

                                @if (!empty($job->company_website ))
                                <li>Webite: <span><a href="{{ $job->company_website }}">{{ $job->company_website }}</a></span></li>
                               @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div> 
            
            {{-- Job Applicants Table --}}

         
                
            

<div class="card shadow border-0 mt-4">
    <div class="job_details_header">
        <div class="single_jobs white-bg d-flex justify-content-between align-items-center p-3">
            <div class="jobs_left d-flex align-items-center">
                <div class="jobs_content">
                    <h4 class="mb-0">Applicants</h4>
                </div>
            </div>
        </div>
    </div>
    

    <div class="descript_wrap white-bg">
        @if ($application->isNotEmpty())
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Applied Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($application as $app)
                <tr>
                    <td>{{ $app->user->name }}</td>
                    <td>{{ $app->user->email }}</td>
                    <td>{{ $app->created_at->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center p-4">
            <p class="text-muted">No applicants have applied yet.</p>
        </div>
        @endif
    </div>
</div>


            
        </div>
    </div>
</section>



@endsection

@section('customjs')

<script>
    function ApplyJop(id){
        if(confirm("Are You Sure you want to Apply on this Job")){
            $.ajax({
                 url: '{{ route("applyJob") }}',
                 type: 'post',
                 data: {id:id},
                 datatype: 'json',
                 success: function(responce){
                       window.location.reload();
                 }
            })
        }
    }

    // save


    function saveJop(id){
        $.ajax({
            url: '{{ route("savejobs") }}',
                 type: 'post',
                 data: {id:id},
                 datatype: 'json',
                 success: function(responce){
                       window.location.reload();
                 }    
            })
    }
</script>

    
@endsection