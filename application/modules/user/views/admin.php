<link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/extra-libs/prism/prism.css">
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- Row -->

    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card card-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    id="admin_datatable">

                                <thead>
                                <tr>
                                    <th >SNO</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Mobile</th>
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



        var url = "<?php echo base_url();?>"+"admin/admin/ajax_list";
        //datatables
        table = $('#admin_datatable').DataTable({

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
            "aoColumnDefs": [

                { "sClass":"text-center", "aTargets": [ 5 ] }
                //You can also set 'sType' to 'numeric' and use the built in css.
            ]



        });

        $('#admin_datatable').on('draw.dt', function ()
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
    function adminEdit(id)
    {
        var userprivileges_check =  "<?php echo isset($_SESSION['userprivileges']['admin']['edit']) ? $_SESSION['userprivileges']['admin']['edit'] : 0 ?> ";
        if(userprivileges_check ==0)
        {
            window.location.replace("<?php echo base_url().'authorization'; ?>");
        }
        else
        {
            var url = "<?php echo base_url();?>"+"admin/admin/edit_admin";
            $.ajax({
                type:'POST',
                url: url,
                dataType:"html",
                data:{'admin_id':id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
                success: function(data){
                    $('#modal_div').html(data);
                    $('#myModal').modal('show');

                }
            });
        }
    }
    function adminStatus(id)
    {
        $("#password_A_I_id").val('');
        $("#reason_A_I_id").val('');

        $("#getformdeatils_id").val("");
        $("#getformdeatils_id").val(id);
        $('#centermodal').modal('show');

    }


    function ResetPassword(id)
    {

        $("#reset_password_from_"+id ).submit();



    }
</script>

