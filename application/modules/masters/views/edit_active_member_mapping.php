<!-- Edit Active Member Mapping Modal -->
<div class="modal fade" id="ammEditModal" tabindex="-1" role="dialog"
     aria-labelledby="ammEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:10px;overflow:hidden;">

            <div class="modal-header" style="background:linear-gradient(135deg,#1565c0,#0288d1);padding:16px 22px;">
                <h5 class="modal-title text-white" id="ammEditModalLabel">
                    <i class="ti-pencil-alt mr-2"></i> Edit Active Member Mapping
                </h5>
                <button type="button" class="close ml-auto" data-dismiss="modal"
                        aria-hidden="true" style="color:#fff;opacity:1;">&times;</button>
            </div>

            <div class="modal-body" style="background:#f8fbff;padding:24px;">
                <form id="frm_amm_edit" class="form-material needs-validation"
                      method="post"
                      action="<?php echo base_url(); ?>masters/ActiveMemberMapping/ActiveMemberMapping"
                      onsubmit="return ammValidateForm('edit');">

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="mapping_id" id="mapping_id_2"
                           value="<?php echo isset($getdata['tamm_id']) ? $this->encryption->encrypt($getdata['tamm_id']) : ''; ?>">

                    <div class="row">

                        <!-- District -->
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label>Select District <span class="text-danger">*</span></label>
                                <select class="form-control" id="district_id_2"
                                        name="district_id"
                                        onchange="ammMandalList(this,'2');">
                                    <option value="">-- Select District --</option>
                                    <?php if (isset($district_list) && count($district_list) > 0):
                                          foreach ($district_list as $d): ?>
                                        <option value="<?php echo $d['district_id']; ?>"
                                            <?php echo (isset($getdata['tamm_district_id']) && $getdata['tamm_district_id'] == $d['district_id']) ? 'selected' : ''; ?>>
                                            <?php echo ucwords(strtolower($d['district_name'])); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Mandal -->
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label>Select Mandal <span class="text-danger">*</span></label>
                                <select class="form-control" id="mandal_id_2"
                                        name="mandal_id"
                                        onchange="ammMemberList(this,'2');">
                                    <option value="">-- Select Mandal --</option>
                                    <?php if (isset($mandal_list) && count($mandal_list) > 0):
                                          foreach ($mandal_list as $m): ?>
                                        <option value="<?php echo $m['mandal_id']; ?>"
                                            <?php echo (isset($getdata['tamm_mandal_id']) && $getdata['tamm_mandal_id'] == $m['mandal_id']) ? 'selected' : ''; ?>>
                                            <?php echo ucwords(strtolower($m['mandal_name'])); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Member -->
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label>Select Member <span class="text-danger">*</span></label>
                                <select class="form-control" id="member_id_2" name="member_id">
                                    <option value="">-- Select Member --</option>
                                    <?php if (isset($member_list) && count($member_list) > 0):
                                          foreach ($member_list as $mem): ?>
                                        <option value="<?php echo $mem['tr_id']; ?>"
                                            <?php echo (isset($getdata['tamm_active_member_id']) && $getdata['tamm_active_member_id'] == $mem['tr_id']) ? 'selected' : ''; ?>>
                                            <?php echo ucwords(strtolower($mem['tr_full_name'])); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Designation -->
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label>Select Designation <span class="text-danger">*</span></label>
                                <select class="form-control" id="designation_id_2" name="designation">
                                    <option value="">-- Select --</option>
                                    <?php if (isset($designation_list) && count($designation_list) > 0):
                                          foreach ($designation_list as $d): ?>
                                        <option value="<?php echo $d; ?>"
                                            <?php echo (isset($getdata['tamm_designation']) && $getdata['tamm_designation'] == $d) ? 'selected' : ''; ?>>
                                            <?php echo $d; ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Current member info card (read-only) -->
                        <?php if (isset($getdata['tr_full_name']) && $getdata['tr_full_name']): ?>
                        <div class="col-sm-12 col-lg-8">
                            <div class="card border-info mb-0" style="border-radius:8px;">
                                <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                                    <?php $photo = base_url().'uploads/registration/'.$getdata['tr_reg_key'].'/'.($getdata['tr_selfie'] ? $getdata['tr_selfie'] : 'default.png'); ?>
                                    <img src="<?php echo $photo; ?>"
                                         onerror="this.src='<?php echo base_url(); ?>assets/images/default-avatar.png'"
                                         style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #0288d1;margin-right:14px;">
                                    <div>
                                        <strong><?php echo ucwords(strtolower($getdata['tr_full_name'])); ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($getdata['tr_mobile']); ?> &nbsp;|&nbsp;
                                            <?php echo htmlspecialchars($getdata['tr_email']); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div><!-- /row -->

                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-info waves-effect waves-light px-4">
                            <i class="ti-save mr-1"></i> Update
                        </button>
                        <button type="button" class="btn btn-light px-4 ml-2" data-dismiss="modal">
                            <i class="ti-close mr-1"></i> Close
                        </button>
                    </div>

                </form>
            </div><!-- /modal-body -->

        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->
