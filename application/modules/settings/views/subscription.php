  <link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

 <div class="pull-right text-right"> <a href="<?php echo base_url().'category_product_maping'; ?>" class="btn waves-effect waves-light btn-dark">Mapping Product With Category</a></div>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <h6 class="card-title" style="margin-bottom: -27px;">Mapping Category with Currency</h6>
                            <!-- Row -->

                            
                           
                           <form class="form-material mt-5 needs-validation" method="post">

                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="row">
                              <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label >Select Currency<span class="text-danger"> * </span></label>
                                        <select required="required"  data-toggle="tooltip"  name="currency_id" class="form-control form-control-line">
                                          <option value=""></option>
                                           <?php
                                                if(isset($currency_list) && count($currency_list) > 0)
                                                {
                                                    foreach ($currency_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option title="<?php echo isset($value['currency_name']) ? $value['currency_name'] : ''; ?>"  value="<?php echo isset($value['currency_id']) ? $value['currency_id'] : 0; ?>"><?php echo isset($value['currency_short_name']) ? $value['currency_short_name'].'('.$value['symbol'].')' : ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                        </select>
                                      
                                        <div class="valid-feedback category_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label >Select Category<span class="text-danger"> * </span></label>
                                        <select required="required" name="category_id" class="form-control form-control-line">
                                          <option value=""></option>
                                           <?php
                                                if(isset($category_list) && count($category_list) > 0)
                                                {
                                                    foreach ($category_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['category_id']) ? $value['category_id'] : 0; ?>"><?php echo isset($value['category_name']) ? ucwords($value['category_name']): ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                        </select>
                                      
                                        <div class="valid-feedback category_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                             

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Monthly Cost<span class="text-danger">*</span></label>
                                        <input type="text" required="required" maxlength="10" name="monthly_cost" id="monthly_cost_id_1"  class="form-control form-control-line num">
                                          <div class="valid-feedback" style="color: red">
                                      </div> 
                                     </div>
                                </div> 

                                <div class="col-sm-12 col-lg-4">
                                   <div class="card-body">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <a class="btn btn-dark waves-effect waves-light" href="">Reset</a>
                                      
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
                                <h6 class="card-title">View Mapping Category with Currency</h6>
                             <div class="table-responsive">
                                 


                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap display"
                                    data-tablesaw-mode="columntoggle" id="subscription_datatable">
                                    
                                    <thead>
                                        <tr>
                                            <th class="no-sort">SNO</th>
                                            <th>Currency </th>
                                            <th>Category </th>
                                            <th>Monthly</th>
                                            <th>GST</th>
                                            <th>Monthly Payable</th>
                                            <th>Quarterly Payable</th>
                                            <th>Halfyearly Payable</th>
                                            <th>AMC</th>
                                            <th >Yearly Payable</th>
                                            <th>Footfalls Per Month</th>
                                             <th>Cost Per Patient</th>
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

<script type="text/javascript">

var table;

$(document).ready(function() {
      var url = "<?php echo base_url();?>"+"settings/Subscription_master/ajax_list";
    //datatables
    table = $('#subscription_datatable').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "pageLength" : 10,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
            "data": {"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"}
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
        "bSort" : false,
         createdRow: function (row, data, dataIndex)
        {
                $(row).attr('title', data.mouseover);
                $(row).attr("data-toggle","tooltip");
                $(row).attr("data-placement","right");
                 $(row).attr("style","cursor: pointer;");
            
        },
        "aoColumnDefs": [

          { "sClass":"text-center", "aTargets": [ 13 ] }
           //You can also set 'sType' to 'numeric' and use the built in css.           
        ]

    });

     $('#subscription_datatable').on('draw.dt', function () 
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

  <script type="text/javascript">
     
      function subscriptionProdcutStatus(id)
      {
        $("#password_A_I_id").val('');
        $("#reason_A_I_id").val('');

        $("#getformdeatils_id").val("");
        $("#getformdeatils_id").val(id);
        $('#centermodal').modal('show');
      } 

  
  </script> 

