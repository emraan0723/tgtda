  <link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->

            <div class="container-fluid">

                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                           
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>

                           <form class="form-material mt-5 novalidate" method="post">
                               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                            <div class="row single_invoice">
                                                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Search Customer <span class="text-danger">*</span></label>
                                          <input type="hidden" value="" name="customer_id" id="customer_id">
                                          <input type="hidden" value="" name="customer_code" id="customer_code">
                                             <input type="text" required="required" placeholder="Please Type Customer Name"  name="customer_name" id="customer_name_id"  class="form-control form-control-line searchcustomer"> 
                                               
                                    </div>
                                    <div id="suggesstion-box"></div>
                                </div>

                                <!--  <div class="col-sm-12 col-lg-4">
                                   <div class="form-group">
                                        <label>Select Currency<span class="text-danger">*</span></label>
                                        <select class="form-control currencylist"  id="currency_id_1" name="currency_id">
                                             <option value=""></option>
                                              
                                            </select>
                                           <div class="valid-feedback" style="color: red">
                                      </div>     
                                      
                                    </div>
                                 </div>      -->
                                
                              
                              <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Service Start Date <span class="text-danger">*</span></label>
                                         <div class="input-group date">

                                            <input  required="required" name="service_start_date" type="text" maxlength="10"  id="service_start_date_id_1"  class="form-control form-control-line start_date">

                                                <span class="input-group-addon input-group-text"><i class="icon-calender"></i></span>
                                            </div>
                                         <div class="valid-feedback" style="color: red">
                                     </div>
                                     
                                      </div>
                                </div>


                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>AMC percentage<span class="text-danger">*</span></label>
                                        <select class="form-control" required="required" name="amc_pecentage"  id="amc_pecentage_id_1">
                                            <option value=""></option>
                                            <?php
                                                for($i=1; $i<=30; $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                }

                                            ?>
                                               
                                            </select> 
                                             <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>



                                  <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Monthly Subscription<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="WITH  OUT GST"><i class="fa fa-info-circle" style="font-size:12px;color:red"></i></span></span></label>
                                        <input  required="required" name="monthly_subscription" type="text" maxlength="10"  id="monthly_subscription_1"  class="form-control form-control-line num productamount">
                                          
                                      </div> 
                                     </div>


                                       <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Yealy Subscription<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="WITH  OUT GST"><i class="fa fa-info-circle" style="font-size:12px;color:red"></i></span></span></label>
                                        <input style="background-color: #ccc;padding: 10px;" type="text" maxlength="10"  id="yealy_subscription_1" readonly="readonly"   class="form-control form-control-line num productamount">
                                          
                                      </div> 
                                     </div>

                                     <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Total AMC Amount<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="WITH  OUT GST"><i class="fa fa-info-circle" style="font-size:12px;color:red"></i></span></span></label>
                                        <input style="background-color: #ccc;padding: 10px;" type="text" maxlength="10"  id="total_amc_subscription_1"   class="form-control form-control-line num productamount">
                                          
                                      </div> 
                                     </div>



                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Invoice Email-Address<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" ></span></span></label>
                                        <input style="" required="required" name="invoice_email_address" type="email"  id="invoice_email_address_1"  class="form-control form-control-line">
                                          
                                      </div> 
                                     </div>


                                      <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Invoice Emailer Name<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;"></span></span></label>
                                        <input style="" name="invoice_emailer_name" type="text"   id="invoice_emailer_name_1" required="required"  class="form-control form-control-line">
                                          
                                      </div> 
                                     </div>

                                

                               
                                </div>

                              
                                <div class="row">
                                    <div class="col-sm-12 pull-right mt-4 text-right">
                                        <div class="card-body">
                                          <div class="form-group mb-0">
                                            <button  type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                
                            
                              
                            </form>
                        </div>
                    </div>

                </div>




           
         <div class="row">
                    <!-- Column -->
                   <div class="col-md-12">
                        <div class="card card-body">
                           <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">AMC Setup View</h6>
                            <div class="">
                                   
                                 <table class="table-hover table-bordered table no-wrap"
                                    id="view_amc_setup">
                                    
                                    <thead>
                                        <tr>
                                         <!--    <th >SNO</th> -->
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">Customer</th>
                                            <th data-toggle="tooltip" title="AMC Service Start Date"> Start Date</th>
                                            <th data-toggle="tooltip" title="AMC Service Due Date">Due Date</th>
                                            <th data-toggle="tooltip" title="AMC Service Paid Date">Paid Date</th>
                                            <th>AMC(%)</th>
                                            <th>Monthly</th>
                                            <th>Yealy </th>
                                            <th>AMC Amount</th>
                                            <th>Status</th>
                                             <th class="text-center">Action</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                     
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

    <script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/js/pages/datatable/custom-datatable.js"></script>
       <script src="<?php echo base_url();?>assets/extra-libs/prism/prism.js"></script>
    <script type="text/javascript">

var table;

