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
                            <h6 class="card-title" style="margin-bottom: -27px;">Add Country</h6>
                            <!-- Row -->

                            


                           <form class="form-material mt-5 needs-validation" method="post" onsubmit="return  countryValidationForm();">

                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>Country Name <span class="text-danger">*</span></label>
                                        <input type="text" name="country_name" maxlength="100" id="country_name_id"  class="form-control form-control-line alpha_s" > 
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>Country Code <span class="text-danger">*</span></label>
                                        <input type="text"  name="country_code" maxlength="10" id="country_code_id"  class="form-control form-control-line alpha"> 
                                        <div class="valid-feedback country_code_cls" style="color: red">
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
                                <h6 class="card-title">View Countrys</h6>
                             <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap display"
                                    data-tablesaw-mode="columntoggle" id="country_datatable">
                                    
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sno</th>
                                            <th>Country Name</th>
                                            <th>Country Code</th>
                                            <th>Status</th>
                                             <th>Action</th>   
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
        $(document).ready(function(e)
        {
             $('#country_datatable thead th').each(function () {
                var title = $(this).text();
                $(this).html(title+' <input type="text" class="col-search-input" placeholder="Search ' + title + '" />');
            });
            

            var base_url = "<?php echo base_url();?>masters/masters/viewCountrys"; // You can use full url here but I prefer like this
               var table =  $('#country_datatable').DataTable
                ({
                    "processing": true,
                    "pageLength" : 10,
                    "serverSide": true,
                    "columnDefs": [
                    { "targets": [0,4] ,"orderable": false, "searchable": true}
                    ],
                     "order": [[0, "desc" ]],

                    "ajax":{
                        url :  base_url,
                        type : 'POST'
                },
                }); // End of DataTable`

                  table.columns().every(function () {
                var table = this;
                $('input', this.header()).on('keyup change', function () {
                    if (table.search() !== this.value) {
                           table.search(this.value).draw();
                    }
                });
            });

                setTimeout(function(){ $('.no-sort').removeClass('sorting_desc'); }, 300);
                  
                 
        }); // End Document Ready Function
    </script>

  <script type="text/javascript">
      function countryEdit(id)
      {
        var url = "<?php echo base_url();?>"+"masters/masters/editCountry";
        $.ajax({
            type:'POST',
            url: url,
            dataType:"html",
            data:{'country_id':id},
            success: function(data){
              $('#modal_div').html(data);
              $('#myModal').modal('show');

            }
        });
      }
      function countryStatus(id)
      {
              var from_id =  id;
             $( "#status_frm_id_"+from_id ).submit();
      } 

    function countryValidationForm()
    {
        var alpha_s = /^([A-Za-z\ ]+)$/;
        var alpha = /^([A-Za-z]+)$/;
        if(($.trim($("#country_name_id").val()) =='' )|| ($.trim($("#country_name_id").val().length) <= 2))
        {   
            $("#country_name_id").focus();
            $("#country_name_id").css('border-bottom',"red 1px solid");
            $(".country_name_cls").text("Please Enter First Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {
             $("#country_name_id").css('border-bottom',"");
        }

        if(($.trim($("#country_code_id").val()) =='' )|| ($.trim($("#country_code_id").val().length) <= 1))
        {   
            $("#country_code_id").focus();
            $("#country_code_id").css('border-bottom',"red 1px solid");
            $(".country_code_cls").text("Please Enter Country  Code Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {
             $("#country_code_id").css('border-bottom',"");
        }

   

    }
      
  </script>