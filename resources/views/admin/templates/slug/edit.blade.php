@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Edit Template Page Slug</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Edit Template Page Slug</li>
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
                    <h5 class="card-title text-black">Edit Template Page Slug</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{url('admin/edit-template-slug',$slug->id)}}" method="post" id="create_form">
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
	                       <label class="col-lg-3 col-form-label" for="page_name">Page Name<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="page_name" name="page_name" >
	                               <option value="">Select Page Name</option>
	                               @foreach($templatepages as $page_name)
	                                    <option @php echo($page_name->id == $slug->template_page_id) ? 'selected' : '' @endphp value="{{$page_name->id}}">{{$page_name->name}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="page_name_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="name">Name<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <textarea class="form-control" id="name"  name="name" placeholder="Enter name" rows="3">{{$slug->name}}</textarea>
	                           <small style="color: #ef0d0d;" id="name_error"></small>
	                       </div>
	                    </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="col-lg-8 text-right">
                                <button type="submit" class="btn btn-primary" id="submit_btn">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
	</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        CKEDITOR.replace('details', {
           allowedContent:true,
        });
    });
    
    $("#create_form").on('submit', function(e) {
        e.preventDefault();
        $('#submit_btn').html("prcessing..");
        $('#submit_btn').prop('disabled', true);
        $("#create_form small").html('');
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data)
            {
                $('#submit_btn').html("Submit");
                $("#create_form small").html('');
                if (data.error == true) {
                    if(data.check ==  true)
                    {
                        $.each(data.message, function( key, value ) {
                            $("#"+key+'_error').html(value);
                            $("#"+key+'_error').css('color','red');
                        });
                    }else{
                        swal({
                            text: data.message,
                            icon: "error",
                        });
                    }
                }else{
                    swal({
                        text: data.message,
                        icon: "success",
                    });
                    location.reload();
                }
                $('#submit_btn').prop('disabled', false);
            }
        });
    });
</script>
@endsection