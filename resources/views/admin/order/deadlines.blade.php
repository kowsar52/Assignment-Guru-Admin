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
           <h4 class="xp-page-title">Deadlines</h4>
       </div>
       <div class="col-md-6 col-lg-6 text-right">
                <button type="button" id="addBtn" class="btn btn-rounded btn-primary"><i class="mdi mdi-plus mr-2"></i> Add New</button>
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
                  <th>#</th>
                  <th>Title</th>
                  <th>Duration (days)</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th>Action</th>
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
      <div class="modal fade" id="kkFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTItle">Add Deadline</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <form id="kk-form">
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
                <label for="user_name">Title</label>
                <input type="hidden" class="form-control" name="type" value="add">
                <input type="hidden" class="form-control" id="id" name="id">
                <input type="text" class="form-control"  name="title" placeholder="Enter title"/>
              </div>
              <div class="form-group">
                <label for="user_name">Duration (days)</label>
                <input type="number" class="form-control"  name="duration" placeholder="Enter Duration"/>
              </div>
              <div class="form-group">
                <label for="user_name">Price</label>
                <input type="text" class="form-control"  name="price" placeholder="Enter price"/>
              </div>
              <div class="form-group">
                <label for="user_name">Status</label>
                <select class="form-control"  name="status">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                </select>
              </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="submit()" id="update_btn">Save</button>
            </div>
          </div>
        </div>
      </div>


<script type="text/javascript">
    $('#addBtn').on('click',function(){
        $("#kk-form")[0].reset();
        $('#modalTItle').text('Add deadline');
        $('#id').val('');
        $('#kkFormModal').modal('show')

    })


        function Edit(id)
        {
          
          $.ajax({
              type:"get",
              url: '{{url('admin/deadline/edit')}}',
              dataType:'json',
              data:{id:id},
              success:function(data){
                $('#modalTItle').text('Edit deadline');
                $('#id').val(data.id);
                $('input[name="type"]').val('edit');
                $('input[name="title"]').val(data.title);
                $('input[name="price"]').val(data.price);
                $('input[name="duration"]').val(data.duration);
                $('select[name="status"]').val(data.status);
                $("#kkFormModal").modal('show');
              }
          });
        }

        function Delete(id)
        {
            Swal.fire({
              title: 'Are you sure?',
              text: "If you remove it, all related data will be also remove.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                    $.ajax({
                      type:"get",
                      url: "{{url('admin/deadline/delete')}}"+'/'+id,
                      success:function(data){
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                          )
                        $('#dataTable').DataTable().ajax.reload();
                      }
                  });
                  
              }
            })
        }
        function submit()
        {
          var form = $("#kk-form");
          $('.progress').show();
          $.ajax({
              type:"POST",
              url: '{{url('admin/deadline/save')}}',
              dataType:'json',
              data:form.serialize(),
              success:function(data){
                  if($.isEmptyObject(data.error)){
                    $('.progress').hide();
                    $("#kkFormModal").modal('hide');
                    $('#dataTable').DataTable().ajax.reload();
                  }else{
                    $('.progress').hide();
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
  
            ajax: "{{ url('admin/deadlines') }}",
  
            columns: [
  
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'duration', name: 'duration'},
                {data: 'price', name: 'price'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
  
            ],
      
  
      } );
  
  } );
  
</script>
@endsection
