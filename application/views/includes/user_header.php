
<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php //$this->output->enable_profiler(TRUE);?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="18x18" href="<?php echo base_url()?>images/EYESMART_230x50.png">
    <title><?php echo  isset($data['title']) ? $data['title'] : ''  ?></title>
    <!-- <link rel="canonical" href="https://www.wrappixel.com/templates/xtremeadmin/" /> -->
    <link href="<?php echo base_url();?>assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>dist/js/pages/chartist/chartist-init.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/libs/c3/c3.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/extra-libs/css-chart/css-chart.css" rel="stylesheet">
    <!-- Vector CSS -->
    <link href="<?php echo base_url();?>assets/libs/jvectormap/jquery-jvectormap.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>dist/css/style.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style type="text/css">
        .radiobtn{
            margin-top: 17px;
        }
        .datepicker
        {
          z-index: 100 !important;
        }

        .tooltip-inner {
    max-width: 200px;
    padding: .25rem .5rem;
    color: #fff;
    text-align: center;
    background-color: #15161991;
    border-radius: 4px;


}

    .tooltip-inner {
    text-align: left;
}

#autosearch_id{float:left;list-style:none;margin-top:0px;padding:0;width:415px;position: absolute;margin-left: -1px;z-index:1;}
#autosearch_id li{padding: 7px; background: #000000;color:#FFF; border-bottom: #bbb9b9 1px solid;}
#autosearch_id li:hover{background:#438EB9;color:#FFF;cursor: pointer;}



		input[type="password"]{
			-webkit-text-security:disc;
		}
        


        
    </style>

<style type="text/css">
    #cover-spin {
    position:fixed;
    width:100%;
    left:0;right:0;top:0;bottom:0;
    /*background-color: rgba(255,255,255,0.7);*/

   background-color: rgb(0 0 0 / 40%);
   
    z-index:9999;
    display:none;
}

@-webkit-keyframes spin {
    from {-webkit-transform:rotate(0deg);}
    to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
    from {transform:rotate(0deg);}
    to {transform:rotate(360deg);}
}

#cover-spin::after {
    content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:100px;height:100px;
    border-style:solid;
    border-color:#a6da07;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}
</style>
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
   
<!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo base_url();?>assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url();?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?php echo base_url();?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="<?php echo base_url();?>dist/js/app.min.js"></script>
   <script type="text/javascript">
     $(function () {
  $('[data-toggle="tooltip"]').tooltip({html:true});

  
})


       $(function() {

    "use strict";
    $("#main-wrapper").AdminSettings({
        Theme: false, // this can be true or false ( true means dark and false means light ),
        Layout: 'horizontal',
        LogoBg: 'skin1', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6 
        NavbarBg: 'skin1', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
        SidebarType: 'full', // You can change it full / mini-sidebar / iconbar / overlay
        SidebarColor: 'skin6', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
        SidebarPosition: true, // it can be true / false ( true means Fixed and false means absolute )
        HeaderPosition: true, // it can be true / false ( true means Fixed and false means absolute )
        BoxedLayout: true, // it can be true / false ( true means Boxed and false means Fluid ) 
    });
});
   </script>
    <!-- <script src="<?php echo base_url();?>dist/js/app.init.horizontal.js"></script> -->
    <script src="<?php echo base_url();?>dist/js/app-style-switcher.horizontal.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url();?>assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url();?>dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url();?>dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url();?>dist/js/custom.min.js"></script>
    <!--This page JavaScript -->


    <script src="<?php echo base_url();?>dist/js/pages/forms/jasny-bootstrap.js"></script>


    <script >

        $(document).ready(function()
        {
         $('form').attr('autocomplete', 'off');
        });

        $(function(){
       // $.ajaxSetup({data: {digipayemts_csrf_code: "<?php echo $this->security->get_csrf_hash();?>"}});
        });

    </script>

    <script src="<?php echo base_url();?>assets/digi/digi_validations.js?v1=<?php echo rand(); ?>"></script>

    <style>
       /* .navbar-brand{
            background-color: #3308ab;
        }
        #main-wrapper[data-layout="horizontal"] .topbar .navbar-collapse[data-navbarbg="skin1"], #main-wrapper[data-layout="horizontal"] .topbar[data-navbarbg="skin1"], #main-wrapper[data-layout="vertical"] .topbar .navbar-collapse[data-navbarbg="skin1"], #main-wrapper[data-layout="vertical"] .topbar[data-navbarbg="skin1"] {
            background: #3308ab;
        }

        #main-wrapper[data-layout="horizontal"] .left-sidebar[data-sidebarbg="skin6"] .sidebar-nav ul .sidebar-item.selected > .sidebar-link, #main-wrapper[data-layout="vertical"] .left-sidebar[data-sidebarbg="skin6"] .sidebar-nav ul .sidebar-item.selected > .sidebar-link {
            color: #fff;
            background: #3308ab;
        }

        .btn-info {
            color: #fff;
            background-color: #3308ab;
            border-color: #3308ab;
        }

        .bg-info {
            background-color: #3308ab !important;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3308ab;
            border-color: #3308ab;
        }*/
    </style>

