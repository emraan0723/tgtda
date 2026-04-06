<style type="text/css">
    

/*.profile-pic {
    max-width: 200px;
    max-height: 200px;
    display: block;
}*/

.file-upload {
    display: none;
}
.circle {
    border-radius: 1000px !important;
    overflow: hidden;
    width: 128px;
    height: 128px;
    border: 8px solid rgba(255, 255, 255, 0.7);
    position: absolute;
    top: 72px;
}
img {
    max-width: 100%;
    height: auto;
}
.p-image {
    cursor: pointer;
    position: absolute;
    top: 151px;
    right: 118px;
    color: #666666a6;
    transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
}
.p-image:hover {
  transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
}
.upload-button {
  font-size: 1.2em;
}

.upload-button:hover {
  transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
  color: #999;
}
</style>
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->

                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <form class="form-horizontal" id="form_profile_pic_id"  method="post" action="<?php echo base_url().'settings/comman/UploadProfilePic'; ?>" enctype="multipart/form-data">

                                     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                                     <input type="hidden" name="pic_id" id="pic_id" value="<?php echo isset($userdeatils['admin_id']) ? $this->encryption->encrypt($userdeatils['admin_id']) : '';?>">
                                        <input type="hidden" name="from_source"  value="admin">

                                            <?php
                                            if(isset($userdeatils['tupp_source']) && $userdeatils['tupp_source'] ='admin' && isset($userdeatils['tupp_filename']) && $userdeatils['tupp_filename'] !='')
                                            {
                                                $imagehtml = base_url().'profile_images/admin/'.$userdeatils['tupp_filename'];

                                            }

                                            ?>
                                <center class="mt-4"> <img src="<?php echo $imagehtml; ?>" class="rounded-circle profile-pic fa fa-camera upload-button" style="cursor: pointer;" width="150" title="Click Upload Pic" />

    
                                 <div class="p-image">
                                   <i class="fa fa-camera upload-button"></i>
                                    <input class="file-upload"  name="uploaded_file" value="" type="file"  accept="image/*"/>
                                 </div>

                                    <h4 class="card-title mt-2"><?php echo isset($userdeatils['admin_name']) ? $userdeatils['admin_name'] : '';?></h4>
                                    
                                   
                                </center>
                               </form>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body"> <small class="text-muted">Email address </small>
                                <h6><?php echo isset($userdeatils['tu_email']) ? $userdeatils['tu_email'] : '';?></h6> <small class="text-muted pt-4 db">Mobile</small>
                                <h6><?php echo isset($userdeatils['tu_mobile']) ? $userdeatils['tu_mobile'] : '';?></h6> <small class="text-muted pt-4 db">Address</small>

                               <?php
                                        $address =  isset($userdeatils['tu_address']) ? $userdeatils['tu_address'] : '';

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
                                <h6><?php echo $address?></h6>
                                
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Tabs -->
                            <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                                
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false">Change Password</a>
                                </li>
                            </ul>
                            <!-- Tabs -->
                            <div class="tab-content" id="pills-tabContent">
                               
                                <div class="tab-pane fade show active" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="card-body">
                                          <form action="<?php echo base_url().'admin/profile/userProfile'; ?>" class="form-horizontal form-material mt-5 needs-validation" method="post" >

                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                               <input type="hidden" name="admin_id" value="<?php echo isset($userdeatils['admin_id']) ? $this->encryption->encrypt($userdeatils['admin_id']) : '';?>">
                                       
                                            <div class="form-group">
                                                <label class="col-md-12">First Name <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                     <input type="text" value="<?php echo isset($userdeatils['tu_first_name']) ? $userdeatils['tu_first_name'] : '';?>" name="first_name" id="first_name_id_1"  class="form-control form-control-line alpha"> 
                                                <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="col-md-12">Last Name <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                   <input type="text" value="<?php echo isset($userdeatils['tu_last_name']) ? $userdeatils['tu_last_name'] : '';?>" name="last_name" id="last_name_id_1"  class="form-control form-control-line alpha"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="col-sm-12">Gender<span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                   <select name="gender" class="form-control form-control-line" id="gender_1">
                                            <option value="" ></option>
                                           <option value="Male" <?php echo isset($userdeatils['tu_gender']) && $userdeatils['tu_gender']=='Male' ? 'selected=selected' : '';?>>Male</option>
                                            <option value="Female" <?php echo isset($userdeatils['tu_gender']) && $userdeatils['tu_gender']=='Female' ? 'selected=selected' : '';?>>Female</option>
                                       </select>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="example-email" class="col-md-12">Email<span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" value="<?php echo isset($userdeatils['tu_email']) ? $userdeatils['tu_email'] : '';?>" name="email" id="email_id_1"  class="emailvalidation form-control form-control-line"> 
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-12">Mobile No<span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="mobile" value="<?php echo isset($userdeatils['tu_mobile']) ? $userdeatils['tu_mobile'] : '';?>" id="mobile_id_1"  class="form-control form-control-line mobilevalidation"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                            <?php
                            $address =isset($userdeatils['tu_address']) ? $userdeatils['tu_address'] : '';   
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

                                            <div class="form-group">
                                                <label class="col-md-12">Address</label>
                                                <div class="col-md-12">
                                                    <textarea name="address"  id="address_id_1" class="form-control" rows="5"><?php echo $address;?></textarea>
                                        <div class="valid-feedback" style="color: red">
                                      </div>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group">
                                                <div class="col-sm-12">

                                                    <button type="submit" class="btn btn-success">Update Profile</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                                    <div class="card-body">
                                         <form action="<?php echo base_url().'admin/profile/changePassword'; ?>" class="form-horizontal form-material mt-5 needs-validation" method="post" onsubmit="return CPValidationsFrom();">

                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                               <input type="hidden" name="admin_id" value="<?php echo isset($userdeatils['admin_id']) ? $this->encryption->encrypt($userdeatils['admin_id']) : '';?>">

                                            <div class="form-group">
                                                <label class="col-md-12">Current Password<span class="text-danger">*</span><span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="password" value="" id="old_password_id" onblur="PwdChecking(this)" placeholder="*******" class="form-control form-control-line">
                                                    <div  id="valid_id" class="valid-feedback" style="color: red">
                                                    
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label for="example-email" class="col-md-12">New Password<span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="password" value="" name="password" id="new_password_id" placeholder="*******" class="form-control form-control-line" name="example-email" id="example-email">
                                                     <div class="valid-feedback" style="color: red">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Confirm Password<span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="password" value="" name="conf_password" id="conf_password_id" placeholder="*******" class="form-control form-control-line">
                                                     <div class="valid-feedback" style="color: red">
                                                </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-success">Update Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
           <script type="text/javascript">
               $(document).ready(function() {

    
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    

    $(".file-upload").on('change', function(){
        $("#form_profile_pic_id").submit();
        readURL(this);
         
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();



       
    });
});
           </script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/digi/js/admin_validations.js?v1=<?php echo rand(); ?>"></script>

