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
           <h4 class="xp-page-title">Payment GetWay</h4>
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
                  <th>Nmae</th>
                  <th>Type</th>
                  <th>Fee</th>
                  <th>Fee cents</th>
                  <th>Sendbox</th>
                  <th>Enabled</th>
                </tr>
              </thead>
            </table>
          </div>

        </div>
      </div>
    </div>
	</div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
  
      var table = $('#dataTable').DataTable( {
  
          processing: true,
  
          responsive: true,
            serverSide: true,
  
            ajax: "{{ url('admin/payment-getways') }}",
  
            columns: [
  
              {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'name', name: 'user'},
                {data: 'type', name: 'type'},
                {data: 'fee', name: 'fee'},
                {data: 'fee_cents', name: 'fee_cents'},
                {data: 'sandbox', name: 'sandbox'},
                {data: 'enabled', name: 'enabled'},
  
            ],
      
  
      } );
  
  } );
  
</script>
@endsection
