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
           <h4 class="xp-page-title">Coupons</h4>
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
                  <th>User</th>
                  <th>Type</th>
                  <th>Feedback</th>
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
              <h5 class="modal-title" id="modalTItle">Edit Bids</h5>
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
                <input type="hidden" name="bids_id" id="bids_id">
              </div>
              <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control"  name="status" id="status">
                   
                </select>
              </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="submit()" id="update_btn">Update</button>
            </div>
          </div>
        </div>
      </div>


<script type="text/javascript">


        function Delete(id)
        {
            Swal.fire({
              title: 'Are you sure?',
              text: "If you remove it, all related data will be also remove.l",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                    $.ajax({
                      type:"get",
                      url: "{{url('admin/review/delete')}}"+'/'+id,
                      success:function(data){
                          if(data.error == true)
                          {
                              Swal.fire(
                                'Not Deleted!',
                                data.msg,
                                'warning'
                              )
                          }else{
                              Swal.fire(
                                'Deleted!',
                                data.msg,
                                'success'
                              )
                            $('#dataTable').DataTable().ajax.reload();
                          }
                        
                      }
                  });
                  
              }
            })
        }
</script>


<script type="text/javascript">

  $(document).ready(function() {
  
      var table = $('#dataTable').DataTable( {
  
          processing: true,
  
          responsive: true,
            serverSide: true,
  
            ajax: "{{ url('admin/reviews') }}",
  
            columns: [
  
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'user', name: 'user',searchable: false},
                {data: 'type', name: 'type'},
                {data: 'feedback', name: 'feedback'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
  
            ],
      
  
      } );
  
  } );
  
</script>
@endsection
