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
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit Currency</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
                
            <div class="modal-body">
                    <form  class="form-material mt-5 needs-validation" method="post" onsubmit="return  currencyValidationForm('edit');">

                              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                        <input type="hidden" name="currency_id" value="<?php echo isset($getdata['currency_id']) ? $this->encryption->encrypt($getdata['currency_id']) : '';?>">

                            <div class="row">
                                 <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" value="<?php echo isset($getdata['currency_name']) ? $getdata['currency_name'] : '';?>" name="currency_name" id="currency_name_id_2"  class="form-control form-control-line alpha_s"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>


                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Short Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" value="<?php echo isset($getdata['tcm_currency_short_name']) ? $getdata['tcm_currency_short_name'] : '';?>" name="currency_short_name" id="currency_short_name_2"  class="form-control form-control-line alpha_s"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>


                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Currency Symbol</label>
                                        <input type="text" maxlength="5" value="<?php echo isset($getdata['currency_symbol']) ? $getdata['currency_symbol'] : '';?>" name="currency_symbol" id="currency_symbol_id_2"  class="form-control form-control-line"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                  
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-3">
                                   <div class="card-body">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <a href="" class="btn btn-dark waves-effect waves-light">Reset</a>
                                        
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/currency_validations.js?v1=<?php echo rand(); ?>"></script>