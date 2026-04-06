<!--  Modal content for the above example -->
<?php
/*print"<pre>";
print_r($getdata);
exit;*/
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header modal-colored-header bg-info">
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit State</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
                
            <div class="modal-body">
                    <form class="form-material mt-5 needs-validation" autocomplete="OFF" method="post" onsubmit="return  stateValidationForm('edit');">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                        <input type="hidden" name="state_id" value="<?php echo isset($getdata['state_id']) ? $this->encryption->encrypt($getdata['state_id']) : '';?>">
                            <div class="row">
                                 <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control" id="country_id_2" name="country_id">
                                                <option value=""></option>
                                                 <?php
                                                if(isset($country_list) && count($country_list) > 0)
                                                {
                                                    foreach ($country_list as $key => $value) 
                                                    { 
                                                      
                                                    ?>
                                                    <option value="<?php echo isset($value['country_id']) ? $value['country_id'] : 0; ?>" <?php echo isset($getdata['country_id']) && $getdata['country_id'] ==$value['country_id']  ? 'selected="selected"':''; ?>><?php echo isset($value['country_name']) ? ucwords($value['country_name']) : ''; ?></option>
                                                    <?php
                                                    }
                                                } 
                                                ?>
                                                
                                            </select>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>State Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="state_name" id="state_name_id_2"  class="form-control form-control-line alpha_s" value="<?php echo isset($getdata['state_name']) ? $getdata['state_name'] : '';?>"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-2">
                                    <div class="form-group ">
                                        
                                        <label>State Code </label>
                                        <input type="text" maxlength="5" value="<?php echo isset($getdata['ts_state_code']) ? $getdata['ts_state_code'] : '';?>" name="state_code" id="state_code_id_2"  class="form-control form-control-line alpha"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-3">
                                   <div class="card-body">
                                    <div class="form-group mb-0">
                                         <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        
                                    </div>
                                </div>
                                </div>
                                
                                

                                
                            </div>
                              
                            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
   <script src="<?php echo base_url();?>assets/digi/digi_validations.js?v1=<?php echo rand(); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/state_validations.js?v1=<?php echo rand(); ?>"></script>