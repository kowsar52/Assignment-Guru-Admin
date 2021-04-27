@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Email Template</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">@lang('Dashboard')</a></li>
                <li class="breadcrumb-item active" aria-current="page">@lang('Email Template')</li>
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
                    <h5 class="card-title text-black">Email Template</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table display responsive nowrap table-striped table-bordered" style="width:100%" id="dataTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>@lang('Email Type')</th>

                                <th>@lang('Subject')</th>


                                <th>@lang('Action')</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
    
        $('#dataTable').DataTable( {
    
            processing: true,
    
            responsive: true,
              serverSide: true,
    
              ajax: "{{ url('admin/email-template') }}",
    
              columns: [
    
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
    
                  {data: 'email_type', name: 'email_type'},
    
                  {data: 'email_subject', name: 'email_subject'},
    
                  {data: 'action', name: 'action', orderable: false, searchable: false},
    
              ],
        
    
        } );
    
    } );
    
      </script>
@endsection

