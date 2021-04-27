@extends('admin.master')
@section('body')
<style>
    .pagination{
        justify-content: center;
    }
    .card {
      border-radius: 0px !important;
    }
</style>
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
   <div class="row">      
       <div class="col-md-6 col-lg-6"> 
           <h4 class="xp-page-title">Api Documentation</h4>
       </div>
       <div class="col-md-6 col-lg-6">
           <div class="xp-breadcrumb">
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="{{route('admin/api-document')}}"><i class="icon-home"></i></a></li>
                   <li class="breadcrumb-item active" aria-current="page">Api Documentation</li>
               </ol>
           </div>
       </div>
   </div>          
</div>
<!-- End XP Breadcrumbbar -->
<div class="xp-contentbar">
	<div class="row">
		<div class="col-lg-12">

        <!--api documentation -->
        <div class="card mt-3">
            <div class="card-header">
                @lang('Introduction')
            </div>
            <div class="card-body">
                <p>Numberish provides a REST API that uses HTTP methods for the requests, authenticates via HTTP Basic Auth, and returns the responses in JSON format.</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                @lang('Authentication')
            </div>
            <div class="card-body">
                <p>Authenticate your account when using the API by including your secret API key in the request. Your API keys carry many privileges, so be sure to keep it secret!</p>
            </div>
        </div>
            
 
        {{-- Register  --}}
        {{-- <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Register')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/register</code>
            <br><br><h6>Form Data</h6>  
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Perameter')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">name <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Full name</td>
                </tr>
                <tr>
                  <th scope="row">email<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Email Address (unique)</td>
                </tr>
                <tr>
                  <th scope="row">username<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>username (unique)</td>
                </tr>
                <tr>
                  <th scope="row">password<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>New Password</td>
                </tr>
                <tr>
                  <th scope="row">terms<br><i class="text-muted">required</i></th>
                  <th scope="row">boolean</th>
                  <td>Must agree term and condition</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div> --}}

        {{-- Login  --}}
        {{-- <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Login')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/login</code>
            <br><br><h6>Form Data</h6>  
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Perameter')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">username / email <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>You can user email or username as you want</td>
                </tr>
                <tr>
                  <th scope="row">password<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Account Password</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div> --}}

        {{-- forgot-password  --}}
        {{-- <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Forgot Password')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/forgot-password</code>
            <br><br><h6>Form Data</h6>  
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Perameter')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"> email <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Email Address</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div> --}}

        {{-- forgot-password  --}}
        {{-- <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Forgot Password')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/forgot-password</code>
            <br><br><h6>Form Data</h6>  
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Perameter')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"> email <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Email Address</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div> --}}

        {{-- get card types  --}}
        <div class="card mt-3" id="getCardTypeApi">
            <div class="card-header">
              @lang('Get all card type')
            </div>
            <div class="card-body">
                <h6>HTTP Request</h6>
                <code style="font-size:13px;font-weight:700;b">GET {{url('/')}}/api/card/types?api_key={api_key}</code>
              <br><br><h6>URL Parameters</h6>  
             <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">@lang('Parameter')</th>
                    <th scope="col">@lang('Description')</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">api_key <br><i class="text-muted">required</i></th>
                    <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                  </tr>
                </tbody>
              </table>
              <br><h6>Returns</h6>
             <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">@lang('Attribute')</th>
                    <th scope="col">@lang('Type')</th>
                    <th scope="col">@lang('Description')</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">id</th>
                    <td>integer</td>
                    <td>Card Type ID</td>
                  </tr>
                  <tr>
                    <th scope="row">name</th>
                    <td>string</td>
                    <td>Type name</td>
                  </tr>

                  <tr>
                    <th scope="row">icon</th>
                    <td>url</td>
                    <td>Card type icon path</td>
                  </tr>

                  <tr>
                    <th scope="row">color</th>
                    <td>string</td>
                    <td>Card Color Code</td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>

        {{-- get cards  --}}
        <div class="card mt-3" id="getCardApi">
            <div class="card-header">
                @lang('Get all cards')
            </div>
            <div class="card-body">
                <h6>HTTP Request</h6>
                <code style="font-size:13px;font-weight:700;b">GET {{url('/')}}/api/card/user_cards?per_page=10&api_key={api_key}</code>
                <br><br><h6>URL Parameters</h6>  
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th scope="col">@lang('Parameter')</th>
                    <th scope="col">@lang('Description')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                      <th scope="row">per_page <br><i class="text-muted"></i></th>
                      <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                    </tr>
                    <tr>
                      <th scope="row">api_key <br><i class="text-muted">required</i></th>
                      <td>Per page limit . Default 10</td>
                    </tr>
                </tbody>
                </table>
                <br><h6>Returns</h6>
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th scope="col">@lang('Attribute')</th>
                    <th scope="col">@lang('Type')</th>
                    <th scope="col">@lang('Description')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                      <th scope="row">id</th>
                      <td>integer</td>
                      <td>Card ID</td>
                    </tr>
                    <tr>
                      <th scope="row">user_id</th>
                      <td>integer</td>
                      <td>User ID</td>
                    </tr>

                    <tr>
                      <th scope="row">card_type</th>
                      <td>integer</td>
                      <td>Card type ID</td>
                    </tr>
                    <tr>
                      <th scope="row">card_name</th>
                      <td>string</td>
                      <td>Card Name</td>
                    </tr>

                    <tr>
                      <th scope="row">description</th>
                      <td>string</td>
                      <td>Details</td>
                    </tr>
                    <tr>
                      <th scope="row">caption</th>
                      <td>boolean</td>
                      <td>True/False</td>
                    </tr>
                    <tr>
                      <th scope="row">is_public</th>
                      <td>boolean</td>
                      <td>True/False</td>
                   </tr>
                    <tr>
                      <th scope="row">status</th>
                      <td>integer</td>
                      <td>Card Status (1=Active, 0= deactive)</td>
                    </tr>
                    <tr>
                      <th scope="row">order</th>
                      <td>integer</td>
                      <td>Card Order By</td>
                    </tr>
                    <tr>
                      <th scope="row">created_at</th>
                      <td>date time</td>
                      <td>Created Time</td>
                    </tr>
                    <tr>
                      <th scope="row">updated_at</th>
                      <td>date time</td>
                      <td>Updated Time</td>
                    </tr>
                </tbody>
                </table>
                <div class="accordion" id="accordionExample">
                  <div class="card mb-2">
                    <div class="card-header p-1" id="switchExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="icon-question text-primary mr-1"></i>
                          Switch Card Extra Return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="switchExtra" data-parent="#accordionExample" style="">
                      <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">switch_value</th>
                                <td>boolean</td>
                                <td>True/False</td>
                              </tr>
                              <tr>
                                <th scope="row">label1</th>
                                <td>string</td>
                                <td>Switch Label 1</td>
                              </tr>
                              <tr>
                                <th scope="row">label2</th>
                                <td>string</td>
                                <td>Switch Label 2</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="NumberExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="icon-question text-primary mr-1"></i>
                          Number Card Extra Return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="NumberExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">run_value</th>
                                <td>string</td>
                                <td>card showing value</td>
                              </tr>
                              <tr>
                                <th scope="row">value</th>
                                <td>integer</td>
                                <td>value</td>
                              </tr>
                              <tr>
                                <th scope="row">format</th>
                                <td>string</td>
                                <td>format</td>
                              </tr>
                              <tr>
                                <th scope="row">decimals</th>
                                <td>integer</td>
                                <td>decimals</td>
                              </tr>
                              <tr>
                                <th scope="row">currency_sign</th>
                                <td>string</td>
                                <td>currency_sign</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
    
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="icon-question text-primary mr-1"></i>
                          Slider Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">min_value</th>
                                <td>integer</td>
                                <td>Minimum value</td>
                              </tr>
                              <tr>
                                <th scope="row">max_value</th>
                                <td>integer</td>
                                <td>Maximum value</td>
                              </tr>
                              <tr>
                                <th scope="row">slider_type</th>
                                <td>string</td>
                                <td>Slider Type</td>
                              </tr>
                              <tr>
                                <th scope="row">slider_value</th>
                                <td>integer</td>
                                <td>Slider Selected value</td>
                              </tr>
                              <tr>
                                <th scope="row">currency_sign</th>
                                <td>string</td>
                                <td>currency_sign</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="icon-question text-primary mr-1"></i>
                          Todo Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">todos</th>
                                <td>array</td>
                                <td>It will be return todo list array</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"><i class="icon-question text-primary mr-1"></i>
                          Timer Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">days</th>
                                <td>string</td>
                                <td>days</td>
                              </tr>
                              <tr>
                                <th scope="row">hours</th>
                                <td>string</td>
                                <td>hours</td>
                              </tr>
                              <tr>
                                <th scope="row">minutes</th>
                                <td>string</td>
                                <td>minutes</td>
                              </tr>
                              <tr>
                                <th scope="row">seconds</th>
                                <td>string</td>
                                <td>seconds</td>
                              </tr>
                              <tr>
                                <th scope="row">new_days</th>
                                <td>string</td>
                                <td>Running days</td>
                              </tr>
                              <tr>
                                <th scope="row">new_hours</th>
                                <td>string</td>
                                <td>Running hours</td>
                              </tr>
                              <tr>
                                <th scope="row">new_minutes</th>
                                <td>string</td>
                                <td>Running minutes</td>
                              </tr>
                              <tr>
                                <th scope="row">new_seconds</th>
                                <td>string</td>
                                <td>Running seconds</td>
                              </tr>
                              <tr>
                                <th scope="row">start_time</th>
                                <td>date-time</td>
                                <td>Start time (UTC+0)</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix"><i class="icon-question text-primary mr-1"></i>
                          Stopwatch Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">diffTime</th>
                                <td>integer</td>
                                <td>Different between current time and start time </td>
                              </tr>
                              <tr>
                                <th scope="row">start_time</th>
                                <td>date-time</td>
                                <td>Start time</td>
                              </tr>
                              <tr>
                                <th scope="row">isRunning</th>
                                <td>boolean</td>
                                <td>True/False</td>
                              </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"><i class="icon-question text-primary mr-1"></i>
                          Contacts Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">contacts</th>
                                <td>array</td>
                                <td>Contact list array</td>
                              </tr>
                              <tr>
                                <th scope="row">contacts_runtime</th>
                                <td>text</td>
                                <td>Runtime text</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight"><i class="icon-question text-primary mr-1"></i>
                          Anniversaries Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseEight" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">anniversaries</th>
                                <td>array</td>
                                <td>Anniversaries list array</td>
                              </tr>
                              <tr>
                                <th scope="row">anniversaries_runtime</th>
                                <td>text</td>
                                <td>Anniversaries runtime text</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine"><i class="icon-question text-primary mr-1"></i>
                          Scores Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseNine" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">home_score</th>
                                <td>integer</td>
                                <td>Home Score</td>
                              </tr>
                              <tr>
                                <th scope="row">away_score</th>
                                <td>integer</td>
                                <td>Away Score</td>
                              </tr>
                              <tr>
                                <th scope="row">increament</th>
                                <td>integer</td>
                                <td>Increament</td>
                              </tr>
                              <tr>
                                <th scope="row">isFinished</th>
                                <td>boolean</td>
                                <td>True/False</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-header p-1" id="sliderExtra">
                      <h5 class="mb-0 text-black">
                        <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen"><i class="icon-question text-primary mr-1"></i>
                          Calendar Card Extra return
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTen" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                      <div class="card-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Attribute')</th>
                                <th scope="col">@lang('Type')</th>
                                <th scope="col">@lang('Description')</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <th scope="row">calendars</th>
                                <td>array</td>
                                <td>Calendar list array</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>


        {{-- get single card   --}}
        <div class="card mt-3" id="getSingleCardTypeApi">
          <div class="card-header">
            @lang('Get Single Card')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">GET {{url('/')}}/api/single-card/{card_id}?api_key={api_key}</code>
            <br><br><h6>URL Parameters</h6>  
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Parameter')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">api_key <br><i class="text-muted">required</i></th>
                  <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                </tr>
                <tr>
                  <th scope="row">card id <br><i class="text-muted">required</i></th>
                  <td>Replace Card ID here {card_id}</td>
                </tr>
              </tbody>
            </table>
            <br><h6>Returns</h6>
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Attribute')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="3">It will be return same perameter as get all cards.</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>

        {{-- create card   --}}
        <div class="card mt-3" id="createCardApi">
          <div class="card-header">
            @lang('Create New Card')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/card/store?api_key={api_key}</code>
            <br><br><h6>Form Data</h6>  
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Key')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">api_key <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                </tr>
                <tr>
                  <th scope="row">card_type<br><i class="text-muted">required</i></th>
                  <th scope="row">integer</th>
                  <td>Card type ID</td>
                </tr>
                <tr>
                  <th scope="row">card_name<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Card name</td>
                </tr>
                <tr>
                  <th scope="row">description<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Card Details</td>
                </tr>
                <tr>
                  <th scope="row">is_public<br><i class="text-muted">required</i></th>
                  <th scope="row">boolean</th>
                  <td>true/false</td>
                </tr>
              </tbody>
            </table>

            
              <div class="accordion" id="accordionExample2">
                <div class="card mb-2">
                  <div class="card-header p-1" id="switchExtraCreate">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseOneCreate" aria-expanded="false" aria-controls="collapseOneCreate"><i class="icon-question text-primary mr-1"></i>
                        Switch Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseOneCreate" class="collapse" aria-labelledby="switchExtraCreate" data-parent="#accordionExample" style="">
                    <div class="card-body">
                      <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Key')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">label1<br><i class="text-muted">required</i></th>
                              <th scope="row">string</th>
                              <td>Label One Text</td>
                            </tr>
                            <tr>
                              <th scope="row">label2<br><i class="text-muted">required</i></th>
                              <th scope="row">string</th>
                              <td>Label Two Text</td>
                            </tr>
                            <tr>
                              <th scope="row">switch_value<br><i class="text-muted">required</i></th>
                              <th scope="row">boolean</th>
                              <td>true/false</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="NumberExtraCreate">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseTwoCreate" aria-expanded="false" aria-controls="collapseTwoCreate"><i class="icon-question text-primary mr-1"></i>
                        Number Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTwoCreate" class="collapse" aria-labelledby="NumberExtraCreate" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Key')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">value<br><i class="text-muted">required</i></th>
                              <td>integer</td>
                              <td>value</td>
                            </tr>
                            <tr>
                              <th scope="row">format<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>Here have 3 number format..select any one (number/currency/percentage)</td>
                            </tr>
                            <tr>
                              <th scope="row">decimals<br><i class="text-muted">required</i></th>
                              <td>integer</td>
                              <td>decimals</td>
                            </tr>
                            <tr>
                              <th scope="row">currency_sign<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>Sign (like:$)</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
  
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="icon-question text-primary mr-1"></i>
                        Slider Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseThree" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('key')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">min_value<br><i class="text-muted">required</i></th>
                              <td>integer</td>
                              <td>Minimum value</td>
                            </tr>
                            <tr>
                              <th scope="row">max_value<br><i class="text-muted">required</i></th>
                              <td>integer</td>
                              <td>Maximum value</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="icon-question text-primary mr-1"></i>
                        Todo Card Form Dataq Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseFour" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Key')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">todos<br><i class="text-muted">required</i></th>
                              <td>json array</td>
                              <td>json Example : <code>[{ "todo_item": "item", "isComplete": false }]</code></td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="timerExtraCreate">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFiveCreate" aria-expanded="false" aria-controls="collapseFiveCreate"><i class="icon-question text-primary mr-1"></i>
                        Timer Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseFiveCreate" class="collapse" aria-labelledby="timerExtraCreate" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">days<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>days</td>
                            </tr>
                            <tr>
                              <th scope="row">hours<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>hours</td>
                            </tr>
                            <tr>
                              <th scope="row">minutes<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>minutes</td>
                            </tr>
                            <tr>
                              <th scope="row">seconds<br><i class="text-muted">required</i></th>
                              <td>string</td>
                              <td>seconds</td>
                            </tr>
                           
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="stopwatchExtraCreate">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSixCreate" aria-expanded="false" aria-controls="collapseSixCreate"><i class="icon-question text-primary mr-1"></i>
                        Stopwatch Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseSixCreate" class="collapse" aria-labelledby="stopwatchExtraCreate" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row" colspan="3">No extra Data Need</th>
                            </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"><i class="icon-question text-primary mr-1"></i>
                        Contacts Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseSeven" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">contacts</th>
                              <td>json array</td>
                              <td>Example : <code>[{"first_name": "First Name","last_name" : "last Name","company": "","function": "", "address": "","zip": "","city": "","state": "","country": "","telephone": "","mobile": "","email": "","photo": "" }]</code>
                              <br>Note: Photo must be base64 string format</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight"><i class="icon-question text-primary mr-1"></i>
                        Anniversaries Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseEight" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">anniversaries</th>
                              <td>json array</td>
                              <td>Example : <code>[{"first_name":"First Name","last_name":"last Name","birth_date":"2020-09-23","photo":""}]</code>
                                <br>Note: Photo must be base64 string format</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine"><i class="icon-question text-primary mr-1"></i>
                        Scores Card Form Data Extra
                      </button>
                    </h5>
                  </div>
                  <div id="collapseNine" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">home_score</th>
                              <td>integer</td>
                              <td>Home Score</td>
                            </tr>
                            <tr>
                              <th scope="row">away_score</th>
                              <td>integer</td>
                              <td>Away Score</td>
                            </tr>
                            <tr>
                              <th scope="row">increament</th>
                              <td>integer</td>
                              <td>Increament</td>
                            </tr>
                            <tr>
                              <th scope="row">isFinished</th>
                              <td>boolean</td>
                              <td>True/False</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card mb-2">
                  <div class="card-header p-1" id="sliderExtra">
                    <h5 class="mb-0 text-black">
                      <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen"><i class="icon-question text-primary mr-1"></i>
                        Calendar Card Extra return
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTen" class="collapse" aria-labelledby="sliderExtra" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">@lang('Attribute')</th>
                              <th scope="col">@lang('Type')</th>
                              <th scope="col">@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <th scope="row">calendars</th>
                              <td>json array</td>
                              <td>Example: <code>[{"title":"Calender Title","from_date_time":"2020-09-10T04:41:00.000Z","to_date_time":"2020-09-30T04:41:00.000Z","location":"dhaka","category":"cat1,cat2"}]</code></td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>


        {{-- edit card   --}}
        <div class="card mt-3" id="editCardApi">
          <div class="card-header">
            @lang('Edit/Update Card')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">POST {{url('/')}}/api/card/store?api_key={api_key}</code>
            <br><br><h6>Form Data</h6>  
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Key')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">api_key <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                </tr>
                <tr>
                  <th scope="row">card_id<br><i class="text-muted">required</i></th>
                  <th scope="row">integer</th>
                  <td>Edited Card ID</td>
                </tr>
                <tr>
                  <th scope="row">card_type<br><i class="text-muted">required</i></th>
                  <th scope="row">integer</th>
                  <td>Card type ID</td>
                </tr>
                <tr>
                  <th scope="row">card_name<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Card name</td>
                </tr>
                <tr>
                  <th scope="row">description<br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Card Details</td>
                </tr>
                <tr>
                  <th scope="row">is_public<br><i class="text-muted">required</i></th>
                  <th scope="row">boolean</th>
                  <td>true/false</td>
                </tr>
                <tr>
                  <th scope="row" colspan="3"><code>All others extra perameters same as create card.</code></th>
                </tr>
              </tbody>
            </table>
          </div>
      </div>


       {{-- delete card   --}}
       <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Delete Card')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">DELETE {{url('/')}}/api/card/delete/{card_id}?api_key={api_key}</code>
            <br><br><h6>Form Data</h6>  
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">@lang('Perameter')</th>
                  <th scope="col">@lang('Type')</th>
                  <th scope="col">@lang('Description')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">api_key <br><i class="text-muted">required</i></th>
                  <th scope="row">string</th>
                  <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                </tr>
                <tr>
                  <th scope="row">card_id<br><i class="text-muted">required</i></th>
                  <th scope="row">integer</th>
                  <td>Card ID. replace {card_id} as your card id</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>

       {{-- get followers  --}}
       <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Get Followers')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">GET {{url('/')}}/api/get/followers?api_key={api_key}</code>
              <br><br><h6>URL Parameters</h6>  
              <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th scope="col">@lang('Parameter')</th>
                     <th scope="col">@lang('Description')</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <th scope="row">api_key <br><i class="text-muted">required</i></th>
                     <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                   </tr>
                 </tbody>
               </table>
               <br><h6>Returns</h6>
              <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th scope="col">@lang('Attribute')</th>
                     <th scope="col">@lang('Type')</th>
                     <th scope="col">@lang('Description')</th>
                   </tr>
                 </thead>
                 <tbody>
                  <tr>
                    <th scope="row">id</th>
                    <td>integer</td>
                    <td>User ID</td>
                  </tr>
                  <tr>
                    <th scope="row">username</th>
                    <td>string</td>
                    <td>Username</td>
                  </tr>
                  <tr>
                    <th scope="row">name</th>
                    <td>string</td>
                    <td>name</td>
                  </tr>
                  <tr>
                    <th scope="row">image</th>
                    <td>url</td>
                    <td>image</td>
                  </tr>
                  <tr>
                    <th scope="row">created_at</th>
                    <td>date time</td>
                    <td>created_at</td>
                  </tr>
                 </tbody>
               </table>
          </div>
      </div>

       {{-- get following cards  --}}
       <div class="card mt-3" id="deleteCardApi">
          <div class="card-header">
            @lang('Get Following Cards')
          </div>
          <div class="card-body">
              <h6>HTTP Request</h6>
              <code style="font-size:13px;font-weight:700;b">GET {{url('/')}}/api/get/following-cards?per_page=10&api_key={api_key}</code>
              <br><br><h6>URL Parameters</h6>  
              <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th scope="col">@lang('Parameter')</th>
                     <th scope="col">@lang('Description')</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <th scope="row">api_key <br><i class="text-muted">required</i></th>
                     <td>Your Api Secret Key.Without api secret key you can't access api.</td>
                   </tr>
                 </tbody>
               </table>
               <br><h6>Returns</h6>
              <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th scope="col">@lang('Attribute')</th>
                     <th scope="col">@lang('Type')</th>
                     <th scope="col">@lang('Description')</th>
                   </tr>
                 </thead>
                 <tbody>
                  <tr>
                    <th colspan="3">Return data like all cards data</th>
                  </tr>
                 </tbody>
               </table>
          </div>
      </div>




    </div>
	</div>
</div>
<script>
  function myFunction() {
    var copyText = document.getElementById("photo-link");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
      document.execCommand("copy");
    }
  </script>
@endsection