@extends('admin.master')
@section('body')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="{{asset('/')}}assets/js/jquery.colorpicker.js"></script>
    <link href="{{asset('/')}}assets/css/jquery.colorpicker.css" rel="stylesheet" type="text/css"/>
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Card Types</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Card Types </li>
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
          <h5 class="card-title text-black">Card Type List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive m-b-30">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Icon</th>
                  <th scope="col">Color</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cards  as $card)
                <tr>
                    <td>{{$card->name}}</td>
                    <td><img src="{{asset('/')}}/uploads/icons/{{$card->icon}}" class="d-icon-img"></td>
                    <td>{{$card->color}}</td>
                    <td>
                      <a href="#" class="btn btn-primary btn-sm" onclick="EditCard({{$card->id}})">Edit</a>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="card_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="top:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Card Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="card_form">
            @csrf
            <div class="alert alert-danger print-error-msg" style="display:none;padding: 0px;margin: 2px 0px;">
                <ul style="margin:0px;">

                </ul>
            </div>
            <div class="progress" style="display: none;">
                <div class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Loading...
                </div>
            </div>
          <div class="form-group">
            <label for="cardname">Card Name</label>
            <input type="text" class="form-control" id="cardname" name="name" id="cardname">
            <input type="hidden" class="form-control" id="id" name="id">
          </div>
          <div class="form-group">
            <label for="color">Color</label>
            <input type="text" class="form-control" id="color" name="color">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit_btn" onclick="UpdateCard()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
    function EditCard(id)
    {
          
        $.ajax({
            type:"get",
            url: '{{url('admin/edit-card')}}',
            dataType:'json',
            data:{id:id},
            success:function(data){
                if(data.success == true)
                {
                    $('#cardname').val(data.name);
                    $('#color').val(data.color);
                    $('#id').val(data.id);
                    $("#card_modal").modal('show');
                }else{
                    
                    alert("card can't found!");
                }
            
            }
        });
    }
    function UpdateCard()
    {
        var color = $('#color').val();
        var n = color.includes("#");
        if(n == false)
        {
            var str =  color.slice(0, 0) + '#' + color.slice(0);
            $('#color').val(str);
        }
        
          var form = $("#card_form");
          $('.progress').show();
          $("#submit_btn").prop("disabled",true);
          $.ajax({
              type:"POST",
              url: '{{url('admin/update-card')}}',
              dataType:'json',
              data:form.serialize(),
              success:function(data){
                  $("#submit_btn").prop("disabled",false);
                  if($.isEmptyObject(data.error)){
                    $('.progress').hide();
                      alert('successfully updated.')
                      location.reload();
                  }else{
                      printErrorMsg(data.error);
                  }
              }
          });
    }
    
    function printErrorMsg (msg) {

        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li style="font-size: 12px;padding: 2px;list-style: none;">'+value+'</li>');
        });
      }
    
   $(function() {
       $('#color').colorpicker();
   });

</script>
@endsection