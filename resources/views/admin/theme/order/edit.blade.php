@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Edit Content</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Edit Content</li>
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
                    <h5 class="card-title text-black">Edit Content</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{url('admin/theme/edit-order-page-content',$content->id)}}" method="post" id="create_form" enctype="multipart/form-data">
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
	                       <label class="col-lg-3 col-form-label" for="language">Language<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="language" name="language">
	                               <option value="">Select Language</option>
	                               @foreach($languages as $language)
	                                    <option @php echo($content->language_id == $language->id) ? 'selected' : '' @endphp value="{{$language->id}}">{{$language->title}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="language_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="title">Title<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="{{$content->title}}">
	                           <small style="color: #ef0d0d;" id="title_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="description_content">Description<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <textarea class="form-control"  id="description_content" name="description_content" placeholder="Enter description">{{$content->description}}</textarea>
	                           <input type="hidden" name="description" id="description">
	                           <small style="color: #ef0d0d;" id="description_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="image">Image<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="file" class="form-control" id="image" name="image" accept="image/*">
	                           <small style="color: #ef0d0d;" id="image_error"></small>
	                           <img src="{{url('/')}}/{{$content->image}}" style="width: 100%;">
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
        CKEDITOR.replace('description_content', {
           allowedContent:true,
        });
    });
    
    $("#create_form").on('submit', function(e) {
        e.preventDefault();
        var desc = CKEDITOR.instances['description_content'].getData();
        $("#description").val(desc);
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
                    window.location.href = '{{url('admin/theme/order-page-contents')}}';
                }
                $('#submit_btn').prop('disabled', false);
            }
        });
    });
</script>
@endsection