
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
              <!-- Main content -->
			  <section class="content">

				<div class="row">
				<div class="col-xs-12">
				  <div class="box">
	
					  <div class="box-body">
						  <dl class="dl-horizontal">
	
						  <!-- start -->
						  <dt>ID</dt>
						  <dd>{{$data->id}}</dd>
						  <!-- ./end -->
	
						  <!-- start -->
						  <dt>{{ trans('general.user') }}</dt>
						  <dd>
				  @if ( ! isset($data->user()->username))
					{{ trans('general.no_available') }}
				  @else
				  <a href="{{url($data->user()->username)}}" target="_blank">{{ $data->user()->name }}</a>
				@endif
				</dd>
						  <!-- ./end -->
	
						@if( $data->gateway == 'PayPal' )
						  <!-- start -->
						  <dt>{{ trans('admin.paypal_account') }}</dt>
						  <dd>{{$data->account}}</dd>
						  <!-- ./end -->
	
						  @else
						   <!-- start -->
						  <dt>{{ trans('general.bank_details') }}</dt>
						  <dd>{!!App\Helper::checkText($data->account)!!}</dd>
						  <!-- ./end -->
	
						  @endif
	
						  <!-- start -->
						  <dt>{{ trans('admin.amount') }}</dt>
						  <dd><strong class="text-success">{{ App\Helper::amountFormatDecimal($data->amount) }}</strong></dd>
						  <!-- ./end -->
	
						  <!-- start -->
						  <dt>{{ trans('general.payment_gateway') }}</dt>
						  <dd>{{$data->gateway}}</dd>
						  <!-- ./end -->
	
	
						  <!-- start -->
						  <dt>{{ trans('admin.date') }}</dt>
						  <dd>{{date(App\Models\Settings::getOption('date_format'), strtotime($data->date))}}</dd>
						  <!-- ./end -->
	
						  <!-- start -->
						  <dt>{{ trans('admin.status') }}</dt>
						  <dd>
							  @if( $data->status == 'paid' )
							  <span class="label label-success">{{trans('general.paid')}}</span>
							  @else
							  <span class="label label-warning">{{trans('general.pending_to_pay')}}</span>
							  @endif
						  </dd>
						  <!-- ./end -->
	
						@if( $data->status == 'paid' )
						  <!-- start -->
						  <dt>{{ trans('general.date_paid') }}</dt>
						  <dd>
							  {{date('d M, y', strtotime($data->date_paid))}}
						  </dd>
						  <!-- ./end -->
						  @endif
				
						</dl>
					  </div><!-- box body -->
	
					  <div class="box-footer">
						   <a href="{{ url('panel/admin/withdrawals') }}" class="btn btn-default">{{ trans('auth.back') }}</a>
	
					  @if( $data->status == 'pending' )
	
	
					@endif
				</div><!-- /.box-footer -->
			</div><!-- box -->
		  </div><!-- col -->
	   </div><!-- row -->
	  </section><!-- /.content -->

        </div>
      </div>
    </div>
	</div>
</div>

@endsection