$(document).ready(function() {
      var url = "<?php echo base_url();?>"+"settings/Settings/viewAmcSetup";
    //datatables
    table = $('#view_amc_setup').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "pageLength" : 10,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
             "data":{"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>",
}           
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
        "bSort" : false,
        "aoColumnDefs": [

          { "sClass":"text-center", "aTargets": [ 5 ] }
           //You can also set 'sType' to 'numeric' and use the built in css.           
        ],
         createdRow: function (row, data, dataIndex)
        {
                $(row).attr('title', data.mouseover);
                $(row).attr("data-toggle","tooltip");
                $(row).attr("data-placement","right");
                 $(row).attr("style","cursor: pointer;");
            
        },

    });

     $('#view_amc_setup').on('draw.dt', function () 
     {
        $('[data-toggle="tooltip"]').tooltip({html:true});
    });

    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload();  //just reload table
    });

});

</script>

<script>
     function amcsetupEdit(id)
      {
        var url = "<?php echo base_url();?>"+"settings/Settings/edit_amcSetUp";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"json",
            data:{'amc_setup_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"
},
            success: function(data)
            {

                
                if(data.tas_amc_customer_id > 0)
                {
                  $("#customer_name_id").focus();
                    $("#customer_id").val(data.tas_amc_customer_id);
                }
                else
                {
                  $("#customer_id").val('');
                }

                 if(data.tas_amc_customer_code !='')
                {
                    $("#customer_code").val(data.tas_amc_customer_code);
                }
                else
                {
                  $("#customer_code").val('');
                }

                 if(data.tas_amc_customer_name !='')
                {
                    $("#customer_name_id").val(data.tas_amc_customer_name);
                }
                else
                {
                  $("#customer_name_id").val('');
                }

                 if(data.tas_amc_percentage !='')
                {
                    $("#amc_pecentage_id_1").val(data.tas_amc_percentage);
                }
                else
                {
                  $("#amc_pecentage_id_1").val('');
                }

                 if(data.service_date !='')
                {
                    $("#service_start_date_id_1").val(data.service_date);
                }
                else
                {
                  $("#service_start_date_id_1").val('');
                }

                 if(data.tas_amc_monthly_subscription !='')
                {
                    $("#monthly_subscription_1").val(data.tas_amc_monthly_subscription);
                }
                else
                {
                  $("#monthly_subscription_1").val('');
                }

                 if(data.tas_amc_yearly_subscription !='')
                {
                    $("#yealy_subscription_1").val(data.tas_amc_yearly_subscription);
                }
                else
                {
                  $("#yealy_subscription_1").val('');
                }

                  if(data.tas_amc_invoice_mail !='')
                {
                    $("#invoice_email_address_1").val(data.tas_amc_invoice_mail);
                }
                else
                {
                  $("#invoice_email_address_1").val('');
                }

                if(data.tas_amc_invoice_mailer_name !='')
                {
                    $("#invoice_emailer_name_1").val(data.tas_amc_invoice_mailer_name);
                }
                else
                {
                  $("#invoice_emailer_name_1").val('');
                }

                if(data.tas_amc_amount !='')
                {
                    $("#total_amc_subscription_1").val(data.tas_amc_amount);
                }
                else
                {
                  $("#total_amc_subscription_1").val('');
                }

           
                
            }
        });
      }
      function amcsetupStatus(id)
      {
             var from_id =  id;
             $( "#status_frm_id_"+from_id ).submit();
      } 
</script>

<script type="text/javascript">

$(function()
{
    $("#amc_pecentage_id_1").change(function()
    {
        var amcpercentage = this.value;
         $("#total_amc_subscription_1").val('');
        $("#yealy_subscription_1").val('');

        $("#amc_pecentage_id_1").css("border-bottom","");

        var montly_amount = $("#monthly_subscription_1").val();

        if(montly_amount > 0 && amcpercentage > 0)
        {
            var yearly_amount = parseFloat(montly_amount * 12);

            $("#yealy_subscription_1").val(yearly_amount);

            var total_amc = parseFloat(yearly_amount) * parseFloat(amcpercentage) / 100;


            $("#total_amc_subscription_1").val(total_amc);
        }

      


       
    });

    $("#monthly_subscription_1").keyup(function()
    {
        var amcpercentage = $("#amc_pecentage_id_1").val();
        $("#total_amc_subscription_1").val('');
        $("#yealy_subscription_1").val('');
        if(amcpercentage > 0 && this.value !='')
        {
            $("#amc_pecentage_id_1").css("border-bottom","");
            var montly_amount = parseFloat(this.value);

            var yearly_amount = parseFloat(montly_amount * 12);

            $("#yealy_subscription_1").val(yearly_amount);

            var total_amc = parseFloat(yearly_amount) * parseFloat(amcpercentage) / 100;

           
           $("#total_amc_subscription_1").val(Math.round(total_amc));
            


        }
        else
        {
            $("#monthly_subscription_1").val('');
            $("#amc_pecentage_id_1").css("border-bottom","red 1px solid");
            //$("#amc_pecentage_id_1").focus();

        }
        

      
         
    });
function roundToTwo(num) {    
    return +(Math.round(num + "e+0"));
}
    
});



</script>