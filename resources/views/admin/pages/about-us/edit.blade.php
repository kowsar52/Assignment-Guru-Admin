@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Edit Page</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
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
                    <h5 class="card-title text-black">Edit Page</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{url('admin/pages/edit-page',$page->id)}}" method="post" id="create_form">
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
                      @php
                      $pages_name[0]['name'] = 'About Us'; 
                      $pages_name[0]['slug'] = 'about-us';
                      $pages_name[1]['name'] = 'Terms And Conditions'; 
                      $pages_name[1]['slug'] = 'terms-conditions';
                      $pages_name[2]['name'] = 'Refund Policy'; 
                      $pages_name[2]['slug'] = 'refund-policy'; 
                      $pages_name[3]['name'] = 'Privacy Policy'; 
                      $pages_name[3]['slug'] = 'privacy-policy'; 
                      $pages_name[4]['name'] = 'Academic Integrity'; 
                      $pages_name[4]['slug'] = 'academic-integrity'; 
                      $pages_name[5]['name'] = 'Contact Us'; 
                      $pages_name[5]['slug'] = 'contact-us'; 
                      $pages_name[6]['name'] = 'FAQ'; 
                        $pages_name[6]['slug'] = 'faq'; 
                      $pages_name[7]['name'] = 'Becoma A Writer'; 
                        $pages_name[7]['slug'] = 'become-writer'; 
                      $pages_name[8]['name'] = 'Recommendations'; 
                        $pages_name[8]['slug'] = 'recommendations'; 
                    @endphp
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="page_name">Page Name<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="page_name" name="page_name">
	                               <option value="">Select Page Name</option>
	                               @foreach($pages_name as $page_name)
	                                    <option @php echo($page->slug == $page_name['slug']) ? ' selected' : '' @endphp value="{{$page_name['slug']}}">{{$page_name['name']}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="page_name_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="language">Language<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="language" name="language">
	                               <option value="">Select Language</option>
	                               @foreach($languages as $language)
	                                    <option @php echo($page->language_id == $language->id) ? ' selected' : '' @endphp value="{{$language->id}}">{{$language->title}}</option>
	                               @endforeach
	                           </select>
	                           <small style="color: #ef0d0d;" id="language_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="title">Title<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="text" class="form-control" id="title" value="{{$page->title}}" name="title" placeholder="Enter Title">
	                           <small style="color: #ef0d0d;" id="title_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="heading">Short Description</label>
	                       <div class="col-lg-8">
	                           <textarea class="form-control" id="heading" rows="3"  name="heading" placeholder="Enter Short Description">{{$page->heading}}</textarea>
	                           
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="details">Details<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <textarea class="form-control"  id="details" name="details" placeholder="Enter about us">{{$page->details}}</textarea>
	                           <input type="hidden" name="details_content" id="details_content" >
	                           <small style="color: #ef0d0d;" id="details_content_error"></small>
	                       </div>
	                    </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="meta_title">Meta title</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="meta_title" name="meta_title" value="{{$page->meta_title}}" placeholder="Enter meta title" >
                                <small style="color: #ef0d0d;" id="meta_title_error"></small>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="meta_key">Meta key</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="meta_key" name="meta_key" value="{{$page->meta_key}}" placeholder="Enter meta key.Separated by comma" >
                                <small style="color: #ef0d0d;" id="meta_key_error"></small>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="meta_description">Meta description</label>
                            <div class="col-lg-8">
                                <textarea class="form-control"  id="meta_description" name="meta_description" placeholder="Enter meta description">{{$page->meta_des}}</textarea>
                                <small style="color: #ef0d0d;" id="meta_description_error"></small>
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
        var desc = CKEDITOR.instances['details'].getData();
        $("#details_content").val(desc);
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