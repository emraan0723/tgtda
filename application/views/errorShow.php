
    
  
    


<div class="error" style="padding:20px">
    
    <?php if($this->session->flashdata('php_error')){ ?> 
    
    <div class="alert alert-info" style="padding-top:0px;">
         <p align="center"><?php echo $this->session->flashdata('php_error'); ?></p><br/>
    </div>
    
  <?php } ?>
    
    <h1>  Operation failed due to technical issues <br>
    Please contact your technical person </h1>
</div>

