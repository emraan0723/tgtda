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
                            <h6 class="card-title" style="margin-bottom: -27px;">Add Currency</h6>
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>

                           <form  class="form-material mt-5 needs-validation" method="post">

                              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                            <div class="row">
                                 

                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="currency_name" id="currency_name_id_1" required="required" class="form-control form-control-line alpha_s"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                                 <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Short Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="currency_short_name" id="currency_short_name_1" required="required"  class="form-control form-control-line alpha"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>


                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Symbol</label>
                                        <input type="text" maxlength="50" name="currency_symbol" id="currency_symbol_id_1"  required="required"  class="form-control form-control-line"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-3">
                                   <div class="card-body">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <a href="" class="btn btn-dark waves-effect waves-light">Reset</a>
                                        
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
                                <h6 class="card-title">View Currency</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    id="currency_datatable">
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">SNO</th>
                                            <th> Currency Name</th>
                                            <th >Currency Short Name </th>
                                            <th >Currency Symbol </th>
                                            
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
      var url = "<?php echo base_url();?>"+"currency_datatable";
    //datatables
    table = $('#currency_datatable').DataTable({ 
        "bSort" : false,
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

<script>
     function currencyEdit(id)
      {
        var url = "<?php echo base_url();?>"+"edit_currency";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"html",
            data:{'currency_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"
},
            success: function(data){
              $('#modal_div').html(data);
              $('#myModal').modal('show');

            }
        });
      }
      function currencyStatus(id)
      {
             var from_id =  id;
             $( "#status_frm_id_"+from_id ).submit();
      } 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/currency_validations.js?v1=<?php echo rand(); ?>"></script>

  