<script>
  function CPValidationsFrom()
  {
      if($("#old_password_id").val() =='')
      {
          $("#old_password_id").css("border-bottom","2px solid red");
          $("#old_password_id").focus();
            return false;
      }
      else
      {
        $("#old_password_id").css("border-bottom","");
      }

      if($("#new_password_id").val() =='')
      {
           $("#valid_id").html("");
          $("#new_password_id").css("border-bottom","2px solid red");
          $("#new_password_id").focus();
        return false;
      }
      else
      {
        $("#valid_id").html("");
        $("#new_password_id").css("border-bottom","");
      }

      
        var re = /^(?=.*[a-z])[A-Za-z0-9\d=!\-@._*]+$/.test($("#new_password_id").val());
        if($("#new_password_id").val() !='' && (!re)) 
        {   
           
            $("#new_password_id").val("");
            $("#new_password_id").focus();
            $("#new_password_id").css('border-bottom',"red 2px solid");
            $("#new_password_id+div").html("Please Enter Password Alpha Numeric & Caps & Small lettes & Special (!\-@._*) Characters , EX - Bv@1 ").show();
             return false;
        } 
        else
        {
                $("#new_password_id").css('border-bottom',"");
                $("#new_password_id+div").hide();
                      
        }


      if($("#conf_password_id").val() =='')
      {
        
          $("#valid_id").html("");
          $("#conf_password_id").css("border-bottom","2px solid red");
          $("#conf_password_id").focus();
         return false;
      }
      else
      {
        $("#valid_id").html("");
        $("#conf_password_id").css("border-bottom","");
      }


        var re = /^(?=.*[a-z])[A-Za-z0-9\d=!\-@._*]+$/.test($("#conf_password_id").val());
        if(($("#conf_password_id").val() !='') && (!re)) 
        {   
            $("#conf_password_id").val("");
            $("#conf_password_id").focus();
            $("#conf_password_id").css('border-bottom',"red 2px solid");
            $("#conf_password_id+div").html("Please Enter Password Alpha Numeric & Caps & Small lettes & Special (!\-@._*) Characters , EX - Bv@1 ").show();
            return false;
        } 
        else
        {
                $("#conf_password_id").css('border-bottom',"");
                $("#conf_password_id+div").hide();
                      
        }



       if($("#new_password_id").val() !=$("#conf_password_id").val())
      {   
           $("#conf_password_id").val("");
          $("#conf_password_id").css("border-bottom","2px solid red");
          $("#conf_password_id").focus();
          $("#conf_password_id + div").html("Password Not Matched, Please Try Again").show();
         return false;
      }
      else
      {
        $("#conf_password_id + div").html('').hide();
        $("#conf_password_id").css("border-bottom","");
      }

   
  }

  function PwdChecking(e)
  {
    var password = e.value;
    var url = "<?php echo base_url();?>"+"admin/profile/ChangePasswordChecking";         
    $.ajax({
    type:'POST',
    url: url,
    data:{'userp':password,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
    success: function(data)
    {

      if(data =='NotValid')
      {
        $("#old_password_id").val("");
        $("#old_password_id").css("border-bottom","2px solid red");
        $("#old_password_id").focus();
        $("#old_password_id + div ").html("Please Enter Valid Password").show();
        return false;
       
      }
      else
      {
         $("#old_password_id + div ").html("").hide();
        $("#old_password_id").css("border-bottom","");
      }

    }

  });

    
  }
</script>          