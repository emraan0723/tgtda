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

               <!-- <li class="sidebar-item">
                    <a class="sidebar-link <?php /*echo ($this->router->fetch_class() == 'dashboard') ? 'active open' : '' */?>  waves-effect waves-dark" href="<?php /*echo base_url().'dashboard'; */?>" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>


                </li>-->

                <?php
                if (isset($_SESSION['userprivileges']['admin']) && $_SESSION['userprivileges']['admin']['permissionset'] > 0)
                {
                    ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Admin </span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <?php if (isset($_SESSION['userprivileges']['admin']['adding']) && $_SESSION['userprivileges']['admin']['adding'] > 0)
                            {
                                ?>
                              <!--  <li class="sidebar-item">
                                    <a href="<?php /*echo base_url().'admin/create'; */?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">Add Admin</span>
                                    </a>
                                </li>-->
                                <?php
                            }
                            ?>
                            <?php if (isset($_SESSION['userprivileges']['admin']['view']) && $_SESSION['userprivileges']['admin']['view'] > 0)
                            {
                                ?>
                              <!--  <li class="sidebar-item">
                                    <a href="<?php /*echo base_url().'admin/view'; */?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">View Admin</span>
                                    </a>
                                </li>-->


                                <?php
                            }
                            ?>
                        </ul>

                    </li>
                    <?php
                }
                ?>


                <?php
                if (isset($_SESSION['userprivileges']['user']) && $_SESSION['userprivileges']['user']['permissionset'] > 0)
                {
                    ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Users </span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <?php if (isset($_SESSION['userprivileges']['user']['adding']) && $_SESSION['userprivileges']['user']['adding'] > 0)
                            {
                                ?>
                                <li class="sidebar-item">
                                    <a href="<?php echo base_url().'user/create'; ?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">User Registration Process</span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            <?php if (isset($_SESSION['userprivileges']['user']['view']) && $_SESSION['userprivileges']['user']['view'] > 0)
                            {
                                ?>
                              <!--  <li class="sidebar-item">
                                    <a href="<?php /*echo base_url().'admin/view'; */?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">View Admin</span>
                                    </a>
                                </li>-->


                                <?php
                            }
                            ?>
                        </ul>

                    </li>
                    <?php
                }
                ?>




                <?php
                if (isset($_SESSION['userprivileges']['masters']) && $_SESSION['userprivileges']['masters']['permissionset'] > 0)
                {
                    ?>
                    <li class="sidebar-item">
                    <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fab fa-maxcdn"></i><span class="hide-menu">Masters </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                    <?php if (isset($_SESSION['userprivileges']['masters']['permissionset']) && $_SESSION['userprivileges']['masters']['permissionset'] > 0)
                {
                    ?>
                    <li class="sidebar-item">
                        <a href="<?php echo base_url().'country'; ?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">Country</span> </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?php echo base_url().'state'; ?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">State</span> </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="<?php echo base_url().'district'; ?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">District</span> </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="<?php echo base_url().'mandal'; ?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">Mandal</span> </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?php echo base_url().'activemember'; ?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">Active Member Maping</span> </a>
                    </li>

                   <!-- <li class="sidebar-item">
                        <a href="<?php /*echo base_url().'currency'; */?>" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">Currency</span> </a>
                    </li>--><!--   <li class="sidebar-item">
                                    <a href="<?php echo base_url().'tax'; ?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">Tax</span>
                                    </a>
                                </li> --></ul></li>
                    <?php
                }
                    ?>


                    <?php
                }
                ?>


                <?php
                if (isset($_SESSION['userprivileges']['settings']) && $_SESSION['userprivileges']['settings']['permissionset'] > 0)
                {
                    ?>
                    <!--<li class="sidebar-item">
                        <a class="sidebar-link  waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu">Settings </span></a>
                        <ul aria-expanded="false" class="collapse  first-level">-->


                            <?php if (isset($_SESSION['userprivileges']['userprivileges']['permissionset']) && $_SESSION['userprivileges']['userprivileges']['permissionset'] > 0)
                            {
                                ?>
                              <!--  <li class="sidebar-item">
                                    <a href="<?php /*echo base_url().'settings/privileges'; */?>" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu">User Privileges</span>
                                    </a>
                                </li>-->
                                <?php
                            }
                            ?>

                        </ul>
                    </li>
                    <?php
                }
                ?>

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

        <?php
        if ($this->router->fetch_class() == 'dashboardsss')
        {
            ?>
            <form class="form-material" id="frm_datefilter" method="post">
                <div class="row">
                    <!-- Column -->

                    <div class="col-md-6 col-lg-5" style="margin-left: -21px;">


                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" readonly="readonly" class="disdatepicker form-control" placeholder="From Date" name="from_date_id" value="<?php echo isset($_POST['from_date_id']) && $_POST['from_date_id'] != '' ? $_POST['from_date_id'] : ''; ?>" id="from_date_id"/>

                            <div class="input-group-append">
                                <span class="input-group-text bg-info b-0 text-white">TO</span>
                            </div>
                            <input type="text" value="<?php echo isset($_POST['to_date_id']) && $_POST['to_date_id'] != '' ? $_POST['to_date_id'] : ''; ?>" placeholder="To Date" readonly="readonly" class="disdatepicker form-control" name="to_date_id" id="to_date_id"/>

                        </div>


                    </div>

                    <div class="col-md-6 col-lg-5">


                        <input type="hidden" name="hidd_currency_name" id="hidd_currency_name_id" value="">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                        <select class="form-control" id="currency_id" name="currency_id">

                            <?php
                            if (isset($currency_list) && count($currency_list) > 0)
                            {
                                foreach ($currency_list as $key => $value)
                                {
                                    ?>
                                    <option value="<?php echo isset($value['currency_id']) ? $value['currency_id'] : 0; ?>" <?php echo isset($_POST['currency_id']) && $_POST['currency_id'] == $value['currency_id'] ? 'selected="selected"' : ''; ?>><?php echo isset($value['currency_name']) ? ucwords($value['currency_name']).' ('.$value['currency_short_name'].')' : ''; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>


                    </div>
                    <button type="submit" class="btn btn-info waves-effect waves-light">submit</button>&nbsp;&nbsp;
                    <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button>


                </div>
            </form>
            <?php
        }
        ?>
    </div>





