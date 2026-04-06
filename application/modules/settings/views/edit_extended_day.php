<!--  Modal content for the above example -->
<?php
/*print"<pre>";
print_r($getdata);
exit;*/
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Edit Extended day </h4>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <form class="form-material mt-5 needs-validation" autocomplete="OFF" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="extended_id" value="<?php echo isset($getdata['tcpp_id']) ? $this->encryption->encrypt($getdata['tcpp_id']) : ''; ?>">
                    <div class="row">


                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group ">

                                <label>Extended Day
                                    <span class="text-danger">*</span></label>
                                <input type="text" maxlength="2" name="extended_day" id="extended_day" class="form-control form-control-line num" value="<?php echo isset($getdata['tccp_extended_day']) ? $getdata['tccp_extended_day'] : ''; ?>">
                                <div class="valid-feedback" style="color: red"></div>

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
<script src="<?php echo base_url(); ?>assets/digi/digi_validations.js?v1=<?php echo rand(); ?>"></script>
