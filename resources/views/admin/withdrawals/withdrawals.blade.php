
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
           <h4 class="xp-page-title">Withdrawals</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Withdrawals</li>
               </ol>
           </div>
       </div>
   </div>          
</div>
<!-- End XP Breadcrumbbar -->
<div class="xp-contentbar">
	<div class="row">
		<div class="col-lg-12">
      @if(Session::has('success_message'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
          <i class="fa fa-check margin-separator"></i>  {{ Session::get('success_message') }}
        </div>
    @endif

      <div class="card m-b-30">
        <div class="card-body">
          <div class="table-responsive m-b-30">
            <table class="display table table-striped table-bordered" id="dataTable">
              <thead>
                <tr>

                  <th scope="col">ID</th>
                  <th scope="col">User</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Account</th>
                  <th scope="col">Status</th>
                  <th scope="col">Date</th>
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


<script>

        function acceptBtn(id)
        {
            Swal.fire({
              title: 'Are you sure?',
              text: "Accept Withdraw Request.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes,Pay!'
            }).then((result) => {
              if (result.isConfirmed) {
                    $.ajax({
                      type:"get",
                      url: "{{url('admin/withdrawals/paid')}}"+'/'+id,
                      success:function(data){
                        console.log(data)
                        if(data.success){
                          Swal.fire(
                              'Paid!',
                              'Paid Successfully.',
                              'success'
                            )
                        }else{
                          Swal.fire(
                              'Opps!',
                              data.message,
                              'error'
                            )
                        }
                        $('#dataTable').DataTable().ajax.reload();
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
  
            ajax: "{{ url('admin/withdrawals') }}",
  
            columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
  
                {data: 'user', name: 'user'},
                {data: 'amount', name: 'amount'},
                {data: 'account', name: 'account'},
                {data: 'status', name: 'status'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
  
            ],
            "order": [[ 5, "desc" ]]
      
  
      } );
  
  } );      
  
    </script>
@endsection
