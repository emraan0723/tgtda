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
                            <h6 class="card-title" style="margin-bottom: -27px;">Add Discounts</h6>
                            <!-- Row -->

                            

                           <form class="form-material mt-5 novalidate" method="post" onsubmit="return  discountsValidateForm('create')">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="row">
                                    <div class="col-sm-12 col-lg-4">
                                   <div class="form-group">
                                         <label>Select Customer<span class="text-danger">*</span></label>
                                         <select class="form-control" id="customer_id_1" onchange="getCurrency(this);" name="customer_id">
                                                <option value=""></option>
                                                <?php
                                                if(isset($customer_list) && count($customer_list) > 0)
                                                {
                                                    foreach ($customer_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['customer_id']) ? $value['customer_id'] : 0; ?>"><?php echo isset($value['customer_name']) ? ucwords($value['customer_name']).' ('.$value['customer_code'].')' : ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                                
                                            </select>

                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                    </div>
                                 </div> 
                                
                              
                                 <div class="col-sm-12 col-lg-4">
                                   <div class="form-group">
                                         <label>Select Products<span class="text-danger">*</span></label>
                                         <select class="form-control" onchange="getProductAmount(this);" id="product_id_1" name="product_id">
                                                <option value=""></option>
                                                <?php
                                                if(isset($product_list) && count($product_list) > 0)
                                                {
                                                    foreach ($product_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['product_id']) ? $value['product_id'] : 0; ?>"><?php echo isset($value['product_name']) ? ucwords($value['product_name']).' ('.$value['product_code'].')' : ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                                
                                            </select>
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                    </div>
                                 </div>     

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>Discount Amount <span class="text-danger">*</span></label>
                                        <input type="text" name="discount_amount" id="discount_amount_id_1"  class="form-control form-control-line num"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-lg-4">

                                      <div class="form-group ">
                                       <label>Discount Validity<span class="text-danger">* <span data-toggle="tooltip" data-placement="right" style="color:red;cursor:pointer;" title="Indicate Period For Which The Discount As Applicable ">?</span></span></label>
                                       <div class="input-daterange input-group" id="date-range">
                                        <input type="text" readonly="readonly"  class="disdatepicker form-control" name="discount_start_date" id="discount_start_date_id_1"/>
                                        
                                        <div class="input-group-append">
                                          <span class="input-group-text bg-info b-0 text-white">TO</span>
                                        </div>
                                        <input type="text" readonly="readonly" class="disdatepicker form-control" name="discount_expiry_date" id="discount_expiry_date_id_1" />

                                      </div>

                                      <div class="valid-feedback dicountdates" style="color: red">
                                      </div>
                                    </div>


                                </div>
                                  <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Discount Description <span class="text-danger">*</span></label>
                                        <textarea name="discount_desc" maxlength="1024" id="discount_desc_id_1" class="form-control" rows="5"></textarea>
                                          <div class="valid-feedback" style="color: red">
                                     </div>
                                </div>
                             </div>

                                    <div class="col-sm-12 col-lg-4">
                                        <div class="card-body">
                                          <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
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
                                <h6 class="card-title">View Discounts</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap" id="discounts_datatable"
                                    >
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">SNO</th>
                                            <th>Customer</th>
                                            <th >Product</th>
                                            <th >Discount</th>
                                            <th >Discount Validity </th>
                                          <!--   <th>Created Date & Time</th> -->
                                            <th>Status </th>
                                            <th  class="text-center">Action</th>
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

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/discounts_validations.js?v1=<?php echo rand(); ?>"></script>
    <script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/js/pages/datatable/custom-datatable.js"></script>
<script type="text/javascript">

var table;

$(document).ready(function() {

    

      var url = "<?php echo base_url();?>"+"settings/discounts/ajax_list";
    //datatables
    table = $('#discounts_datatable').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "bSort" : false,
        "pageLength" : 10,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
            "data":{"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"
                },
         
        },
        
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ,5 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
         createdRow: function (row, data, dataIndex)
        {
                $(row).attr('title', data.mouseover);
                $(row).attr("data-toggle","tooltip");
                $(row).attr("data-placement","right");
                 $(row).attr("style","cursor: pointer;");
            
        },
        "bSort" : false,
        "aoColumnDefs": [

          { "sClass":"text-center", "aTargets": [ 6 ] }
           //You can also set 'sType' to 'numeric' and use the built in css.           
    ]

   

    });

     $('#discounts_datatable').on('draw.dt', function () 
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
<script src="<?php echo base_url();?>assets/libs/moment/moment.js"></script>
<script src="<?php echo base_url();?>assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>

      function discountsStatus(id)
      {
        $("#password_A_I_id").val('');
        $("#reason_A_I_id").val('');

        $("#getformdeatils_id").val("");
        $("#getformdeatils_id").val(id);
        $('#centermodal').modal('show');
      } 


 $.fn.datepicker.defaults.format = "dd-mm-yyyy";

   
$(".disdatepicker").datepicker({ 
   startDate: "today" ,
     todayHighlight: true
    
});
    // Date Picker
    jQuery('.mydatepicker, #datepicker, .input-group.date').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });
</script>


