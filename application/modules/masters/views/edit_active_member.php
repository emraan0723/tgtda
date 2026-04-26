<!-- Edit Active Member Modal -->
<div class="modal fade" id="myModalActiveMember" tabindex="-1" role="dialog"
     aria-labelledby="activeMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="activeMemberModalLabel">Edit Active Member</h4>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="frm_active_member_edit_id" class="form-material mt-3 needs-validation"
                      method="post" onsubmit="return activeMemberValidationForm('edit');">

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>"
                           value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="active_member_id"
                           value="<?php echo isset($getdata['member_id']) ? $this->encryption->encrypt($getdata['member_id']) : ''; ?>">

                    <div class="row">

                        <!-- Country -->
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Select Country <span class="text-danger">*</span></label>
                                <select class="form-control" id="country_id_2"
                                        onchange="StateList(this);" name="country_id">
                                    <option value="">-- Select --</option>
                                    <?php if(isset($country_list) && count($country_list) > 0):
                                          foreach($country_list as $value): ?>
                                    <option value="<?php echo $value['country_id']; ?>"
                                        <?php echo (isset($getdata['country_id']) && $getdata['country_id'] == $value['country_id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo ucwords($value['country_name']); ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Select State <span class="text-danger">*</span></label>
                                <select class="form-control statelist" id="state_id_2"
                                        onchange="DistictsList(this,'<?php echo base_url(); ?>');" name="state_id">
                                    <option value="">-- Select --</option>
                                    <?php if(isset($state_list) && count($state_list) > 0):
                                          foreach($state_list as $value): ?>
                                    <option value="<?php echo $value['state_id']; ?>"
                                        <?php echo (isset($getdata['state_id']) && $getdata['state_id'] == $value['state_id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo ucwords($value['state_name']); ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- District -->
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Select District <span class="text-danger">*</span></label>
                                <select class="form-control distictslist" id="district_id_2"
                                        onchange="MandalList(this,'<?php echo base_url(); ?>');" name="district_id">
                                    <option value="">-- Select --</option>
                                    <?php if(isset($district_list) && count($district_list) > 0):
                                          foreach($district_list as $value): ?>
                                    <option value="<?php echo $value['district_id']; ?>"
                                        <?php echo (isset($getdata['district_id']) && $getdata['district_id'] == $value['district_id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo ucwords($value['district_name']); ?>
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
                                <select class="form-control mandallist" id="mandal_id_2" name="mandal_id">
                                    <option value="">-- Select --</option>
                                    <?php if(isset($mandal_list) && count($mandal_list) > 0):
                                          foreach($mandal_list as $value): ?>
                                    <option value="<?php echo $value['mandal_id']; ?>"
                                        <?php echo (isset($getdata['mandal_id']) && $getdata['mandal_id'] == $value['mandal_id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo ucwords($value['mandal_name']); ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Designation -->
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Select Designation <span class="text-danger">*</span></label>
                                <select class="form-control" id="designation_id_2" name="designation">
                                    <option value="">-- Select --</option>
                                    <?php if(isset($designation_list) && count($designation_list) > 0):
                                          foreach($designation_list as $desig): ?>
                                    <option value="<?php echo $desig; ?>"
                                        <?php echo (isset($getdata['designation']) && $getdata['designation'] == $desig) ? 'selected="selected"' : ''; ?>>
                                        <?php echo $desig; ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                        <!-- Member Name -->
                        <div class="col-sm-12 col-lg-3">
                            <div class="form-group">
                                <label>Active Member Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       value="<?php echo isset($getdata['member_name']) ? htmlspecialchars($getdata['member_name']) : ''; ?>"
                                       maxlength="150" name="member_name" id="member_name_id_2"
                                       class="form-control form-control-line"
                                       placeholder="Enter full name">
                                <div class="valid-feedback" style="color:red;"></div>
                            </div>
                        </div>

                    </div><!-- /row -->

                    <div class="card-body">
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Update</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </form>
            </div><!-- /modal-body -->
        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->
