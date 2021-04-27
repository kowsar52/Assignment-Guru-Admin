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
           <h4 class="xp-page-title">Template page slug</h4>
       </div>
       <div class="col-md-6 col-lg-6 text-right">
                <a href="{{url('admin/add-template-slug')}}" class="btn btn-rounded btn-primary "><i class="mdi mdi-plus mr-2"></i> Add New</a>
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
                  <th>Page</th>
                  <th>Slug</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
	</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
        function delete_slug(id){
            swal({
                title: "Are you sure?",
                text: "you will not be able to recover this request info!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: "{{url('admin/delete-template-slug')}}",
                            data: {id:id,"_token":"{{csrf_token()}}"},
                            dataType: "json",
                            cache: false,
                            success:
                                function (data) {
                                    if(data==1){
                                        swal("Slug deleted successfully!", {
                                            icon: "success",
                                        }).then(function(){
                                            location.reload();
                                        });
                                    }else {
                                        swal("Slug deleted failed!", {
                                            icon: "warning",
                                        });
                                    }
                                }
                        });

                    } else {
                        swal("The Slug is not deleted!");
                    }
                });
        }
    $(document).ready(function() {
  
      var table = $('#dataTable').DataTable( {

            processing: true,

            responsive: true,
            serverSide: true,

            ajax: "{{ url('admin/template-slug') }}",

            columns: [
                {data: 'id', name: 'id',orderable: true, searchable: false},
                {data: 'template_page_id', name: 'template_page_id',searchable: false},
                {data: 'slug', name: 'slug'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "order": [[ 0, "desc" ]],
      
  
      } );
  
  } );
</script>
@endsection
