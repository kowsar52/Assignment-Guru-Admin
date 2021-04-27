@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Password Change</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Password Change</li>
               </ol>
           </div>
       </div>
   </div>          
</div>
<!-- End XP Breadcrumbbar -->
<div class="xp-contentbar">
	<div class="row">
		<div class="col-lg-12">
            <div class="card m-b-30">
            	<div class="card-header bg-white">
                    <h5 class="card-title text-black">Password Change</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{route('admin/password-change')}}" method="post" enctype="multipart/form-data">
                    	@csrf
                    	@if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                        @endif
                    	@if(Session::get('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        @if(Session::get('error'))
                          <div class="alert alert-danger">
                              {{Session::get('error')}}
                          </div>
                      @endif
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="password">Password<span class="text-danger">*</span></label>
	                       <div class="col-lg-6">
	                           <input type="password" class="form-control" value="" id="password" name="password" placeholder="Enter password">
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
	                       <div class="col-lg-6">
	                           <input type="password" class="form-control" value="" id="confirm_password" name="confirm_password" placeholder="Enter confirm password">
	                       </div>
	                    </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="col-lg-8">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
	</div>
</div>
@endsection