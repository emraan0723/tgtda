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
            <h4 class="modal-title text-white" id="info-header-modalLabel">Edit Invoice Sendeing Details</h4>
            <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
                
            <div class="modal-body">
                     <form class="form-material mt-5 novalidate" method="post">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                        <input type="hidden" name="invoice_details_id" value="<?php echo isset($getdata['invoice_details_id']) ? $this->encryption->encrypt($getdata['invoice_details_id']) : '';?>">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                    <div class="form-group ">
                                        
                                        <label >Left Header Name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['tia_left_header_name']) ? stripslashes($getdata['tia_left_header_name']) : '';?>" required="" name="left_header_name"  id="left_header_name_id_1"  class="form-control form-control-line" > 
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group ">
                                        
                                        <label >Right Header Name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo isset($getdata['tia_right_header_name']) ? stripslashes($getdata['tia_right_header_name']) : '';?>" name="right_header_name"  id="right_header_name_id_1"  class="form-control form-control-line" > 
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                 <?php
                            $from_addr =isset($getdata['tia_from_address']) ? $getdata['tia_from_address'] : '';   
                            $from_addr = str_replace('\\r\\n','&#10;', $from_addr);
                            $from_addr = str_replace('\r\n','&#10;', $from_addr);
                            $from_addr = str_replace('\\R\\N','&#10;', $from_addr);
                            $from_addr = str_replace('\R\N','&#10;', $from_addr);
                            $from_addr = str_replace('/\r\\n','&#10;', $from_addr);
                            $from_addr = str_replace('/r/n','&#10;', $from_addr);
                            $from_addr = str_replace('/\R\\N','&#10;', $from_addr);
                            $from_addr = str_replace('/R/N','&#10;', $from_addr);


                             $footer_addr =isset($getdata['tia_footer_address']) ? $getdata['tia_footer_address'] : '';   
                            $footer_addr = str_replace('\\r\\n','&#10;', $footer_addr);
                            $footer_addr = str_replace('\r\n','&#10;', $footer_addr);
                            $footer_addr = str_replace('\\R\\N','&#10;', $footer_addr);
                            $footer_addr = str_replace('\R\N','&#10;', $footer_addr);
                            $footer_addr = str_replace('/\r\\n','&#10;', $footer_addr);
                            $footer_addr = str_replace('/r/n','&#10;', $footer_addr);
                            $footer_addr = str_replace('/\R\\N','&#10;', $footer_addr);
                            $footer_addr = str_replace('/R/N','&#10;', $footer_addr);

                                ?>

                                  <div class="col-sm-12">
                                    <div class="form-group ">
                                        
                                        <label >From Address<span class="text-danger">*</span></label>
                                        <textarea style="height: 150px;"  name="from_addr"  id="from_addr_id_1"  class="form-control form-control-line" ><?php echo stripslashes($from_addr) ;?> </textarea>
                                        <div class="valid-feedback country_name_cls" style="color: red">
                                      </div>
                                     </div>
                                </div>


                                  <div class="col-sm-12">
                                    <div class="form-group ">
                                        
                                        <label >Footer Address<span class="text-danger">*</span></label>
                                        <textarea name="footer_addr"   id="footer_addr_id_1"  class="form-control form-control-line" style="height: 150px;" > <?php echo stripslashes($footer_addr) ;?></textarea>
                                        <div class="valid-feedback country_name_cls" style="color: red">
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

