@extends('front.layouts.app')

@section('main')



<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidbar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs Saved</h3>
                            </div>
                            {{-- <div style="margin-top: -10px;">
                                <a href="{{ route('account.createjob') }}" class="btn btn-primary">Post a Job</a>
                            </div> --}}
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Applied Date</th>
                                        {{-- <th scope="col">Applicants</th> --}}
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($savejob ->isNotEmpty())
                                    @foreach ($savejob  as $savedjob)
                                    <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{ $savedjob->job->title }}</div>
                                            <div class="info1">{{ $savedjob->job->jobType->name }} . {{ $savedjob->job->location }}</div>
                                        </td>
                                        <td>{{ $savedjob->created_at->format('d-m-y') }}</td>
                                        {{-- <td> Applications</td> --}}
                                        <td>
                                            @if ($savedjob->job->status == 1)
                                            <div class="job-status text-capitalize">Active</div>
                                            @else
                                            <div class="job-status text-capitalize">Block</div>
                                            @endif
                                            
                                        </td>
                                        <td>
                                            <div class="action-dots float-end">
                                                <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('jobDetails', $savedjob->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                    {{-- <li><a class="dropdown-item" href="{{ route('account.editJob', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li> --}}
                                                    <li><a class="dropdown-item" href="#" onclick="removeJob({{ $savedjob->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                     <td colspan="5">Jobs Applications Not Found</td>
                                    @endif
                                   
                                    
                                    
                                </tbody>
                                
                            </table>
                        </div>
                        <div>
                            {{-- Display pagination links --}}
                            {{ $savejob->links('pagination::bootstrap-4') }}

                            {{-- php artisan vendor:publish --tag=pagination --}}

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
    function removeJob(id){
         if(confirm("Are You Sure you Want to delete ?")){
               $.ajax({
                   url: '{{ route("account.removesavejob") }}',
                   type: 'post',
                   data: {id: id},
                   dataType: 'json',
                   success: function(response){
                       window.location.href = "{{ route('account.savedjobs') }}";
                   }
               })
         }
    }
</script>
    
@endsection