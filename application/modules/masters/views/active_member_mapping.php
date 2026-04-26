<link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

<style>
/* ── Card hover popup ───────────────────────────────── */
.amm-member-wrap        { position: relative; display: inline-block; }
.amm-info-card          {
    position     : absolute;
    top          : 28px;
    left         : 0;
    z-index      : 9999;
    width        : 360px;
    background   : #fff;
    border-radius: 10px;
    box-shadow   : 0 8px 32px rgba(0,0,0,.22);
    border       : 1px solid #e3eaf3;
    padding      : 0;
    overflow     : hidden;
}
.amm-info-card-header   {
    background  : linear-gradient(135deg,#1565c0,#0288d1);
    padding     : 14px 16px 10px;
    display     : flex;
    align-items : center;
    gap         : 14px;
}
.amm-selfie             {
    width        : 64px;
    height       : 64px;
    border-radius: 50%;
    object-fit   : cover;
    border       : 3px solid #fff;
    box-shadow   : 0 2px 8px rgba(0,0,0,.25);
    flex-shrink  : 0;
}
.amm-header-text h6     { color:#fff; margin:0; font-size:15px; font-weight:700; }
.amm-header-text small  { color:#b3d9f7; font-size:11px; }
.amm-info-card-body     { padding: 10px 14px 12px; }
.amm-info-card-body table td { padding: 4px 6px; font-size: 12px; vertical-align: top; border:none; }
.amm-info-card-body table td:first-child { font-weight:600; color:#455a64; width:110px; }
.amm-member-name        { color:#1565c0 !important; font-weight:600; cursor:pointer; }
.amm-member-name:hover  { text-decoration: underline; }

/* ── Filter card ─────────────────────────────────────── */
.filter-card            {
    background   : #f8fbff;
    border       : 1px solid #dde8f5;
    border-radius: 10px;
    padding      : 18px 20px 10px;
    margin-bottom: 20px;
}
.filter-card .filter-title {
    font-size  : 13px;
    font-weight: 700;
    color      : #1565c0;
    margin-bottom: 12px;
    letter-spacing: .5px;
}

/* ── Add form card ───────────────────────────────────── */
.add-card {
    border-radius: 10px;
    border: 1px solid #1e88e5;
    box-shadow: 0 2px 12px rgba(21,101,192,.07);
}
.add-card .card-header-custom {
    background: linear-gradient(135deg,#1e88e5,#1e88e5);
    border-radius: 10px 10px 0 0;
    padding      : 14px 20px;
    color        : #fff;
    font-weight  : 700;
    font-size    : 15px;
    letter-spacing: .3px;
}

/* ── DataTable badge tweaks ──────────────────────────── */
.badge-success { background:#28a745; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; }
.badge-danger  { background:#dc3545; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; }

/* responsive overflow guard */
#active_member_map_dt_wrapper { overflow-x: auto; }
</style>

<div class="container-fluid">

    <!-- ══════════════════════════════════════════════════
         ADD / EDIT FORM CARD
    ══════════════════════════════════════════════════════ -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card add-card">
                <div class="add-card-header-custom card-header-custom">
                    <i class="ti-user mr-2"></i> Add Active Member Mapping
                </div>
                <div class="card-body pt-4">

                    <?php /* flash messages */ ?>
                    <?php if ($this->session->flashdata('sucess')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ti-check-box mr-2"></i><?php echo $this->session->flashdata('sucess'); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti-alert mr-2"></i><?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('is_exits')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ti-info mr-2"></i><?php echo $this->session->flashdata('is_exits'); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <form id="frm_amm_create" class="form-material needs-validation"
                          method="post" onsubmit="return ammValidateForm('create');">

                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                               value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="mapping_id" id="mapping_id_1" value="">

                        <div class="row">

                            <!-- District -->
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label>Select District <span class="text-danger">*</span></label>
                                    <select class="form-control" id="district_id_1"
                                            name="district_id" onchange="ammMandalList(this,'1');">
                                        <option value="">-- Select District --</option>
                                        <?php
                                        /* Fetch all districts for the create form */
                                        $all_districts = $this->Comman_model->getDistrictList(array());
                                        if (isset($all_districts['query'])):
                                            foreach ($all_districts['query']->result_array() as $d): ?>
                                            <option value="<?php echo $d['district_id']; ?>">
                                                <?php echo ucwords(strtolower($d['district_name'])); ?>
                                            </option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <div class="valid-feedback" style="color:red;"></div>
                                </div>
                            </div>

                            <!-- Mandal -->
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label>Select Mandal <span class="text-danger">*</span></label>
                                    <select class="form-control" id="mandal_id_1"
                                            name="mandal_id" onchange="ammMemberList(this,'1');">
                                        <option value="">-- Select Mandal --</option>
                                    </select>
                                    <div class="valid-feedback" style="color:red;"></div>
                                </div>
                            </div>

                            <!-- Member -->
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label>Select Member <span class="text-danger">*</span></label>
                                    <select class="form-control" id="member_id_1" name="member_id">
                                        <option value="">-- Select Member --</option>
                                    </select>
                                    <div class="valid-feedback" style="color:red;"></div>
                                </div>
                            </div>

                            <!-- Designation -->
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label>Select Designation <span class="text-danger">*</span></label>
                                    <select class="form-control" id="designation_id_1" name="designation">
                                        <option value="">-- Select --</option>
                                        <?php if (isset($designation_list)): foreach ($designation_list as $d): ?>
                                            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <div class="valid-feedback" style="color:red;"></div>
                                </div>
                            </div>

                        </div><!-- /row -->

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-info waves-effect waves-light px-4">
                                    <i class="ti-save mr-1"></i> Save
                                </button>
                                <button type="reset" class="btn btn-dark waves-effect waves-light px-4 ml-2"
                                        onclick="ammResetForm();">
                                    <i class="ti-reload mr-1"></i> Reset
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         FILTER + DATATABLE CARD
    ══════════════════════════════════════════════════════ -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ti-list mr-1"></i> View Active Member Mappings
                    </h6>

                    <!-- FILTERS -->
                    <div class="filter-card">
                        <div class="filter-title"><i class="ti-filter mr-1"></i> Filter Records</div>
                        <div class="row">

                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group mb-2">
                                    <label class="small mb-1">District</label>
                                    <select class="form-control form-control-sm" id="filter_district"
                                            onchange="ammFilterMandalList(this);">
                                        <option value="">All Districts</option>
                                        <?php
                                        if (isset($all_districts['query'])):
                                            foreach ($all_districts['query']->result_array() as $d): ?>
                                            <option value="<?php echo $d['district_id']; ?>">
                                                <?php echo ucwords(strtolower($d['district_name'])); ?>
                                            </option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group mb-2">
                                    <label class="small mb-1">Mandal</label>
                                    <select class="form-control form-control-sm" id="filter_mandal"
                                            onchange="ammFilterMemberList(this);">
                                        <option value="">All Mandals</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-2">
                                <div class="form-group mb-2">
                                    <label class="small mb-1">Member</label>
                                    <select class="form-control form-control-sm" id="filter_member">
                                        <option value="">All Members</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-2">
                                <div class="form-group mb-2">
                                    <label class="small mb-1">Designation</label>
                                    <select class="form-control form-control-sm" id="filter_designation">
                                        <option value="">All</option>
                                        <?php if (isset($designation_list)): foreach ($designation_list as $d): ?>
                                            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-2">
                                <div class="form-group mb-2">
                                    <label class="small mb-1">Status</label>
                                    <select class="form-control form-control-sm" id="filter_status">
                                        <option value="">All</option>
                                        <option value="ACTIVE">ACTIVE</option>
                                        <option value="INACTIVE">INACTIVE</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 text-right mt-1 mb-1">
                                <button class="btn btn-info btn-sm px-3" onclick="ammApplyFilter();">
                                    <i class="ti-search mr-1"></i> Search
                                </button>
                                <button class="btn btn-secondary btn-sm px-3 ml-2" onclick="ammClearFilter();">
                                    <i class="ti-close mr-1"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /FILTERS -->

                    <!-- DATATABLE -->
                    <div class="table-responsive" id="active_member_map_dt_wrapper">
                        <table class="table table-striped table-hover table-bordered no-wrap"
                               id="active_member_map_dt">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width:50px;">SNO</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Mandal</th>
                                <th>Designation</th>
                                <th>Member Name</th>
                                <th style="width:90px;">Status</th>
                                <th style="width:80px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div><!-- /container-fluid -->

<!-- Edit modal placeholder -->
<div id="amm_modal_div"></div>

<script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
var ammTable;
var BASE_URL   = "<?php echo base_url(); ?>";
var CSRF_NAME  = "<?php echo $this->security->get_csrf_token_name(); ?>";
var CSRF_TOKEN = "<?php echo $this->security->get_csrf_hash(); ?>";

/* ═══════════════════════════════════════════════
   DataTable init
═══════════════════════════════════════════════ */
$(document).ready(function () {

    ammTable = $('#active_member_map_dt').DataTable({
        processing  : true,
        serverSide  : true,
        order       : [],
        pageLength  : 10,
        ajax: {
            url  : BASE_URL + 'masters/ActiveMemberMapping/ajax_list',
            type : 'POST',
            data : function (d) {
                d[CSRF_NAME]         = CSRF_TOKEN;
                d.filter_district    = $('#filter_district').val();
                d.filter_mandal      = $('#filter_mandal').val();
                d.filter_member      = $('#filter_member').val();
                d.filter_designation = $('#filter_designation').val();
                d.filter_status      = $('#filter_status').val();
            }
        },
        columnDefs: [
            { targets: [0, 7, 8], orderable: false }
        ],
        aoColumnDefs: [
            { sClass: 'text-center', aTargets: [8] }
        ],
        language: {
            processing: '<div class="spinner-border text-info" role="status"><span class="sr-only">Loading...</span></div>'
        }
    });

    /* hover card toggle – event delegation */
    $(document).on('click', '[data-toggle-card]', function (e) {
        e.stopPropagation();
        var target = $('#' + $(this).data('toggle-card'));
        $('.amm-info-card').not(target).hide();
        target.toggle();
    });
    $(document).on('click', function () { $('.amm-info-card').hide(); });
    $(document).on('click', '.amm-info-card', function (e) { e.stopPropagation(); });
});

/* ═══════════════════════════════════════════════
   Cascading: District → Mandal (create form)
═══════════════════════════════════════════════ */
function ammMandalList(el, suffix)
{
    var district_id = $(el).val();
    $('#mandal_id_' + suffix).html('<option value="">-- Select Mandal --</option>');
    $('#member_id_' + suffix).html('<option value="">-- Select Member --</option>');
    if (!district_id) return;

    $.post(BASE_URL + 'settings/comman/getMandals',
        { district_id: district_id, [CSRF_NAME]: CSRF_TOKEN },
        function (res) {
            var html = '<option value="">-- Select Mandal --</option>';
            /* res is object {mandal_name: mandal_id} from Comman controller */
            if (res && res !== 0) {
                $.each(res, function (name, id) {
                    html += '<option value="' + id + '">' + ucwordsJs(name) + '</option>';
                });
            }
            $('#mandal_id_' + suffix).html(html);
        }, 'json');
}

/* ═══════════════════════════════════════════════
   Cascading: Mandal → Member (create form)
═══════════════════════════════════════════════ */
function ammMemberList(el, suffix)
{
    var mandal_id = $(el).val();
    $('#member_id_' + suffix).html('<option value="">-- Select Member --</option>');
    if (!mandal_id) return;

    $.post(BASE_URL + 'masters/ActiveMemberMapping/getMembersByMandal',
        { mandal_id: mandal_id, [CSRF_NAME]: CSRF_TOKEN },
        function (res) {
            var html = '<option value="">-- Select Member --</option>';
            if (res && res.length > 0) {
                $.each(res, function (i, v) {
                    html += '<option value="' + v.tr_id + '">' + ucwordsJs(v.tr_full_name) + '</option>';
                });
            } else {
                html += '<option value="" disabled>No members found</option>';
            }
            $('#member_id_' + suffix).html(html);
        }, 'json');
}

/* ═══════════════════════════════════════════════
   Filter dropdowns
═══════════════════════════════════════════════ */
function ammFilterMandalList(el)
{
    var district_id = $(el).val();
    $('#filter_mandal').html('<option value="">All Mandals</option>');
    $('#filter_member').html('<option value="">All Members</option>');
    if (!district_id) return;

    $.post(BASE_URL + 'settings/comman/getMandals',
        { district_id: district_id, [CSRF_NAME]: CSRF_TOKEN },
        function (res) {
            var html = '<option value="">All Mandals</option>';
            if (res && res !== 0) {
                $.each(res, function (name, id) {
                    html += '<option value="' + id + '">' + ucwordsJs(name) + '</option>';
                });
            }
            $('#filter_mandal').html(html);
        }, 'json');
}

function ammFilterMemberList(el)
{
    var mandal_id = $(el).val();
    $('#filter_member').html('<option value="">All Members</option>');
    if (!mandal_id) return;

    $.post(BASE_URL + 'masters/ActiveMemberMapping/getMembersByMandal',
        { mandal_id: mandal_id, [CSRF_NAME]: CSRF_TOKEN },
        function (res) {
            var html = '<option value="">All Members</option>';
            if (res && res.length > 0) {
                $.each(res, function (i, v) {
                    html += '<option value="' + v.tr_id + '">' + ucwordsJs(v.tr_full_name) + '</option>';
                });
            }
            $('#filter_member').html(html);
        }, 'json');
}

function ammApplyFilter()  { ammTable.ajax.reload(); }
function ammClearFilter()
{
    $('#filter_district').val('');
    $('#filter_mandal').html('<option value="">All Mandals</option>');
    $('#filter_member').html('<option value="">All Members</option>');
    $('#filter_designation').val('');
    $('#filter_status').val('');
    ammTable.ajax.reload();
}

/* ═══════════════════════════════════════════════
   Edit modal
═══════════════════════════════════════════════ */
function ammEdit(id)
{
    $.ajax({
        type    : 'POST',
        url     : BASE_URL + 'masters/ActiveMemberMapping/editActiveMemberMapping',
        dataType: 'html',
        data    : { mapping_id: id, [CSRF_NAME]: CSRF_TOKEN },
        success : function (html) {
            $('#amm_modal_div').html(html);
            $('#ammEditModal').modal('show');
        }
    });
}

/* ═══════════════════════════════════════════════
   Status toggle
═══════════════════════════════════════════════ */
function ammStatus(id)
{
    $('#amm_status_frm_' + id).submit();
}

/* ═══════════════════════════════════════════════
   Client-side validation
═══════════════════════════════════════════════ */
function ammValidateForm(mode)
{
    var sfx = (mode === 'edit') ? '2' : '1';
    var ok  = true;
    var fields = ['district_id_', 'mandal_id_', 'member_id_', 'designation_id_'];
    $.each(fields, function (i, f) {
        var el = $('#' + f + sfx);
        if (!el.val()) {
            el.next('.valid-feedback').text('This field is required.');
            ok = false;
        } else {
            el.next('.valid-feedback').text('');
        }
    });
    return ok;
}

function ammResetForm()
{
    $('#mandal_id_1').html('<option value="">-- Select Mandal --</option>');
    $('#member_id_1').html('<option value="">-- Select Member --</option>');
}

/* ═══════════════════════════════════════════════
   Utility
═══════════════════════════════════════════════ */
function ucwordsJs(str)
{
    if (!str) return '';
    return str.toLowerCase().replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
}
</script>
