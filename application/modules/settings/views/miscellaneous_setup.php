 <?php
  $gst_cal = $tax['CGST'] + $tax['SGST'] ;
  $gst_cal = $gst_cal / 100 ;
  $igst_cal = $tax['IGST'] / 100 ;
  
  ?>
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


                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Currency</label>
                                        <input required="required"  type="text" disabled="disabled"   class="form-control form-control-line cus_currency_cls" style="background-color: #eee !important;">
                                          
                                      </div> 
                                     </div>

                            
                                
                                   <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Title Name<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" ></span></span></label>
                                        <input style="" required="required" name="title_name" type="text"  id="title_name_id_1"  class="form-control form-control-line">
                                          
                                      </div> 
                                     </div>

                                       <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Description<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" ></span></span></label>
                                        <textarea style="" required="required" name="description" type="text"  id="description"  class="form-control form-control-line"></textarea> 
                                          
                                      </div> 
                                     </div>
                                      
                                       <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Product Amount<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="With out GST"><i class="fa fa-info-circle" style="font-size:12px;color:red"></i></span></span></label>
                                        <input style="padding: 10px;" onkeydown="getTaxAmount(this);" onkeyup="getTaxAmount(this);" required="required" name="pamount" type="text" maxlength="10"  id="mproduct_amount"   class="form-control form-control-line num">
                                          
                                      </div> 
                                     </div>
                              
                            
                                         <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label> Total Amount <span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="With GST"><i class="fa fa-info-circle" style="font-size:12px;color:red"></i></span></span></label>
                                        <input style="background-color: #ccc;padding: 10px;" type="text" maxlength="10" readonly="readonly" name="total_amt" id="total_amt_id"  class="form-control form-control-line num">
                                          <div class="valid-feedback" style="color: red">
                                      </div> 
                                     </div>
                                </div>


                                    



                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Invoice Email-Address<span class="text-danger">*<span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" ></span></span></label>
                                        <input style="" required="required" name="invoice_email_address" type="text"  id="invoice_email_address_1"  class="form-control form-control-line">
                                          
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
                                <h6 class="card-title">Miscellaneous View</h6>
                            <div class="table-responsive">
                                    
                                 <table id="view_miscellaneous_setup" class="table table-striped table-bordered display" style="width:100%">
                                    
                                    <thead>
                                        <tr>
                                         <!--    <th >SNO</th> -->
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">Customer</th>
                                             <th data-toggle="tooltip" title=""> Currency</th>
                                            <th data-toggle="tooltip" title=""> Title</th>
                                            <th data-toggle="tooltip" title="">Description</th>
                                            <th data-toggle="tooltip" title="">Product Amount</th>
                                            <th data-toggle="tooltip" title="">Total Amount</th>
                                            <th>Created Date</th>
                                            <th>Sent Date</th>
                                             <th class="text-center">Action</th>   
                                        </tr>
                                    </thead>
                                   
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
        function getTaxAmount(e)
        {
          $("#total_amt_id").val('');
          var currency = $(".cus_currency_cls").val();
          if(parseInt(e.value) > 0 && currency !='')
          {
               var amount =parseFloat(e.value);
               
               var gst=0;
               if(currency=='INR')
               {
                  gst ="<?php echo $gst_cal ?>";
                  //gst ="<?php echo $igst_cal ?>";
               }
              
              var gst_amount = parseFloat(amount) * parseFloat(gst) ;
              var final_amount = parseFloat(amount) +  parseFloat(gst_amount);
              final_amount = Math.round(final_amount);
              
              $("#total_amt_id").val(final_amount);
          }
         
        }
      </script>

          <script type="text/javascript">

var table;

$(document).ready(function() {
      var url = "<?php echo base_url();?>"+"settings/Miscellaneous/viewMiscellaneous";
    //datatables
    table = $('#view_miscellaneous_setup').DataTable({ 

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

     $('#view_miscellaneous_setup').on('draw.dt', function () 
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


function SendingInvoice(key)
{
  if(key !='')
  {   
        $('#cover-spin').show();
       var url = "<?php echo base_url();?>"+"settings/Miscellaneous/SendInvoice";
      $.ajax({
        type:'POST',
        url: url,
        data:{'key_id':key,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
        dataType:'json',
        success: function(data)
        {
           if(data =='MAIL_SENT')
           {
              alert("Mails sending successfully");
              $('#cover-spin').hide();
               window.location.reload();
           }
           else
           {  
                alert("Mail sending faild");
               $('#cover-spin').hide();
           }

         }
      });
   }

}

$(document).ready(function() 
{
  $('#description').keypress(function (e) {
    if (e.which == 13 || e.which == 44) {
      $("#description").val($('textarea').val() + ',');
    }
});
 
});

</script>

      