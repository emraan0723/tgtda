
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== bv-->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <!-- Row -->

                           <form class="form-material mt-5 needs-validation" method="post" onsubmit="return  adminValidateForm('create')">
                             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label for="first_name_id">First Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="first_name" id="first_name_id_1"  class="form-control form-control-line alpha_s"> 
                                     <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" maxlength="100" name="last_name" id="last_name_id_1"  class="form-control form-control-line alpha_s"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                     <div class="row">
                                       <div class="col-sm-12 col-lg-12">
                                    <div class="form-group ">
                                        <label>Gender <span class="text-danger">*</span></label>

                                       <select name="gender" class="form-control form-control-line" id="gender_1">
                                            <option value=""></option>
                                           <option value="Male">Male</option>
                                            <option value="Female">Female</option>
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
                                        <input type="text" maxlength="10" name="mobile" id="mobile_id_1"  class="form-control form-control-line mobilevalidation"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                     
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email_id_1" maxlength="255" class="emailvalidation form-control form-control-line"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                      
                                </div>

                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <textarea name="address" id="address_id_1" class="form-control" rows="5"></textarea>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                      
                                </div>
                                


                                
                            </div> 

                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Username<span class="text-danger">*</span></label>
                                        <input type="text" name="username" id="username_id_1"  class="form-control form-control-line usernamevalidation"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>
                                
                                  <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="Password" name="password" id="password_id_1"  class="form-control form-control-line passwordvalidation"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                  </div>

                               
                                

                                
                            </div>
                               <div class="card-body">
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                   
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/admin_validations.js?v1=<?php echo rand(); ?>"></script>
 