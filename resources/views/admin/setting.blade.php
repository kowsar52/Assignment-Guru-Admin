@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Setting</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Setting</li>
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
                    <h5 class="card-title text-black">Site Setting</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{route('admin/setting')}}" method="post" enctype="multipart/form-data">
                    	@csrf
                    	@if(Session::get('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                    	@foreach($settings as $setting)
                    		@if($setting->name == 'site_name')
	                        <div class="form-group row">
	                            <label class="col-lg-3 col-form-label" for="site_name">Site name<span class="text-danger">*</span></label>
	                            <div class="col-lg-6">
	                                <input type="text" class="form-control" value="{{$setting->value}}" id="site_name" name="setting[site_name]" placeholder="Enter sitename">
	                            </div>
	                        </div>
	                        @elseif($setting->name == 'favicon')
	                        	<div class="form-group row">
		                            <label class="col-lg-3 col-form-label" for="favicon">favicon icon</label>
		                            <div class="col-lg-6">
		                                <input type="file" class="form-control" id="favicon" name="setting[favicon]">
		                                <img src="{{asset('/')}}{{$setting->value}}" style="width:50px;">
		                            </div>
		                        </div>
	                        @elseif($setting->name == 'logo')
	                        	<div class="form-group row">
		                            <label class="col-lg-3 col-form-label" for="logo">Logo</label>
		                            <div class="col-lg-6">
		                                <input type="file" class="form-control" id="logo" name="setting[logo]">
		                                <img src="{{asset('/')}}{{$setting->value}}" style="width:150px;">
		                            </div>
		                        </div>
							@elseif($setting->name == 'default_avater')
	                        	<div class="form-group row">
		                            <label class="col-lg-3 col-form-label" for="default_avater">Default Avater</label>
		                            <div class="col-lg-6">
		                                <input type="file" class="form-control" id="default_avater" name="setting[default_avater]">
		                                <img src="{{asset('/')}}{{$setting->value}}" style="width:70px;margin: 5px">
		                            </div>
		                        </div>
		                    @else
		                        <div class="form-group row">
	                            <label class="col-lg-3 col-form-label text-capitalize" for="{{$setting->name}}">{{str_replace('_',' ',$setting->name)}}<span class="text-danger">*</span></label>
	                            <div class="col-lg-6">
	                                <input type="text" class="form-control" value="{{$setting->value}}" id="{{$setting->name}}" name="setting[{{$setting->name}}]" >
	                            </div>
	                        </div>
	                        @endif
                        @endforeach
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