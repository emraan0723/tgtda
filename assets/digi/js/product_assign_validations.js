    function productAsignValidateForm(flag)
    {
        var id="";
        if(flag=='edit')
        {
           id=2;
        }
        else
        if(flag=='create')
        {
            id=1;
        } 

        if(($.trim($("#customer_id_"+id).val())) =='')
        {   
            $("#customer_id_"+id).focus();
            $("#customer_id_"+id).css('border-bottom',"red 1px solid");
            $("#customer_id_"+id+"+ div").text("Please Select Customer").show();

            return false;
        }
        else
        {    

            $("#customer_id_"+id+"+ div").text("");   
            $("#customer_id_"+id).css('border-bottom',"");
        }



        if(($.trim($("#product_id_"+id).val())) =='')
        {   

            $("#product_id_"+id).focus();
            $("#product_id_"+id).css('border-bottom',"red 1px solid");
            $("#product_id_"+id+"+ div").text("Please Select Products").show();

            return false;
        }
        else
        {    
            $("#product_id_"+id+"+ div").text("");   
            $("#product_id_"+id).css('border-bottom',"");
        }


        if(($.trim($("#category_id").val())) =='')
        {   

            $("#category_id").focus();
            $("#category_id").css('border-bottom',"red 1px solid");
            $("#category_id + div").text("Please Select Category").show();

            return false;
        }
        else
        {    
            $("#category_id + div").text("");   
            $("#category_id").css('border-bottom',"");
        }




         if(($.trim($("#currency_id_"+id).val())) =='')
        {   

            $("#currency_id_"+id).focus();
            $("#currency_id_"+id).css('border-bottom',"red 1px solid");
            $("#currency_id_"+id+"+ div").text("Please Select Currency").show();

            return false;
        }
        else
        {    
            $("#currency_id_"+id+"+ div").text("");   
            $("#currency_id_"+id).css('border-bottom',"");
        }


        if(($.trim($("#payment_type_id_"+id).val())) =='')
        {   

            $("#payment_type_id_"+id).focus();
            $("#payment_type_id_"+id).css('border-bottom',"red 1px solid");
            $("#payment_type_id_"+id+"+ div").text("Please Select Payment Type").show();

            return false;
        }
        else
        {    
            $("#payment_type_id_"+id+"+ div").text("");   
            $("#payment_type_id_"+id).css('border-bottom',"");
        }


         if(($.trim($("#product_amt_id_"+id).val())) =='')
        {   

            $("#product_amt_id_"+id).focus();
            $("#product_amt_id_"+id).css('border-bottom',"red 1px solid");
            $("#product_amt_id_"+id+"+ div").text("Required Product Amount").show();

            return false;
        }
        else
        {    
            $("#product_amt_id_"+id+"+ div").text("");   
            $("#product_amt_id_"+id).css('border-bottom',"");
        }

         if(id ==1)
        {
            if(($.trim($("#service_start_date_id_"+id).val())) =='')
            {   
                $("#service_start_date_id_"+id).focus();
                $("#service_start_date_id_"+id).css('border-bottom',"red 1px solid");
                $("#service_start_date_id_"+id+"+ div").text("Please Select Start Date").show();

                return false;
            }
            else
            {    
                $("#service_start_date_id_"+id+"+ div").text("");   
                $("#service_start_date_id_"+id).css('border-bottom',"");
            }
        }


       
     
            

     
    }
      

