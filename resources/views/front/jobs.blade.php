@extends('front.layouts.app')

@section('main')


<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    {{-- <select name="sort" id="sort" class="form-control">
                        <option value="1">Latest</option>
                        <option value="0">Oldest</option>
                    </select> --}}
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
           <form action="" id="seachForm" name="seachForm">
                <div class="card border-0 shadow p-4">
                    <div class="mb-4">
                        <h2>Keywords</h2>
                        <input type="text" value="{{ Request::get('keywords') }}" name="keywords" id="keywords" placeholder="Keywords" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Location</h2>
                        <input type="text" value="{{ Request::get('location') }}" name="location" id="location" placeholder="Location" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Category</h2>
                        <select name="category" id="category" class="form-control">
                            <option  value="">Select a Category</option>
                            @if ($Categories)
                                @foreach ($Categories as $category)
                                  <option   {{ (Request::get('category')== $category->id)? 'selected': '' }} value="{{ $category->id  }}">{{ $category->name  }}</option>
                                @endforeach
                            @endif
                            {{-- <option value="">Engineering</option>
                            <option value="">Accountant</option>
                            <option value="">Information Technology</option>
                            <option value="">Fashion designing</option> --}}
                        </select>
                    </div>                   

                    <div class="mb-4">
                        <h2>Job Type</h2>
                        @if ($JobType->isNotEmpty())
                            @foreach ($JobType as $JobTypes)
                            <div class="form-check mb-2"> 
                                <input class="form-check-input " name="job_type" type="checkbox" value="{{ $JobTypes->id }}" id="job-type-{{ $JobTypes->id }}">    
                                <label class="form-check-label " for="job-type-{{ $JobTypes->id }}">{{ $JobTypes->name }}</label>
                            </div>
                            @endforeach
                        
                            
                        @endif

                        

                    </div>

                    <div class="mb-4">
                        <h2>Experience</h2>
                        <select name="experience" id="experience" class="form-control">
                            <option value="">Select Experience</option>
                            <option value="1" {{ (Request::get('experience')== 1)? 'selected': '' }}>1 Year</option>
                            <option value="2" {{ (Request::get('experience')== 2)? 'selected': '' }}>2 Years</option>
                            <option value="3" {{ (Request::get('experience')== 3)? 'selected': '' }}>3 Years</option>
                            <option value="4" {{ (Request::get('experience')== 4)? 'selected': '' }}>4 Years</option>
                            <option value="5" {{ (Request::get('experience')== 5)? 'selected': '' }}>5 Years</option>
                            <option value="6" {{ (Request::get('experience')== 6)? 'selected': '' }}>6 Years</option>
                            <option value="7" {{ (Request::get('experience')== 7)? 'selected': '' }}>7 Years</option>
                            <option value="8" {{ (Request::get('experience')== 8)? 'selected': '' }}>8 Years</option>
                            <option value="9" {{ (Request::get('experience')== 9)? 'selected': '' }}>9 Years</option>
                            <option value="10" {{ (Request::get('experience')== 10)? 'selected': '' }}>10 Years</option>
                            <option value="10_plus" {{ (Request::get('experience')== '10_plus')? 'selected': '' }}>10+ Years</option>
                        </select>
                    </div> 
                    <button type="submit" class="btn btn-outline-success">Search</button>                   
                </div>
           </form>

            </div>
            

            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if ($Jobsdetsils->isNotEmpty())
                        @foreach ($Jobsdetsils as $job)
                        
                        <div class="col-md-4">
                            <div class="card border-0 p-3 shadow mb-4">
                                <div class="card-body">
                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{$job->title }}</h3>
                                    <p>{{ Str::words($job->description , 5) }}</p>
                                    <div class="bg-light p-3 border">
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                            <span class="ps-1">{{$job->location }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                            <span class="ps-1">{{ $job->jobType->name }}</span>
                                        </p>
                                        @if (!is_null($job->salary))
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                <span class="ps-1">{{ $job->salary }}</span>
                                            </p>
                                        @endif
                                        
                                    </div>

                                    <div class="d-grid mt-3">
                                        <a href="{{ route('jobDetails', $job->id) }}" class="btn btn-primary btn-lg">Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                         @else
                         <div>Jobs Not Found</div>
                            
                        @endif
                        
                        
                     
                                                 
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>



@endsection

@section('customjs')

<script>
    $("#seachForm". submit(function(e){
        e.preventDefault();

        var url = '{{ route("jobs") }}?',

        var keyword = $("#keywords").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();

        //sort
        var sort = $("#sort").val();


        // job_type

        var cheackjobtype = $("input:checkbox[name='job_type']:checked").map(function(){
            return $(this).val();

        }).get();


        

        if(keyword != ""){
            url += '&keyword='+keyword;
        }


        // location

        if(location != ""){
            url += '&location='+location;
        }

        // category

        if(category != ""){
            url += '&category='+category;
        }


        // experience

        if(experience != ""){
            url += '&experiencey='+experience;
        }

        if(cheackjobtype.length > 0){
            url += '&job_type='+cheackjobtype;
        }


        window.location.href=url;

    }))
</script>

    
@endsection
