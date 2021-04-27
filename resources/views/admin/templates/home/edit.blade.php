@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Template Translation</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Template Translation</li>
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
                    <h5 class="card-title text-black">Template Translation</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{url('admin/edit-template-translation',$translation->id)}}" method="post" id="create_form">
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
	                                    <option @php echo($page_name->id == $translation->page_id) ? 'selected' : '' @endphp value="{{$page_name->id}}">{{$page_name->name}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="page_name_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="language">Language<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="language" name="language" >
	                               <option value="">Select Language</option>
	                               @foreach($languages as $language)
	                                    <option @php echo($language->id == $translation->language_id) ? 'selected' : '' @endphp value="{{$language->id}}">{{$language->title}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="language_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="slug">Slug<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="slug" name="slug" >
	                               <option value="">Select Slug</option>
	                               @foreach($slugs as $slug)
	                                    <option @php echo($slug->slug == $translation->slug) ? 'selected' : '' @endphp value="{{$slug->slug}}">{{$slug->name}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="slug_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="translation">Translation<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <textarea  class="form-control"  id="translation" rows="3" name="translation" placeholder="Enter translation">{{$translation->translation}}</textarea>
	                           <small style="color: #ef0d0d;" id="translation_error"></small>
	                       </div>
	                    </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="col-lg-8">
                                <button type="submit" id="submit_btn" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
	</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $("#page_name").change(function(){
        $("#submit_btn").html("processing..");
        $("#submit_btn").prop("disabled",true);
        var page = $(this).val();
        if(page != '')
        {
            $.ajax({
                type: "GET",
                url: "{{url('admin/get-template-page-slug')}}",
                data: {id:page},
                dataType: "json",
                cache: false,
                success:
                    function (data) {
                        if(data.error == true)
                        {
                            swal({
                                text: data.msg,
                                icon: "error",
                            });
                        }else{
                            
                            $("#slug").html(data.data);
                            
                        }
                            $("#submit_btn").html("Submit");
                            $("#submit_btn").prop("disabled",false);
                    }
            });
        }
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