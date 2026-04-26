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
                <h6 class="card-title" style="margin-bottom: -27px;">Add Active Member</h6>
                <!-- Row -->

                <?php //echo validation_errors(); ?>
                <form id="frm_state_create_id" class="form-material mt-5 needs-validation" method="post" onsubmit="return  mandalValidationForm('create');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="row">
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group ">
                                <label>Select Country <span class="text-danger">*</span></label>
                                <select class="form-control" id="country_id_1" onchange="StateList(this);" name="country_id">
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
                                <label>Select State </label>
                                <select class="form-control statelist" onchange="DistictsList(this);" id="state_id_1" name="state_id">
                                    <option value=""></option>

                                </select>
                                <div class="valid-feedback" style="color: red">
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group ">
                                <label>Select Districts</label>
                                <select class="form-control distictslist" onchange="MandalList(this);" id="disticts_id_1" name="district_id">
                                    <option value=""></option>

                                </select>
                                <div class="valid-feedback" style="color: red">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group ">
                                <label>Select Mandal</label>
                                <select class="form-control mandallist"  id="mandal_id_1" name="mandal_id">
                                    <option value=""></option>

                                </select>
                                <div class="valid-feedback" style="color: red">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Select Designation <span class="text-danger">*</span></label>
                                <select class="form-control" id="designation_id_1" name="designation">
                                    <option value="">-- Select --</option>
                                    <?php
                                    if(isset($designation_list) && count($designation_list) > 0):
                                        foreach($designation_list as $desig):
                                            ?>
                                            <option value="<?php echo $desig; ?>"><?php echo $desig; ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group ">
                                <label>Active Member Name</label>
                                <input type="text" maxlength="150" name="member_name"
                                        id="member_name_id_1"
                                        class="form-control form-control-line"
                                        placeholder="Enter full name">
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
                                </div
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>

    </div>


        <!-- Column -->
        <div class="col-md-12">
            <div class="card card-body">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">View Active Members</h6>
                        <div class="table-responsive">

                            <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    id="active_member_datatable">

                                <thead>
                                <tr>
                                    <th class="border">SNO</th>
                                    <th>Country Name</th>
                                    <th>State Name</th>
                                    <th>District Name</th>
                                    <th>Mandal Name</th>
                                    <th>Designation</th>
                                    <th>Member Name</th>
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




<!-- Modal placeholder -->
<div id="modal_div"></div>

<script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>dist/js/pages/datatable/custom-datatable.js"></script>

<script type="text/javascript">
    var amTable;
    var BASE_URL = "<?php echo base_url(); ?>";
    var CSRF_NAME  = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var CSRF_TOKEN = "<?php echo $this->security->get_csrf_hash(); ?>";

    $(document).ready(function() {
        amTable = $('#active_member_datatable').DataTable({
            "processing"  : true,
            "serverSide"  : true,
            "order"       : [],
            "pageLength"  : 10,
            "ajax": {
                "url"  : BASE_URL + "active_member_datatable",
                "type" : "POST",
                "data" : { [CSRF_NAME]: CSRF_TOKEN }
            },
            "columnDefs": [
                { "targets": [0, 7], "orderable": false }
            ],
            "bSort"       : false,
            "aoColumnDefs": [
                { "sClass": "text-center", "aTargets": [8] }
            ]
        });
    });

    /* ── Cascading dropdowns (create form) ────────────────────────── */
    function amStateList(el)
    {
        var country_id = $(el).val();
        var suffix     = $(el).attr('id').replace('country_id_','');
        $('#state_id_'+suffix).html('<option value="">-- Select --</option>');
        $('#district_id_'+suffix).html('<option value="">-- Select --</option>');
        $('#mandal_id_'+suffix).html('<option value="">-- Select --</option>');
        if(!country_id) return;
        $.post(BASE_URL + 'ajax/get_states', { country_id: country_id, [CSRF_NAME]: CSRF_TOKEN },
            function(res) {
                var html = '<option value="">-- Select --</option>';
                $.each(res, function(i, v) {
                    html += '<option value="'+v.state_id+'">'+v.state_name+'</option>';
                });
                $('#state_id_'+suffix).html(html);
            }, 'json');
    }

    function amDistrictList(el)
    {
        var state_id = $(el).val();
        var suffix   = $(el).attr('id').replace('state_id_','');
        $('#district_id_'+suffix).html('<option value="">-- Select --</option>');
        $('#mandal_id_'+suffix).html('<option value="">-- Select --</option>');
        if(!state_id) return;
        $.post(BASE_URL + 'ajax/get_districts', { state_id: state_id, [CSRF_NAME]: CSRF_TOKEN },
            function(res) {
                var html = '<option value="">-- Select --</option>';
                $.each(res, function(i, v) {
                    html += '<option value="'+v.district_id+'">'+v.district_name+'</option>';
                });
                $('#district_id_'+suffix).html(html);
            }, 'json');
    }

    function amMandalList(el)
    {
        var district_id = $(el).val();
        var suffix      = $(el).attr('id').replace('district_id_','');
        $('#mandal_id_'+suffix).html('<option value="">-- Select --</option>');
        if(!district_id) return;
        $.post(BASE_URL + 'ajax/get_mandals', { district_id: district_id, [CSRF_NAME]: CSRF_TOKEN },
            function(res) {
                var html = '<option value="">-- Select --</option>';
                $.each(res, function(i, v) {
                    html += '<option value="'+v.mandal_id+'">'+v.mandal_name+'</option>';
                });
                $('#mandal_id_'+suffix).html(html);
            }, 'json');
    }

    /* ── Edit modal ────────────────────────────────────────────────── */
    function activeMemberEdit(id)
    {
        $.ajax({
            type     : 'POST',
            url      : BASE_URL + 'edit_active_member',
            dataType : 'html',
            data     : { member_id: id, [CSRF_NAME]: CSRF_TOKEN },
            success  : function(data) {
                $('#modal_div').html(data);
                $('#myModalActiveMember').modal('show');
            }
        });
    }

    /* ── Status toggle ─────────────────────────────────────────────── */
    function activeMemberStatus(id)
    {
        $('#status_frm_id_'+id).submit();
    }

    /* ── Client-side validation ────────────────────────────────────── */
    function activeMemberValidationForm(mode)
    {
        var suffix = (mode === 'edit') ? '2' : '1';
        var ok = true;

        ['country_id_','state_id_','district_id_','mandal_id_','designation_id_'].forEach(function(field) {
            var el = $('#'+field+suffix);
            if(!el.val()) {
                el.next('.valid-feedback').text('This field is required.');
                ok = false;
            } else {
                el.next('.valid-feedback').text('');
            }
        });

        var nameEl = $('#member_name_id_'+suffix);
        if(!nameEl.val().trim()) {
            nameEl.next('.valid-feedback').text('Member name is required.');
            ok = false;
        } else {
            nameEl.next('.valid-feedback').text('');
        }

        return ok;
    }
</script>
