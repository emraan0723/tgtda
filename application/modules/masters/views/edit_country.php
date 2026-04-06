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
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit Country</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
                
            <div class="modal-body">
                     <form class="form-material mt-5 novalidate" method="post"  onsubmit="return  countryValidationForm('edit');">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                        <input type="hidden" name="country_id" value="<?php echo isset($getdata['country_id']) ? $this->encryption->encrypt($getdata['country_id']) : '';?>">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Country Name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['country_name']) ? $getdata['country_name'] : '';?>" name="country_name" id="country_name_id_2"  class="form-control form-control-line"> 
                                      <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Country Code </label>
                                        <input type="text" name="country_code" value="<?php echo isset($getdata['tc_country_code']) ? $getdata['tc_country_code'] : '';?>" id="country_code_id_2"  class="form-control form-control-line"> 
                                     <div class="valid-feedback country_code_cls" style="color: red">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/country_validations.js?v1=<?php echo rand(); ?>"></script>
