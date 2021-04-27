@include('admin.layout.header')
    <!-- Start XP Container -->
    <div id="xp-container">

        <!-- Start XP Leftbar -->
        <div class="xp-leftbar">    
            <!-- Start XP Sidebar -->
            @include('admin.layout.sidebar')
            <!-- End XP Sidebar -->
        </div>
        <!-- End XP Leftbar -->

        <!-- Start XP Rightbar -->
        <div class="xp-rightbar">
            @include('admin.layout.top-bar')

            <!-- Start XP Contentbar -->    
            <!-- <div class="xp-contentbar"> -->
                <!-- Start XP Row -->
                @yield('body')
                <!-- End XP Row -->
            <!-- </div> -->
            <!-- End XP Contentbar -->

            <!-- Start XP Footerbar -->
            @include('admin.layout.footer')
            