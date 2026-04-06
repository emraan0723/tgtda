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
                            <h6 class="card-title" style="margin-bottom: -27px;">Add District</h6>
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>
                           <form id="frm_state_create_id" class="form-material mt-5 needs-validation" method="post" onsubmit="return  districtValidationForm('create');">
                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="row">
                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control" id="country_id_1" onchange="StateList(this,'<?php echo base_url(); ?>');" name="country_id">
                                                <option value=""></option>
                                                <?php
                                                if(isset($country_list) && count($country_list) > 0)
                                                {
                                                    foreach ($country_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['country_id']) ? $value['country_id'] : 0; ?>"><?php echo isset($value['country_name']) ? ucwords($value['country_name']) : ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                                
                                            </select>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                    </div>
                                </div>

                                 <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select State <span class="text-danger">*</span></label>
                                            <select class="form-control statelist" id="state_id_1" name="state_id">
                                                <option value=""></option>
                                                
                                            </select>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>District Name  <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="district_name" id="district_name_id_1"  class="form-control form-control-line alpha_s"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <div class="form-group ">
                                        <label>District Code</label>
                                        <input type="text" maxlength="5" name="district_code" id="district_code_id_1"  class="form-control form-control-line alpha"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                            </div>
                            <div class="row">
                                  <div class="col-sm-12 col-lg-12">
                                   <div class="card-body form-group mb-0 text-right">
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
                                <h6 class="card-title">View Districts</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    id="state_datatable">
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">SNO</th>
                                            <th>Country Name </th>
                                            <th>State Name </th>
                                            <th>District Name </th>
                                            <th>District Code </th>
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
      var url = "<?php echo base_url();?>"+"district_datatable";
    //datatables
    table = $('#state_datatable').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
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
            "targets": [ 0 ,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
        "bSort" : false,
        "aoColumnDefs": [

          { "sClass":"text-center", "aTargets": [6] }
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
     function districtEdit(id)
      {
        var url = "<?php echo base_url();?>"+"edit_district";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"html",
            data:{'district_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
            success: function(data){
              $('#modal_div').html(data);
              $('#myModal').modal('show');

            }
        });
      }
      function districtStatus(id)
      {
        $("#password_A_I_id").val('');
        $("#reason_A_I_id").val('');

        $("#getformdeatils_id").val("");
        $("#getformdeatils_id").val(id);
        $('#centermodal').modal('show');
      } 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/district_validations.js?v1=<?php echo rand(); ?>"></script>
  
