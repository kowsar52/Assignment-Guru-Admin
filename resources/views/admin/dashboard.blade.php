@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Dashboard</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
               </ol>
           </div>
       </div>
   </div>          
</div>
<style type="text/css">
  .title_tag{
    font-size: 16px;
    font-weight: 600;
  }
</style>
<!-- End XP Breadcrumbbar -->
<div class="xp-contentbar">
	<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="xp-widget-box text-center">
                                    <div class="xp-widget-icon xp-widget-icon-bg bg-primary-rgba">
                                        <i class="mdi mdi-account-supervisor font-30 text-primary"></i>
                                    </div>
                                    <h4 class="xp-counter text-primary m-t-20">{{$users}}</h4>
                                    <p class="text-muted">Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="xp-widget-box text-center">
                                    <div class="xp-widget-icon xp-widget-icon-bg bg-success-rgba">
                                        <i class="mdi mdi-account-multiple-plus font-30 text-success"></i>
                                    </div>
                                    <h4 class="xp-counter text-success m-t-20">{{$MonthUsers}}</h4>
                                    <p class="text-muted">Users of this month</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="xp-widget-box text-center">
                                    <div class="xp-widget-icon xp-widget-icon-bg bg-warning-rgba">
                                        <i class="mdi mdi-animation font-30 text-warning"></i>
                                    </div>
                                    <h4 class="xp-counter text-warning m-t-20">{{$cards}}</h4>
                                    <p class="text-muted">Total Writers</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="xp-widget-box text-center">
                                    <div class="xp-widget-icon xp-widget-icon-bg bg-danger-rgba">
                                        <i class="mdi mdi-arrange-bring-forward font-30 text-danger"></i>
                                    </div>
                                    <h4 class="xp-counter text-danger m-t-20">{{$MonthCards}}</h4>
                                    <p class="text-muted">Total Customers</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
	</div>
</div>
@endsection