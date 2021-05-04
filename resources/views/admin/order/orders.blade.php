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
           <h4 class="xp-page-title">Orders</h4>
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
                  <th>Customer</th>
                  <th>Writer</th>
                  <th>deadline</th>
                  <th>Price</th>
                  <th>Paid</th>
                  <th>Discount</th>
                  <th>Quantity</th>
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
              <h5 class="modal-title" id="modalTItle">Edit Order</h5>
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
                <input type="hidden" name="id_order" id="id_order">
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

function Edit(id)
{
            
          $.ajax({
              type:"get",
              url: '{{url('admin/orders/order/edit')}}',
              dataType:'json',
              data:{id:id},
              success:function(data){
                  if(data.error == true)
                  {
                      alert(data.msg);
                  }else{
                        $('#id_order').val(id);
                        $("#status").html(data.html);
                        $("#kkFormModal").modal('show');
                  }
              }
          });
}

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
                      url: "{{url('admin/orders/order/delete')}}"+'/'+id,
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
function submit()
{
          var form = $("#kk-form");
          $('.progress').show();
          $.ajax({
              type:"POST",
              url: '{{url('admin/orders/order/edit')}}',
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
  
            ajax: "{{ url('admin/orders/orders') }}",
  
            columns: [
  
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'customer', name: 'customer',searchable: false},
                {data: 'writer', name: 'writer',searchable: false},
                {data: 'deadline', name: 'deadline',searchable: false},
                {data: 'price', name: 'price',searchable: false},
                {data: 'paid_amount', name: 'paid_amount',searchable: false},
                {data: 'discount', name: 'discount',searchable: false},
                {data: 'quantity', name: 'quantity',searchable: false},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
  
            ],
      
  
      } );
  
  } );
  
</script>
@endsection
