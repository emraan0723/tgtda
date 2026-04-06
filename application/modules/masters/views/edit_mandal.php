<!--  Modal content for the above example -->
<?php
/*print"<pre>";
print_r($district_list);
exit;*/
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header modal-colored-header bg-info">
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit City</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
                
            <div class="modal-body">
                     <form id="frm_state_create_id" class="form-material mt-5 needs-validation" method="post" onsubmit="return  manadalValidationForm('edit');">
                         <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                         <input type="hidden" name="manadal_id" value="<?php echo isset($getdata['manadal_id']) ? $this->encryption->encrypt($getdata['manadal_id']) : '';?>">
                            <div class="row">

                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control" id="country_id_2" onchange="StateList(this,'<?php echo base_url(); ?>');" name="country_id">
                                                <option value=""></option>
                                                <?php
                                                if(isset($country_list) && count($country_list) > 0)
                                                {
                                                    foreach ($country_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['country_id']) ? $value['country_id'] : 0; ?>"   <?php echo isset($getdata['country_id']) && $getdata['country_id'] ==$value['country_id']  ? 'selected="selected"':''; ?>><?php echo isset($value['country_name']) ? ucwords($value['country_name']) : ''; ?></option>
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
                                            <select class="form-control statelist" onchange="DistictsList(this,'<?php echo base_url(); ?>');" id="state_id_2" name="state_id">
                                                 <option value=""></option>
                                               <?php
                                                if(isset($state_list) && count($state_list) > 0)
                                                {
                                                    foreach ($state_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['state_id']) ? $value['state_id'] : 0; ?>"   <?php echo isset($getdata['state_id']) && $getdata['state_id'] ==$value['state_id']  ? 'selected="selected"':''; ?>><?php echo isset($value['state_name']) ? ucwords($value['state_name']) : ''; ?></option>
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
                                        <label>Select Disticts </label>
                                            <select class="form-control distictslist"  id="disticts_id_2" name="district_id">
                                                <option value=""></option>
                                              <?php
                                                if(isset($district_list) && count($district_list) > 0)
                                                {
                                                    foreach ($district_list as $key => $value) 
                                                    { 
                                                       
                                                    ?>
                                                    <option value="<?php echo isset($value['district_id']) ? $value['district_id'] : 0; ?>"   <?php echo isset($getdata['district_id']) && $getdata['district_id'] ==$value['district_id']  ? 'selected="selected"':''; ?>><?php echo isset($value['district_name']) ? ucwords($value['district_name']) : ''; ?></option>
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
                                        <label>Manadal Name  <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['manadal_name']) ? $getdata['manadal_name'] : '';?>" maxlength="100" name="manadal_name" id="manadal_name_id_2"  class="form-control form-control-line alpha_s">
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>
                            </div>
                            <div class="card-body">
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <button type="button" class="btn btn-light"
                    data-dismiss="modal">Close</button>
                                        
                                    </div>
                                </div>
                              
                            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="<?php echo base_url();?>assets/digi/digi_validations.js?v1=<?php echo rand(); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/manadal_validations.js?v1=<?php echo rand(); ?>"></script>
