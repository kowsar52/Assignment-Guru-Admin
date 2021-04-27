@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Profile</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                    <h5 class="card-title text-black">Profile</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{route('admin/profile')}}" method="post" enctype="multipart/form-data">
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
	                       <label class="col-lg-3 col-form-label" for="name">Name<span class="text-danger">*</span></label>
	                       <div class="col-lg-6">
	                           <input type="text" class="form-control" value="{{$admin->name}}" id="name" name="name" placeholder="Enter name">
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="username">Username</label>
	                       <div class="col-lg-6">
	                           <input type="text" class="form-control" value="{{$admin->username}}"   readonly>
	                           <input type="text" class="form-control" value="" id="username" name="username" placeholder="Enter username">
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="email">Email</label>
	                       <div class="col-lg-6">
	                           <input type="text" class="form-control" value="{{$admin->email}}"   readonly>
	                           <input type="email" class="form-control" value="" id="email" name="email" placeholder="Enter email">
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="image">Image</label>
	                       <div class="col-lg-6">
	                           <input type="file" class="form-control" value="" id="image" name="image" >
	                           <img style="width:100px;" src="{{asset('/')}}{{$admin->image}}">
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