<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Neon is a bootstrap, laravel & php admin dashboard template">
    <meta name="keywords" content="admin, admin dashboard, admin panel, admin template, admin theme, bootstrap 4, laravel, php, crm, analytics, responsive, sass support, ui kits, web app, clean design">
    <meta name="author" content="Themesbox17">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>Numberish | Admin Reset Password</title>

    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{asset('/')}}assets/images/logo.png">

    <!-- Start CSS -->
    <link href="{{asset('/')}}assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}assets/css/style.css" rel="stylesheet" type="text/css">
    <!-- End CSS -->
    <script src="{{asset('/')}}assets/js/jquery.min.js"></script>
</head>

<body class="xp-vertical">

    <div class="xp-authenticate-bg"></div>
    <!-- Start XP Container -->
    <div id="xp-container" class="xp-container">

        <!-- Start Container -->
        <div class="container">

            <!-- Start XP Row -->
            <div class="row vh-100 align-items-center">
                <!-- Start XP Col -->
                <div class="col-lg-12 ">

                    <!-- Start XP Auth Box -->
                    <div class="xp-auth-box">

                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center mt-0 m-b-15">
                                    <a href="{{route('admin/login')}}" class="xp-web-logo"><img src="{{asset('/')}}assets/images/logo.png" height="40" alt="logo"></a>
                                </h3>
                                <div class="p-3">
                                    <form action="{{route('admin/reset-password')}}" method="post">
                                        @csrf
                                        <div class="text-center mb-3">
                                            <h4 class="text-black">Reset Password !</h4>
                                        </div>
                                        @if(Session::get('success'))
                                            <div class="alert alert-success">
                                                {{Session::get('success')}}
                                            </div>
                                        @endif
                                        @if(Session::get('error'))
                                            <div class="alert alert-danger">
                                                {{Session::get('error')}}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" >
                                            @error('email')
                                                <div style="color: #ef0d0d;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group text-right">
                                            <label class="forgot-psw"> 
                                                <a id="forgot-psw" href="{{route('admin/login')}}">Sign In</a>
                                            </label>
                                        </div>                         
                                      <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End XP Auth Box -->

                </div>
                <!-- End XP Col -->
            </div>
            <!-- End XP Row -->
        </div>
        <!-- End Container -->
    </div>
    <!-- End XP Container -->

    <!-- Start JS -->        
    
    <script src="{{asset('/')}}assets/js/popper.min.js"></script>
    <script src="{{asset('/')}}assets/js/bootstrap.min.js"></script>

</body>
</html>