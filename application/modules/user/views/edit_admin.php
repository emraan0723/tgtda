<?php
/*print"<pre>";
print_r($getdata);
exit;*/
?>
<!--  Modal content for the above example -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header modal-colored-header bg-info">
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit Admin</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
       
            <div class="modal-body">
                     <form autocomplete="off" action="<?php echo base_url().'admin/admin/CreateAdmins'; ?>" class="form-material mt-5 needs-validation" method="post" onsubmit="return  adminValidateForm('edit')">
                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                               <input type="hidden" name="admin_id" value="<?php echo isset($getdata['admin_id']) ? $this->encryption->encrypt($getdata['admin_id']) : '';?>">
                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label for="first_name_id">First Name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['tu_first_name']) ? $getdata['tu_first_name'] : '';?>" name="first_name" id="first_name_id_2"  class="form-control form-control-line alpha_s"> 
                                     <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['tu_last_name']) ? $getdata['tu_last_name'] : '';?>" name="last_name" id="last_name_id_2"  class="form-control form-control-line alpha_s"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                     <div class="row">
                                       <div class="col-sm-12 col-lg-12">
                                    <div class="form-group ">
                                        <label>Gender <span class="text-danger">*</span></label>

                                       <select name="gender" class="form-control form-control-line" id="gender_2">
                                            <option value="" ></option>
                                           <option value="Male" <?php echo isset($getdata['tu_gender']) && $getdata['tu_gender']=='Male' ? 'selected=selected' : '';?>>Male</option>
                                            <option value="Female" <?php echo isset($getdata['tu_gender']) && $getdata['tu_gender']=='Female' ? 'selected=selected' : '';?>>Female</option>
                                       </select>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                        </div>
                                 </div>
                             </div>

                             <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Mobile <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="10" name="mobile" value="<?php echo isset($getdata['tu_mobile']) ? $getdata['tu_mobile'] : '';?>" id="mobile_id_2"  class="form-control form-control-line mobilevalidation"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                     
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['tu_email']) ? $getdata['tu_email'] : '';?>" name="email" id="email_id_2"  class="emailvalidation form-control form-control-line"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                       
                                </div>

                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <?php
                                        $address =  isset($getdata['tu_address']) ? $getdata['tu_address'] : '';
                                        $address = str_replace('\\r\\n','&#10;', $address);
                                        $address = str_replace('\r\n','&#10;', $address);
                                        $address = str_replace('\\R\\N','&#10;', $address);
                                        $address = str_replace('\R\N','&#10;', $address);
                                        $address = str_replace('/\r\\n','&#10;', $address);
                                        $address = str_replace('/r/n','&#10;', $address);
                                        $address = str_replace('/\R\\N','&#10;', $address);
                                        $address = str_replace('/R/N','&#10;', $address);
                                          $address = stripslashes($address);
                                            
                                        ?>

                                        <textarea name="address"   id="address_id_2" class="form-control" rows="5"><?php echo $address;?></textarea>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                      
                                </div>
                                


                                
                            </div> 

                           
                               <div class="card-body">
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <!-- <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button> -->
                                    </div>
                                </div>

                            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="<?php echo base_url();?>assets/digi/digi_validations.js?v1=<?php echo rand(); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/admin_validations.js?v1=<?php echo rand(); ?>"></script>
 