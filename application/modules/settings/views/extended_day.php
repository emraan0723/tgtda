<link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet"><!-- ============================================================== --><!-- Container fluid  --><!-- ============================================================== -->
<div class="container-fluid">
    <!-- Row -->


    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card card-body">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">View States</h6>
                        <div class="table-responsive">

                            <table class="tablesaw table-striped table-hover table-bordered table no-wrap" id="extended_datatable">

                                <thead>
                                <tr>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">SNO</th>
                                    <th>Customer Name</th>
                                    <th>Customer Code</th>
                                    <th>Extended Day</th>
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
</div><!-- ============================================================== --><!-- End Container fluid  --><!-- ============================================================== -->

<script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>dist/js/pages/datatable/custom-datatable.js"></script>
<script type="text/javascript">

    var table;

    $(document).ready(function () {
        var url = "<?php echo base_url();?>" + "extended_day_datatable";
        //datatables
        table = $('#extended_datatable').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "pageLength": 10,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST",
                "data": {
                    "<?php echo $this->security->get_csrf_token_name();?>": "<?php echo $this->security->get_csrf_hash();?>",
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
            "bSort": false,
            "aoColumnDefs": [

                {"sClass": "text-center", "aTargets": [4]}
                //You can also set 'sType' to 'numeric' and use the built in css.
            ]

        });

        $('#btn-filter').click(function () { //button filter event click
            table.ajax.reload();  //just reload table
        });
        $('#btn-reset').click(function () { //button reset event click
            $('#form-filter')[0].reset();
            table.ajax.reload();  //just reload table
        });

    });

</script>

<script>
    function editExtendedDay(id) {
        var url = "<?php echo base_url();?>" + "edit_extended_day";
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "html",
            data: {
                'extended_id': id,
                "<?php echo $this->security->get_csrf_token_name();?>": "<?php echo $this->security->get_csrf_hash();?>"
            },
            success: function (data) {
                $('#modal_div').html(data);
                $('#myModal').modal('show');

            }
        });
    }

    function stateStatus(id) {
        var from_id = id;
        $("#status_frm_id_" + from_id).submit();
    }
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/state_validations.js?v1=<?php echo rand(); ?>"></script>

  
