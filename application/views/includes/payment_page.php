<?php

/*print"<pre>";
print_r($status);
exit;*/
?>
<!DOCTYPE html>
<html dir="ltr">

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
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="main-wrapper">
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
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->

        <?php
            $color ="#fff !important";
            if(isset($status['bank_status_code']) && $status['bank_status_code']=='000')
                $color ="#22ff14 !important";
            if(isset($status['bank_status_code']) && $status['bank_status_code']=='111')
                 $color ="#fc4b6c !important";
            if(isset($status['bank_status_code']) && $status['bank_status_code']=='101')
                 $color ="#ffb22b !important";

            if(isset($status['status_code']) && $status['status_code']=='000')
                $color ="#22ff14 !important";
            if(isset($status['bank_status']) && $status['status_code']=='111')
                 $color ="#fc4b6c !important";
            if(isset($status['status_code']) && $status['status_code']=='101')
                 $color ="#ffb22b !important";

            
       
         

        ?>
        <div class="error-box"  style="background-color: #fff; background-size: cover;">
             <input type="hidden" value="<?php echo base_url().'' ?>login/logout" id="logout_url_id">
            <div class="error-body text-center">
            <!--   <img src="<?php echo base_url().'images/logo.png'?>" alt="Dashboard" class="dark-logo"  width="50px"> -->
                <img class="" width="150px" src="<?php echo base_url().'images/logo.png'; ?>" alt="Eyesmart Logo" />
                <div class="mt-4">
                    <h1 style="color:<?php echo $color; ?>;font-weight: bold;size: 25px"><?php echo  isset($data['msg']) ? $data['msg'] : '' ?></h1>
                    <h3 class="mb-0 text-muted font-medium" style="color:<?php echo $color; ?>;font-weight: bold;size: 25px">Your Payment <?php echo $status['bank_status']; ?></h5><br/>
                    <h5 class="text-muted font-medium" style="font-weight: bold;size: 25px">Transaction Reference Number - <?php echo $status['transaction_key']; ?> </h5>
                    <h5 class="text-muted font-medium" style="font-weight: bold;size: 25px">Reference Number - <?php echo $status['cusomer_rid']; ?> </h5>
                    <?php 
                      if((isset($status['bank_status_code']) && $status['bank_status_code']=='111') || (isset($status['status_code']) && $status['status_code']=='111'))
                      {
                        ?>
                            <h5 class="text-muted font-medium" style="font-weight: bold;size: 25px">Please Try Again</h5>
                    <?php
                      }
                      ?>
                      <h5 class="text-muted font-medium" style="font-weight: bold;size: 25px">Thank You </h5>
                </div>
                
              <!--   <div class="mt-4">
                    <a href="javascript:void(0)" class="btn btn-facebook" data-toggle="tooltip" title="" data-original-title="Facebook"> <i aria-hidden="true" class="fab fa-facebook-f"></i> </a>
                    <a href="javascript:void(0)" class="btn btn-linkedin ml-1" data-toggle="tooltip" title="" data-original-title="Linkedin"> <i aria-hidden="true" class="fab fa-linkedin-in"></i> </a>
                    <a href="javascript:void(0)" class="btn btn-dark ml-1" data-toggle="tooltip" title="" data-original-title="Skype"> <i aria-hidden="true" class="fab fa-skype"></i> </a>
                    <a href="javascript:void(0)" class="btn btn-twitter ml-1" data-toggle="tooltip" title="" data-original-title="Twitter"> <i aria-hidden="true" class="fab fa-twitter"></i> </a>
                </div> -->
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="<?php echo base_url();?>assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url();?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?php echo base_url();?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
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
<!--     <script src="<?php echo base_url();?>assets/extra-libs/jquery-sessiontimeout/jquery.sessionTimeout.min.js"></script>
    <script src="<?php echo base_url();?>assets/extra-libs/jquery-sessiontimeout/session-timeout-init.js"></script> -->

    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    </script>

    <script >
        setTimeout(function() {

           window.close();
    }, 500);
    </script>
</body>

</html>