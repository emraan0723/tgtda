 <div  id="cover-spin" role="status">
                                 Loading...
                                </div>
<?php
 if(isset($_SESSION['debug_session']))
    {
      if($_SESSION['debug_session'])
      {
          if (isset($_SESSION['debug_info']))
              $this->output->set_profiler_sections($_SESSION['debug_info']);
          $this->output->enable_profiler(TRUE);
      }
    }

?>

<div id='modal_div'></div>


                            <div>
                            <?php if(validation_errors() !='') 
                            {
                            ?>
                            <button type="button" style="display:none" class="btn btn-danger" data-toggle="modal" data-target="#danger-alert-modal">Error Alert</button>
                            <script>
                            $(function()
                            {
                            $(".btn-danger").click();
                            });
                            </script>
                            <?php
                            }
                            ?> 
                            </div>

                               <!-- Danger Alert Modal -->
                                <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content modal-filled bg-danger">
                                            <div class="modal-body p-4">
                                                <div class="text-center">
                                                    <i class="dripicons-wrong h1"></i>
                                                    <h4 class="mt-2 text-white">Error</h4>
                                                    <p class="mt-3 op-7">
                                                        <?php
                                                        echo validation_errors();
                                                        ?>
                                                    </p>
                                                    <a class="btn btn-light my-2 closepage"
                                                         href="" >Continue</a>
                                                   
                                                </div>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                  
                                </div><!-- /.modal -->


<script type="text/javascript">
    function closepwdIA()
    {
         $("#password_A_I_id").val('');
          $("#reason_A_I_id").val('');
            $('#centermodal').modal('hide');
    }
    function ActiveInactivPasswordRequied()
    {   

        $(".IAPR").remove(); //dyanmic creation password and reason in from
       if($("#password_A_I_id").val()=='' && $("#reason_A_I_id").val()=='')
       {
            $("#password_A_I_id").css("border-bottom","red 1px solid");
            $("#reason_A_I_id").css("border-bottom","red 1px solid");
            $("#password_A_I_id").focus();
            return false;
       }
       else
       if($("#password_A_I_id").val()=='')
       {
             $("#password_A_I_id").css("border-bottom","red 1px solid");
              $("#reason_A_I_id").css("border-bottom","");
              $("#password_A_I_id").focus();
              return false;
       }
       else
       if($("#reason_A_I_id").val()=='')
       {
              $("#reason_A_I_id").css("border-bottom","red 1px solid");
              $("#password_A_I_id").css("border-bottom","");
              $("#reason_A_I_id").focus();
              return false;
       }
       else
       { 
            $("#password_A_I_id").css("border-bottom","");
            $("#reason_A_I_id").css("border-bottom","");
             var from_id =  $("#getformdeatils_id").val();
            
            var password = $("#password_A_I_id").val();
            var reason = $("#reason_A_I_id").val();
            var html_data='';
            html_data +='<input type="hidden" class="IAPR" name="password" value="'+password+'">';
            html_data +='<input type="hidden" class="IAPR"" name="reason" value="'+reason+'">';

            $("#status_frm_id_"+from_id).append(html_data);
          
            var url = "<?php echo base_url();?>"+"settings/comman/ActiveInactiveResons";
            $.ajax({
            type:'POST',
            url: url,
            dataType:"json",
            data:$("#status_frm_id_"+from_id).serializeArray(),
            success: function(data){

                if(data=='Valid')
                {

                    $(".IAPR").remove(); 
                    $( "#status_frm_id_"+from_id ).submit();
                }
                else
                {
                    alert("Invalid password, please try again.");
                }

            }
            });   

       }
    }

</script>
<!-- Center modal content -->
<div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="myCenterModalLabel" >Password</h4>
               
            </div>
            <div class="modal-body" style="margin-top: -47px;">

                           <form class="form-material mt-5 needs-validation">
                            <input type="hidden" id="getformdeatils_id" value="">
                             <div class="row">
                                     <div class="col-sm-12 col-lg-6">
                                    <div class="form-group ">
                                        <label >Plase Enter Password<span class="text-danger">*</span></label>
                                        <input type="password" maxlength="100" name="password_A_I" id="password_A_I_id"  required="required" class="form-control form-control-line"> 
                                         <div class="valid-feedback" style="color: red">
                                      </div>
                                     </div>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group ">
                                        <div class="form-group mb-0">
                                            <label>Reason<span class="text-danger">*</span></label>
                                       <textarea required="required" name="reason_A_I" id="reason_A_I_id" class="form-control" rows="5"></textarea>
                                       
                                    </div>
                                      </div>
                                     </div>

                                      <div class="card-body">
                                    <div class="form-group mb-0 text-right">
                                        <button type="button" onclick="ActiveInactivPasswordRequied()" class="btn btn-info waves-effect waves-light">Save</button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                </div>
                            
                            </div>

                            </form>
             
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  
<script src="<?php echo base_url();?>assets/libs/moment/moment.js"></script>
    <script src="<?php echo base_url();?>assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
      
       $.fn.datepicker.defaults.format = "dd-mm-yyyy";

   
$(".datepicker").datepicker({ 
    startDate: "today" ,
     todayHighlight: true
});
    // Date Picker
    jQuery('.mydatepicker, #datepicker, .input-group.date').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });

    </script>
<script type="text/javascript">
    var isexits = "<?php echo $this->session->flashdata('is_exits'); ?>";
    var sucess_msg = "<?php echo $this->session->flashdata('sucess'); ?>";
    var error_msg = "<?php echo $this->session->flashdata('error'); ?>";

    if(isexits !='')
    {
        $(".alert_digi_msgs").show();
        $(".alert_digi_msgs").attr('style','background-color:#ffb22b !important;padding:4px;');
        $(".alert_digi_msgs").html(isexits);
         setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 3000);
    }
    if(sucess_msg !='')
    {
          $(".alert_digi_msgs").show();
        $(".alert_digi_msgs").attr('style','background-color:#3ebd09de !important;padding:4px;');
        $(".alert_digi_msgs").html(sucess_msg);
       setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 3000);
    }

    if(error_msg !='')
    {
          $(".alert_digi_msgs").show();
        $(".alert_digi_msgs").attr('style','background-color:#fc4b6c !important;padding:4px;');
        $(".alert_digi_msgs").html(error_msg);
       setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 3000);
    }



  $(document).ajaxSuccess(function(e,x) {

  
     /* try{
        var result = $.parseJSON(x.responseText);
        console.log(result);
        if(result.digipayemts_csrf_code != undefined)
        $('input:hidden[name="csrf_digitarural"]').val(result.dreh_token_val);
        $.ajaxSetup({data: {csrf_digitarural: result.dreh_token_val}});
      } catch(e) {
        console.log("JSON parse error, this is not json");
    }*/

  });
        
    
</script>

 <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                 © <?php echo date('Y') ?> - Powered by TGTDA
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
  
    <div class="chat-windows"></div>
    

</body>

</html>