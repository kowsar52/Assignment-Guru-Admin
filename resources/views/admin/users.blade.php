@extends('admin.master')
@section('body')
<style>
    .pagination{
        justify-content: center;
    }
</style>
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Users</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Users</li>
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
        <div class="card-body">
          <div class="table-responsive m-b-30">
            <table class="display table table-striped table-bordered" id="dataTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Photo</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role</th>
                  <th scope="col">Country</th>
                  <th scope="col">Balance</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
            </table>
          </div>

        </div>
      </div>
    </div>
	</div>
</div>
<!-- Modal -->
      <div class="modal fade user_modal" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit user</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <form id="update_modal">
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
                <label for="user_name">Status</label>
                <input type="hidden" class="form-control" id="user_id" name="user_id">
                <select class="form-control"  name="status">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                </select>
              </div>
              <div class="form-group d-none">
                <label for="user_name">Api Access</label>
                <select class="form-control"  name="access_api">
                    <option value="1" selected>Active</option>
                    <option value="0">Deactive</option>
                </select>
              </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="UpdateUser()" id="update_btn">Save changes</button>
            </div>
          </div>
        </div>
      </div>
<script>
        function EditUser(id)
        {
          
          $.ajax({
              type:"get",
              url: '{{url('admin/edit-user')}}',
              dataType:'json',
              data:{id:id},
              success:function(data){
                $('#user_id').val(data.id);
                $('select[name="status"]').val(data.status);
                $('select[name="access_api"]').val(data.access_api);
                $(".user_modal").modal('show');
              }
          });
        }
        function deleteUser(id)
        {
            Swal.fire({
              title: 'Are you sure?',
              text: "All data related this user will be removed.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                    $.ajax({
                      type:"get",
                      url: "{{url('admin/delete-user')}}"+'/'+id,
                      success:function(data){
                        Swal.fire(
                            'Deleted!',
                            'user has been deleted.',
                            'success'
                          )
                        $('#dataTable').DataTable().ajax.reload();
                      }
                  });
                  
              }
            })
        }
        function UpdateUser()
        {
          var form = $("#update_modal");
          $('.progress').show();
          $.ajax({
              type:"POST",
              url: '{{url('admin/update-user')}}',
              dataType:'json',
              data:form.serialize(),
              success:function(data){
                  if($.isEmptyObject(data.error)){
                    $('.progress').hide();
                    $(".user_modal").modal('hide');
                    $('#dataTable').DataTable().ajax.reload();
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
</script>


<script type="text/javascript">

  $(document).ready(function() {
  
      var table = $('#dataTable').DataTable( {
  
          processing: true,
  
          responsive: true,
            serverSide: true,
  
            ajax: "{{ url('admin/users') }}",
  
            columns: [
  
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
  
                {data: 'avater', name: 'avater'},
                {data: 'first_name', name: 'first_name'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
                {data: 'country', name: 'country'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
  
            ],
      
  
      } );
  
  } );      
  
    </script>
@endsection