</head>

<body>
        <?php

        $imagehtml =base_url().'images/default_pic.png';


        
        ?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-lg navbar-dark">
                <div class="navbar-header">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-lg-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="<?php echo base_url().'user_dashboard/dashboard'; ?>">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo base_url().'images/logo.jpg'?>" alt="Dashboard" class="dark-logo"  width="50px">
                          
                            <!-- Light Logo icon -->
                             <img src="<?php echo base_url().'images/logo.jpg'?>" alt="Dashboard" class="light-logo"  width="50px">
                          
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text" >
                                
                            
                               TGTDA
                            <!-- dark Logo text -->
                               <!-- <img src="<?php echo base_url().'images/logo.jpg'?>" alt="Dashboard" class="dark-logo"  width="150px"> -->
                            <!-- Light Logo text -->
                               <!-- <img src="<?php echo base_url().'images/logo.png'?>" alt="Dashboard" class="light-logo"  width="200px"> -->
                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-lg-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" style="margin-left: 102px;" >
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                      <!--   <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li> -->
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                   <!--      <li class="nav-item d-none d-md-block search-box"> <a
                                class="nav-link d-none d-md-block waves-effect waves-dark" href="javascript:void(0)"><i
                                    class="ti-search"></i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search & enter"> 
                                <a class="srh-btn"><i class="ti-close"></i></a> 
                            </form>
                        </li> -->
                        <!-- ============================================================== -->
                        <!-- Mega Menu -->
                        <!-- ============================================================== -->
                     
                        <!-- ============================================================== -->
                        <!-- End Mega Menu -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <img class="" src="<?php echo base_url()?>images/logo.jpg" width="114px" height="63px" alt="tgtda" /></a>
                            <a></a>
                        </li>
                        <li class="nav-item dropdown">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                        <li class="nav-item dropdown ">
                           
                            <a class="nav-link dropdown-toggle waves-effect waves-dark " href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                <img src="<?php echo $imagehtml; ?>" alt="user" width="30" class="profile-pic rounded-circle" />
                                 <p style="margin-top: -47px;margin-left: -24px;font-size: 13px;"><?php echo isset($_SESSION['uuser_name']) ? ucwords(strtolower($_SESSION['uuser_name'])) :'';  ?>
                                   <?php //echo date('d-m-Y h:i s A'); ?>
                                 </p>
                                
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right scale-up">
                                <ul class="dropdown-user list-style-none">
                                    <li>
                                        <div class="dw-user-box p-3 d-flex">
                                            <div class="u-img"><img src="<?php echo $imagehtml; ?>" alt="user" class="rounded" width="80"></div>
                                            <div class="u-text ml-2">
                                                <h4 class="mb-0"><?php echo isset($_SESSION['uuser_name']) ? ucwords(strtolower($_SESSION['uuser_name'])) :'';  ?></h4>
                                                <p class="text-muted mb-1 font-14"><?php echo isset($_SESSION['uuser_mobile']) ? strtolower($_SESSION['uuser_mobile']) :'';  ?></p>
                                                <a href="<?php echo base_url().'client_profile'; ?>" class="btn btn-rounded btn-danger btn-sm text-white d-inline-block">View
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="dropdown-divider"></li>
                                    <li class="user-list"><a class="px-3 py-2" href="<?php echo base_url().'' ?>login/Userlogout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Language -->
                        <!-- ============================================================== -->
                       
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
       
      
