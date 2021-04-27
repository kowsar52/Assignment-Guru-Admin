<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Neon is a bootstrap, laravel & php admin dashboard template">
    <meta name="keywords" content="admin, admin dashboard, admin panel, admin template, admin theme, bootstrap 4, laravel, php, crm, analytics, responsive, sass support, ui kits, web app, clean design">
    <meta name="author" content="Themesbox17">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>{{App\Models\Settings::getOption('site_name')}} | User Dashboard</title>

    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{asset('/')}}{{get_setting()['favicon']}}">

    <!-- Start CSS -->
    <!-- Chartist Chart CSS -->
    <link rel="stylesheet" href="{{asset('/')}}assets/plugins/chartist-js/chartist.min.css">
    <style>
      .xp-breadcrumbbar {
        padding: 85px 30px 10px 30px !important ;
    }
    </style>
<style>
  .card {
    border-radius: 5px !important;
}
</style>
    <!-- Datepicker CSS -->
    <link href="{{asset('/')}}assets/plugins/datepicker/datepicker.min.css" rel="stylesheet" type="text/css">

    <link href="{{asset('/')}}assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}assets/css/datatable.min.css" rel="stylesheet" type="text/css">

       <!-- Sweet-Alert JS -->
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- End CSS -->
    <script src="{{asset('/')}}assets/js/jquery.min.js"></script>
</head>

<body class="xp-vertical">
    <!-- Search Modal -->
    <div class="modal search-modal fade" id="xpSearchModal" tabindex="-1" role="dialog" aria-labelledby="xpSearchModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="xp-searchbar">
                <form>
                    <div class="input-group">
                      <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                      <div class="input-group-append">
                        <button class="btn" type="submit" id="button-addon2">GO</button>
                      </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>