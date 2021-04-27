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
              <li class="breadcrumb-item"><a href="{{ url('/admin/email-template') }}">@lang('Email Template')</a></li>

              <li class="breadcrumb-item active" aria-current="page">@lang('Edit')</li>
          </ol>
          <a  href="{{ url('/admin/email-template') }}" class="btn btn-warning kk-add-new addBtn">@lang('Back')</a>
           </div>
       </div>
   </div>          
</div>
<!-- End XP Breadcrumbbar -->
<div class="xp-contentbar">
    <div class="row">
        <div class="col-md-12">
             <div class="kt-portlet kt-portlet--mobile p-4" style="overflow-x:auto;background:white">
                <p>Use the BB codes, it show the data dynamically in your emails.</p>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">Meaning</th>
                      <th scope="col">BB Code</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Name</td>
                      <td>{name}</td>
                    </tr>
                    <tr>
                      <td>Account Verification Link</td>
                      <td>{verification_link}</td>
                    </tr>
                    <tr>
                      <td>Password Reset Link</td>
                      <td>{password_reset_link}</td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="kt-portlet kt-portlet--mobile p-4" style="overflow-x:auto;background:white;margin-top:20px">
                <form method="post" action="{{ url('/admin/email-template/update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('Email Subject')</label>
                            <input type ="hidden" class="form-control" name="id" value="{{$data->id}}">
                            <input type="text" class="form-control" name="email_subject" value="{{$data->email_subject}}">
                            <div id="email-error" class="error invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('Terms & Conditions'):</label>
                            <textarea class="form-control" name="email_body">
                                {{$data->email_body}}
                            </textarea>
                            <div id="email-error" class="error invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary submit-btn">@lang('Update')</button>
                        </div>

         
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace('email_body', {
           allowedContent:true,
        });
    
      
    
    } );
    </script>


@endsection





