
<!doctype html>
<html lang="en">
  <head>
    <style type="text/css">
        @font-face {
        font-family: 'Neutra Text';
        src: url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); /* IE9 Compat Modes */
        }

        </style>
  </head>
  <body style="background: whitesmoke;font-family: 'Roboto', sans-serif;">
    <div class="container my-3">
      <div class="row">
        <div class="col-md-12">
          <div class="m-auto" style="padding: 100px;
          background: whitesmoke;">
                <div class="email-header" style="background-color:#503a85;text-align:center;border-radius: 10px 10px 0px 0px;">
                      <img style="width: 150px;
                      padding: 20px;" src="https://www.maysanexpress.sa/uploads/logo/1595916497logo-1-en.png" alt="">
                </div>
                <div class="email-body px-3 py-4" style="background: white;
                padding: 30px 20px;">
                    <strong>Hello {{ $name }},</strong><br>
                    <p>You are {{ $role }} in {{App\Models\Settings::getOption('site_name')}} . Your Account Credential Below:<p>
                    <strong>Email: </strong> {{$email}} <br> 
                    <strong>Password: </strong> {{$password}}<br> <br> 
                    
                      <p class="py-4 text-center font-ws" style="font-size: 13px;">Never share your credential information with others. Please change password after login.</p>

                      <div class="text-center py-5" style="text-align: center;margin-top: 30px;">
                            <a href="{{url('admin/login')}}" class="btn btn-lg font-ws" style="background-color: #503a85;
                            color: #ffffff;
                            border-radius: 50px;
                            padding: 10px 40px;
                            font-weight: 700;
                            text-decoration: none;" type="button" name="button">Sign In</a>
                      </div>

                </div>
                <div class="email-footer text-center py-3" style="background-color:#503a85;text-align:center;border-radius: 0px 0px 10px 10px ;">
                    <img style="width: 150px;
                    padding: 20px;" src="https://www.maysanexpress.sa/uploads/logo/1595916497logo-1-en.png" alt="">
                  <p class="text-light font-ws" style="font-size:14px;    color: #fff;">Fast Shipping - Competitive Price - Parcel Safety</p>
                  <p class="text-light font-ws" style="color: #fff;">All rights reserved to Maysan Express platform 2020</p>
                  <div class="links" style="    padding: 20px;">
                    <a class="px-2 text-light font-ws" style="text-decoration:none;font-size: 12px;    color: #fff;" href="{{url('/contact')}}">Contact Us - </a>
                    <a class="px-2 text-light font-ws" style="text-decoration:none;font-size: 12px;    color: #fff;" href="{{url('/terms')}}">T&C - </a>
                    <a class="px-2 text-light font-ws" style="text-decoration:none;font-size: 12px;    color: #fff;" href="{{url('/privacy_policy')}}">Policy - </a>
                    <a class="px-2 text-light font-ws" style="text-decoration:none;font-size: 12px;    color: #fff;" href="{{url('/faq')}}">FAQs</a>
                  </div>


                </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>

