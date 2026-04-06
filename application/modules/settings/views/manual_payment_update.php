
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
         
            <div class="container-fluid">

                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                           
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>

                           <form class="form-material mt-5 novalidate" id="manual_update_frm" method="post">
                               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                            <div class="row">

                                 <div class="col-sm-12 col-lg-4">
                                <div class="form-group ">
                                         <label>Invoice Date</label>
                                          <div class="input-group">
                                                <input type="text" name="invoice_date" readonly="readonly" class="form-control invoice_date" id="datepicker-autoclose" placeholder="dd-mm-yyyy">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                          
                                     </div>
                                 </div>
                                                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group ">
                                        <label>Search Customer <span class="text-danger">*</span></label>
                                          <input type="hidden" value="" name="customer_id" id="customer_id">
                                          <input type="hidden" value="" name="customer_code" id="customer_code">
                                             <input type="text" required="required" placeholder="Please Type Customer Name"  name="customer_name" id="customer_name_id"   class="form-control form-control-line searchcustomer"> 
                                               
                                    </div>
                                    <div id="suggesstion-box"></div>
                                </div>


                                
                             
                                </div>

                               
                              
                                <div class="product_assign">
                                </div>
                                  
                                
                                <div class="row">
                                    <div class="col-sm-12 pull-right mt-4 text-right">
                                        <div class="card-body">
                                          <div class="form-group mb-0">
                                            <button  type="button" onclick="frmSubmit()" class="btn btn-info waves-effect waves-light">Manual Update</button>
                                            <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button>
                                           </div>
                                        </div>
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

<script type="text/javascript">

    function frmSubmit()
    {
        
        if($(".invoice_date").val() =='')
        {
            $(".invoice_date").css("border-bottom","red 1px solid");
            $(".invoice_date").val("").focus();
            return false;
        }
        else
        if($("#customer_name_id").val() =='')
        {
            $("#customer_name_id").css("border-bottom","red 1px solid");
            $("#customer_name_id").val("").focus();
            return false;
        }
        else
        {
            if (confirm('Are you sure ?')) 
            {
                $("#manual_update_frm").submit();
            }

          
        }
    }

    
    
</script>