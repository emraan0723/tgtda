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
                            <h6 class="card-title" style="margin-bottom: -27px;">Mapping Product With Category</h6>
                            <!-- Row -->

                            
                           
                           <form class="form-material mt-5 needs-validation" method="post">

                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="row">
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
                                   <div class="form-group">
                                         <label>Select Products<span class="text-danger">*</span></label>
                                         <select class="form-control" required="required" id="product_id_1" name="product_id">
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
                                <h6 class="card-title">View Mapping Product With Category</h6>
                             <div class="table-responsive">
                                 


                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap display"
                                    data-tablesaw-mode="columntoggle" id="category_datatable">
                                    
                                    <thead>
                                        <tr>
                                            <th class="no-sort">SNO</th>
                                            <th>Category </th>
                                            <th>Product</th>
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
      var url = "<?php echo base_url();?>"+"settings/Category_product_maping/ajax_list";
    //datatables
    table = $('#category_datatable').DataTable({ 

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
        "aoColumnDefs": [

          { "sClass":"text-center", "aTargets": [ 4 ] }
           //You can also set 'sType' to 'numeric' and use the built in css.           
        ]

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
      function categoryProdcutEdit(id)
      {
        var url = "<?php echo base_url();?>"+"settings/Category_product_maping/edit_category";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"html",
            data:{'category_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
            success: function(data){
              $('#modal_div').html(data);
              $('#myModal').modal('show');

            }
        });
      }
      function categoryProdcutStatus(id)
      {
        $("#password_A_I_id").val('');
        $("#reason_A_I_id").val('');

        $("#getformdeatils_id").val("");
        $("#getformdeatils_id").val(id);
        $('#centermodal').modal('show');
      } 

  
  </script> 

