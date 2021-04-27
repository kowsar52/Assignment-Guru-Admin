@extends('layouts.invoice')

@section('content')
<style>
    @media print {

        body{
            visibility: hidden;
        }
        .print_section{
            visibility: visible;
        }

    }

</style>
    <div class="content_page" style="margin: 20px">
        <strong>Hello {{ $user->name }},</strong><br><br>
             @lang('')<br>
             {{-- <a href="{{url('user/shipment/'.$data->id.'/label')}}">Click here</a><br><br> --}}


        <div class="direct-rtl">





            <div class="kt-container--fluid  kt-grid__item kt-grid__item--fluid ">



                <div class="kt-portlet kt-portlet--height-fluid print_section">
                    <table class="table  table-bordered">
                        <thead>
                          <tr>
                          <th style="{{$locale == "ar" ? 'text-align: right;':''}}">
                                {!! DNS1D::getBarcodeSVG($data->code, 'C128',2, 50) !!}
                            </th>
                            <th>
                                @if($locale == "en")
                                <img style="max-height:40px;float:right" src="{{ asset('uploads/logo/'.App\Models\Settings::getOption('logo_en')) }}">
                                @else
                                <img style="max-height:40px;float:left" src="{{ asset('uploads/logo/'.App\Models\Settings::getOption('logo_ar')) }}">
                                @endif
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th style="font-size: 16px;width:50%">@lang('Sender Information')</th>
                            <th style="font-size: 16px;width:50%">@lang('Receiver Information')</th>
                          </tr>
                          <tr>
                              {{-- sender  --}}
                            <td>
                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Name')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ $sender->name }}
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Mobile')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ $mobile_code . $sender->mobile }}
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Street Address')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ $data->sender_address }}
                                    </div>
                                </div>
                                
                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('City')
                                    </div>
                                    <div class="col-8 text-muted">
                                        @if(isset(DB::table('city_translations')->where('city_id', $sender->city)->where('locale', $locale)->first()->name))
                                        {{ DB::table('city_translations')->where('city_id', $sender->city)->where('locale', $locale)->first()->name }}
                                        @endif
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Country')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ DB::table('country_translations')->where('country_id', $sender->country)->where('locale', $locale)->first()->name }}
                                    </div>
                                </div>
                            </td>
                            {{-- receiver  --}}
                            <td>
                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Name')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{  $data->receiver_name }}
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Mobile')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ $mobile_code . $data->receiver_mobile_1 }}
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Street Address')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ $data->receiver_address }}
                                    </div>
                                </div>
                                
                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('City')
                                    </div>
                                    <div class="col-8 text-muted">
                                        @if(isset(DB::table('city_translations')->where('city_id', $data->receiver_city)->where('locale', $locale)->first()->name))
                                        {{ DB::table('city_translations')->where('city_id', $data->receiver_city)->where('locale', $locale)->first()->name }}
                                        @endif
                                    </div>
                                </div>

                                <div class="row" style="padding: 5px;">
                                    <div class="col-4">
                                        @lang('Country')
                                    </div>
                                    <div class="col-8 text-muted">
                                        {{ DB::table('country_translations')->where('country_id', $data->receiver_country)->where('locale', $locale)->first()->name }}
                                    </div>
                                </div>
                            </td>
                       
                          </tr>

                          {{-- shipment info  --}}
                          <tr>
                            {{-- sender  --}}
                          <td>
                              <div class="row" style="padding: 5px;">
                                  <div class="col-4">
                                    @lang('Date of shipment')
                                  </div>
                                  <div class="col-8 text-muted">
                                    {{ date('d M Y',strtotime($data->ship_date))}}
                                  </div>
                              </div>

                              <div class="row" style="padding: 5px;">
                                  <div class="col-4">
                                    @lang('Shipment Weight Over 8KG ?')
                                  </div>
                                  <div class="col-8 text-muted">
                                    {{ __($data->isOverweight == 1 ? "Yes" : "No") }} ( {{$data->weight}}KG)
                                  </div>
                              </div>

                              <div class="row" style="padding: 5px;">
                                  <div class="col-4">
                                      @lang('Breakable Shipment')
                                  </div>
                                  <div class="col-8 text-muted">
                                      {{ __($data->isBroken == 1 ? "Yes" : "No") }}
                                  </div>
                              </div>
                              
                          </td>
                          {{-- receiver  --}}
                          <td>
                              <div class="row" style="padding: 5px;">
                                  <div class="col-12">
                                      @lang('Details')
                                  </div>
                                  <div class="col-12 text-muted">
                                      {{  substr($data->details,0,500) }}
                                  </div>
                              </div>

                             
                          </td>
                     
                        </tr>
                    </tbody>
                    <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="row" style="padding: 5px;">
                                            <div class="col-12" style="    position: absolute;
                                            font-weight: 700;">
                                                @lang('Terms & Conditions')
                                            </div>
                                            <div class="col-12 text-muted" style="margin-top: 10px">
                                                <p><?= App\Models\Settings::getOption('invoice_terms_'.$locale)?></p>
                                            </div>
                                        </div>
          
                                       
                                    </td>
                                </tr>

                        </tfoot>
                      </table>






                </div>

            </div>

            <br><br>
<strong>@lang('Regards')</strong><br>
    <i>@lang('Maysan Express') </i>


        </div>

    </div>

@endsection

@section('script')



@endsection

