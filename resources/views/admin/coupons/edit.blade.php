@extends('admin.master')
@section('body')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Edit Coupon</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
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
                    <h5 class="card-title text-black">Edit Coupon</h5>
                </div>
                <div class="card-body">
                    <form class="xp-form-validate" action="{{url('admin/coupon/edit',$coupon->id)}}" method="post" id="create_form">
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
	                       <label class="col-lg-3 col-form-label" for="title">Title<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="{{$coupon->title}}">
	                           <small style="color: #ef0d0d;" id="title_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="code">Code<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="text" class="form-control" id="code" name="code" placeholder="Enter code" value="{{$coupon->code}}">
	                           <small style="color: #ef0d0d;" id="code_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="percentage">Percentage<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="number" class="form-control" id="percentage" name="percentage" placeholder="Enter percentage" value="{{$coupon->percentage}}">
	                           <small style="color: #ef0d0d;" id="percentage_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="deadline">Deadline<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <input type="date" class="form-control" id="deadline" name="deadline" placeholder="Enter deadline" value="{{date('m/d/Y',strtotime($coupon->deadline))}}">
	                           <small style="color: #ef0d0d;" id="deadline_error"></small>
	                       </div>
	                    </div>
	                    <div class="form-group row">
	                       <label class="col-lg-3 col-form-label" for="status">Status<span class="text-danger">*</span></label>
	                       <div class="col-lg-8">
	                           <select class="form-control" id="status" name="status">
	                               <option @php echo($coupon->status == 1) ? 'selected' : '' @endphp value="1">Active</option>
	                               <option @php echo($coupon->status == 0) ? 'selected' : '' @endphp value="0">Deactive</option>
	                           </select>
	                           <small style="color: #ef0d0d;" id="deadline_error"></small>
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