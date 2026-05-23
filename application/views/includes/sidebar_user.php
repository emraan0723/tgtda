    <!-- ============================================================== --><!-- Left Sidebar - style you can find in sidebar.scss  --><!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <!-- User Profile-->
                    <li class="nav-small-cap">
                        <i class="mdi mdi-dots-horizontal"></i>
                        <span class="hide-menu"></span></li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo ($this->router->fetch_class() == 'dashboard') ? 'active open' : '' ?>  waves-effect waves-dark" href="<?php echo base_url().'user_dashboard'; ?>" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>

                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="<?php echo base_url().'map'; ?>" class="sidebar-link">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">Members Map</span> </a>
                            </li>
                        </ul>

                    </li>

                   <!-- <li class="sidebar-item">
                        <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fab fa-maxcdn"></i><span class="hide-menu">Settings </span></a>
                        <ul aria-expanded="false" class="collapse  first-level">


                            <li class="sidebar-item">
                                <a href="<?php /*echo base_url().'map'; */?>" class="sidebar-link">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">Map</span> </a>
                            </li>
                        </ul>
                    </li>-->
                    <!--<li class="sidebar-item">
                        <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu">Settings </span></a>
                        <ul aria-expanded="false" class="collapse  first-level">-->




                </ul>
                </li>


                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside><!-- ============================================================== --><!-- End Left Sidebar - style you can find in sidebar.scss  --><!-- ============================================================== --><!-- ============================================================== --><!-- Page wrapper  --><!-- ============================================================== -->
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-4 col-12 align-self-center">
                <!-- <h3 class="text-themecolor mb-0"><?php echo isset($data['header1']) ? $data['header1'] : '' ?></h3> -->
                <ol class="breadcrumb mb-0 p-0 bg-transparent">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)"><?php echo isset($data['header1']) ? $data['header1'] : '' ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo isset($data['header2']) ? $data['header2'] : '' ?></li>


                </ol>

            </div>
            <div class="alert  alert-dismissible bg-success text-white border-0 fade show col-md-7 col-12 align-self-center  alert_digi_msgs" role="alert" style="padding: 3px;display: none">
                <!--  <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button> -->

            </div>

        </div>





