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
                            <h6 class="card-title" style="margin-bottom: -27px;">Add Invoice Sendeing Details</h6>
                            <!-- Row -->

                            
                           
                           <form class="form-material mt-5 needs-validation" method="post" >

                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label >Left Header Name <span class="text-danger">*</span></label>
                                        <input type="text" required="" name="left_header_name"  id="left_header_name_id_1"  class="form-control form-control-line" > 
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label >Right Header Name <span class="text-danger">*</span></label>
                                        <input type="text" name="right_header_name"  id="right_header_name_id_1"  class="form-control form-control-line" > 
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                  <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label >From Address<span class="text-danger">*</span></label>
                                        <textarea  name="from_addr"  id="from_addr_id_1"  class="form-control form-control-line" > </textarea>
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>


                                  <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label >Footer Address<span class="text-danger">*</span></label>
                                        <textarea name="footer_addr"  id="footer_addr_id_1"  class="form-control form-control-line" > </textarea>
                                        <div class="valid-feedback country_name_cls" style="color: red">
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
                                <h6 class="card-title">View Invoice Sendeing Details</h6>
                             <div class="table-responsive">
                                 


                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap display"
                                    data-tablesaw-mode="columntoggle" id="invoice_details">
                                    
                                    <thead>
                                        <tr>
                                            <th class="no-sort">SNO</th>
                                            <th>Left Header Name </th>
                                            <th>Right Header Name</th>
                                            <th> From Address</th>
                                             <th>Footer Address</th>
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
      var url = "<?php echo base_url();?>settings/Invoice_sendeing_details/ajax_list";
    //datatables
    table = $('#invoice_details').DataTable({ 

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

  <script type="text/javascript">
      function InvoicedetailsEdit(id)
      {
        var url = "<?php echo base_url();?>settings/Invoice_sendeing_details/editInvoiceDeatils";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"html",
            data:{'invoice_details_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
            success: function(data){
              $('#modal_div').html(data);
              $('#myModal').modal('show');

            }
        });
      }
      function InvoicedetailsStatus(id)
      {
              var from_id =  id;
             $( "#status_frm_id_"+from_id ).submit();
      } 

  
  </script> 

