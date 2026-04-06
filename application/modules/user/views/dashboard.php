

<div class="container-fluid">

    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-body">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col pr-0 align-self-center">
                        <h2 class="font-weight-light mb-0"><?php echo isset($total_customer) ? $total_customer : 0; ?> </h2>
                        <h6 class="text-muted">Total Users</h6>
                    </div>
                    <!-- Column -->
                    <div class="col text-right align-self-center" style="padding-right:0px;">
                        <div class="css-bar mb-0 css-bar-info css-bar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card card-body">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col pr-0 align-self-center">
                        <h2 class="font-weight-light mb-0"><?php echo isset($total_active_customer) ? $total_active_customer : 0; ?> </h2>
                        <h6 class="text-muted">Total Active Users</h6>
                    </div>
                    <!-- Column -->
                    <div class="col text-right align-self-center" style="padding-right:0px;">
                        <div class="css-bar mb-0 css-bar-info css-bar"></div>
                    </div>
                </div>
            </div>
        </div>


</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->



<!-- chartist chart -->
<script src="<?php echo base_url();?>assets/libs/chartist/dist/chartist.min.js"></script>
<script src="<?php echo base_url();?>assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
<!--c3 JavaScript -->
<script src="<?php echo base_url();?>assets/libs/d3/dist/d3.min.js"></script>
<script src="<?php echo base_url();?>assets/libs/c3/c3.min.js"></script>
<!-- Vector map JavaScript -->
<script src="<?php echo base_url();?>assets/libs/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo base_url();?>assets/extra-libs/jvector/jquery-jvectormap-us-aea-en.js"></script>
<!-- Chart JS -->


<?php
$months = array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
        //$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December', );

$monthname = array();
$paid =array();
$due = array();

if(isset($getMonths) && count($getMonths) > 0)
{
    foreach ($getMonths as $datakey => $datavalue)
    {



       $monthname[] =$datakey;
       $paid[] =isset($datavalue['PAID']) && ['product_discount_amount'] > 0 ? $datavalue['PAID']['product_discount_amount'] : 0;

       $due[]= isset($datavalue['DUE']) && $datavalue['DUE']['product_discount_amount'] > 0 ? $datavalue['DUE']['product_discount_amount'] : 0;








   }
}

/*
          print"<pre>";
        print_r($due);
*/
        $month = implode("' , '", $monthname);
        $month = "'".strtoupper($month)."'";

        $paid = implode(',', $paid);
        $due = implode(',', $due);



        ?>

        <script type="text/javascript">
            $(function ()
            {
                "use strict";
            // ==============================================================
            // Sales overview
            // ==============================================================
            var chart2 = new Chartist.Bar('.amp-pxl', {
              labels: [<?php echo $month ?>],




              series: [
              [<?php echo $paid ?>],
              [<?php echo $due ?>]
              ]
          }, {
              axisX: {
                    // On the x-axis start means top and end means bottom
                    position: 'end',
                    showGrid: false
                },
                axisY: {
                    // On the y-axis start means left and end means right
                    position: 'start'
                },

                plugins: [
                Chartist.plugins.tooltip()
                ]
            });

        });
    </script>
    <script type="text/javascript">
       $(function()
       {
           var currency_id = "<?php echo isset($_POST['currency_id']) && $_POST['currency_id'] > 0  ? $_POST['currency_id'] : 45 ; ?>";
           $("#currency_id").val(currency_id);
           $("#currency_id").change(function()
           {
             var gettext =$("#currency_id option:selected" ).text();
             $("#hidd_currency_name_id").val(gettext);

             $("#frm_currency").submit();
         });

     </script>


     <script type="text/javascript">

       $.fn.datepicker.defaults.format = "dd-mm-yyyy";


       $(".disdatepicker").datepicker({
         startDate: "today" ,
         todayHighlight: true

     });
    // Date Picker
    jQuery('.mydatepicker, #datepicker, .input-group.date').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        //autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });
</script>
<script type="text/javascript">
 $(function()
 {
     var currency_id = "<?php echo isset($_POST['currency_id']) && $_POST['currency_id'] > 0  ? $_POST['currency_id'] : 45 ; ?>"
     $("#currency_id").val(currency_id);
       /* $("#currency_id").change(function()
        {
           var gettext =$("#currency_id option:selected" ).text();
           $("#hidd_currency_name_id").val(gettext);

            $("#frm_currency").submit();
        });*/
    });
</script>
<script src="<?php echo base_url();?>assets/libs/moment/moment.js"></script>
<script src="<?php echo base_url();?>assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>