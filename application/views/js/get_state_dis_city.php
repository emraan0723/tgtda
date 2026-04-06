<script >
function StateList(country_id)
{ 
   

     cityList(0,country_id);

    $(".statelist").find('option').not(':first').remove();
    var country_id = country_id.value;
    if(country_id > 0)
    {
      if(country_id =='101')
      {
        $(".country_cls").show();
         
      }
      else
      {
         $(".country_cls").hide();
      }
      
        var url = "<?php echo base_url(); ?>settings/comman/getStates";

        $.ajax({
          type:'POST',
          url: url,
          data:{'country_id':country_id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
          dataType:'json',
          success: function(data)
          {
            if(data !=0 && data !='')
            {
                    $.each(data,function(idx,obj)
                    {
                        $(".statelist").append($('<option>',
                        {
                            value:obj,
                            text:idx    
                            
                        }));    
                    }); 
             }
             else
             {
                 $(".statelist").find('option').not(':first').remove(); 
             }

           }
        });
    }

    DistictsList(0,'');

}

function DistictsList(state_id)
{ 
   $(".distictslist").find('option').not(':first').remove();
    var state_id = state_id.value;
    if(state_id > 0)
    {
        var url = "<?php echo base_url(); ?>settings/comman/getDisticts";
        $.ajax({
          type:'POST',
          url: url,
          data:{'state_id':state_id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
          dataType:'json',
          success: function(data)
          {
            if(data !=0 && data !='')
            {
                    $.each(data,function(idx,obj)
                    {
                        $(".distictslist").append($('<option>',
                        {
                            value:obj,
                            text:idx    
                            
                        }));    
                    }); 
             }
             else
             {
                 $(".distictslist").find('option').not(':first').remove(); 
             }

           }
        });
     }
     cityList(0,0);
   
}

function cityList(district_id,country_id)
{ 
   $(".cityslist").find('option').not(':first').remove();
    var district_id = district_id.value;
    if(country_id !=0)
    {
         var country_id = country_id.value;
    }
    else
    {
      var country_id = 0;
    }
    
    if(district_id > 0 || (country_id > 0 && country_id !='101'))
    {
        var url = "<?php echo base_url(); ?>settings/comman/getCitys";
        $.ajax({
          type:'POST',
          url: url,
          data:{'district_id':district_id,'country_id':country_id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
          dataType:'json',
          success: function(data)
          {
            if(data !=0 && data !='')
            {
                    $.each(data,function(idx,obj)
                    {
                        $(".cityslist").append($('<option>',
                        {
                            value:obj,
                            text:idx    
                            
                        }));    
                    }); 
             }
             else
             {
                 $(".cityslist").find('option').not(':first').remove(); 
             }

           }
        });
     }

   
}

function getCurrency(country_id)
{ 
   $(".currencylist").find('option').not(':first').remove();
    var country_id = country_id.value;
    if(country_id > 0)
    {
        var url = "<?php echo base_url(); ?>settings/comman/getCurrency";
        $.ajax({
          type:'POST',
          url: url,
          data:{'country_id':country_id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
          dataType:'json',
          success: function(data)
          {
            if(data !=0 && data !='')
            {
                    $.each(data,function(idx,obj)
                    {
                        $(".currencylist").append($('<option>',
                        {
                            value:obj,
                            text:idx    
                            
                        }));    
                    }); 
             }
             else
             {
                 $(".currencylist").find('option').not(':first').remove(); 
             }

           }
        });
     }

   
}
function getProductAmount(product_id)
{ 
   $(".productamount").val("");
    var product_id = product_id.value;
    if(product_id > 0)
    {
        var url = "<?php echo base_url(); ?>settings/comman/getProductAmount";
        $.ajax({
          type:'POST',
          url: url,
          data:{'product_id':product_id,"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
          dataType:'json',
          success: function(data)
          {
            if(data !=0 && data !='')
            {
                   
                   $(".productamount").val(data);
             }
             else
             {
                 $(".productamount").val(""); 
             }

           }
        });
     }

   
}


$(function()
{
  $(".searchcustomer").keyup(function()
  {

    var url = "<?php echo base_url();?>"+"settings/comman/getAssignedCustomerList";
    $.ajax({
    type: "POST",
    url: url,
    data:{'customer_name':$(this).val(),"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
    beforeSend: function(){
    },
    success: function(data){

      if(data !=0)
      {
        $("#suggesstion-box").show();
        $("#suggesstion-box").html(data);
      }
      else
      {
        
        $(".searchcustomer").val('');
        $("#customer_id").val('');
        $("#customer_code").val('');
        $("#suggesstion-box").html("<span style='color:red'><b>No Customers Found ,Please Try Again.</b></span>");

        if($(".cus_currency_cls").length > 0)
        {
          $(".cus_currency_cls").val('');
        }

      }
      
      
    }
    });
  });
});

function selectCustomerName(customer_id,customer_code,customer_name,currency) 
{
  $(".searchcustomer").val(customer_name);
  $("#customer_id").val(customer_id);
  $("#customer_code").val(customer_code);
  if($(".cus_currency_cls").length > 0)
  {
    $(".cus_currency_cls").val(currency);
  }
  $("#suggesstion-box").hide();
}


</script>