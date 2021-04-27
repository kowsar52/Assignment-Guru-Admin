<div class="xp-sidebar">

  <!-- Start XP Logobar -->
  <div class="xp-logobar text-center">
      <a href="{{route('admin/dashboard')}}" class="xp-logo"><img src="{{asset('/')}}{{get_setting()['logo']}}" class="" style="width: 150px;" alt="logo"></a>
      
  </div>
  <!-- End XP Logobar -->

  <!-- Start XP Navigationbar -->
  <div class="xp-navigationbar">
      <ul class="xp-vertical-menu">
          <li>
              <a href="{{url('admin/dashboard')}}">
                <i class="icon-speedometer"></i><span>Dashboard</span>
              </a>
          </li>
          <li>
            <a href="javaScript:void();">
                <i class="icon-doc"></i><span>Order Management</span><i class="icon-arrow-right pull-right"></i>
            </a>
            <ul class="xp-vertical-submenu">
                <li><a href="{{url('admin/products')}}">Products</a></li>
                <li><a href="{{url('admin/services')}}">Services</a></li>
                <li><a href="{{url('admin/levels')}}">Education Levels</a></li>
                <li><a href="{{url('admin/deadlines')}}">Deadlines</a></li>
                <li><a href="{{url('admin/languages')}}">Languages</a></li>
            </ul>
        </li>
          <li>
              <a href="{{route('users')}}">
                <i class="fa fa-users"></i><span>Users</span>
              </a>
          </li>
          <li>
              <a href="{{route('admin/setting')}}">
                <i class="fa fa-gear"></i><span>Setting</span>
              </a>
          </li>
          <li>
              <a href="{{route('admin/email-template')}}">
                <i class="fa fa-envelope"></i><span>Email Templates</span>
              </a>
          </li>
          <li>
            <a href="javaScript:void();">
                <i class="icon-doc"></i><span>Templates</span><i class="icon-arrow-right pull-right"></i>
            </a>
            <ul class="xp-vertical-submenu">
                <li><a href="{{url('admin/template-translation')}}">Translations</a></li>
                <li><a href="{{url('admin/template-slug')}}">Slug</a></li>
            </ul>
          </li>
          <li>
            <a href="javaScript:void();">
                <i class="icon-doc"></i><span>Theme Management</span><i class="icon-arrow-right pull-right"></i>
            </a>
            <ul class="xp-vertical-submenu">
                <li><a href="{{url('admin/home/writing-service-features')}}">Writing Service Features</a></li>
                <li><a href="{{url('admin/home/frequently-asked-questions')}}">Frequently Asked Questions</a></li>
                <li><a href="{{url('admin/theme/order-page-contents')}}">Order Page Contents</a></li>
            </ul>
          </li>
          <li>
              <a href="{{url('admin/pages')}}">
                <i class="fa fa-gear"></i><span>Pages</span>
              </a>
          </li>
      </ul>
  </div>
  <!-- End XP Navigationbar -->

</div>