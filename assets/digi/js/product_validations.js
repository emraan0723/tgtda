    

    function productValidateForm(flag)
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



        if(($.trim($("#product_name_id_"+id).val())) =='' || ($.trim($("#product_name_id_"+id).val().length) <= 3))
        {   
            $("#product_name_id_"+id).focus();
            $("#product_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#product_name_id_"+id+"+ div").text("Please Enter Product name Length is 4 Characters").show();

            return false;
        }
        else
        {    

            $("#product_name_id_"+id+"+ div").text("");   
            $("#product_name_id_"+id).css('border-bottom',"");
        }



        if(($.trim($("#product_code_id_"+id).val())) =='' || ($.trim($("#product_code_id_"+id).val().length) <= 2))
        {   

            $("#product_code_id_"+id).focus();
            $("#product_code_id_"+id).css('border-bottom',"red 1px solid");
            $("#product_code_id_"+id+"+ div").text("Please Enter Product Code Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {    
            $("#product_code_id_"+id+"+ div").text("");   
            $("#product_code_id_"+id).css('border-bottom',"");
        }

         if(($.trim($("#product_desc_id_"+id).val())) =='')
        {   

            $("#product_desc_id_"+id).focus();
            $("#product_desc_id_"+id).css('border-bottom',"red 1px solid");
            $("#product_desc_id_"+id+"+ div").text("Please Enter Product Description").show();

            return false;
        }
        else
        {    
            $("#product_desc_id_"+id+"+ div").text("");   
            $("#product_desc_id_"+id).css('border-bottom',"");
        }

       /* if(($.trim($("#install_charge_id_"+id).val())) =='')
        {   

            $("#install_charge_id_"+id).focus();
            $("#install_charge_id_"+id).css('border-bottom',"red 1px solid");
            $("#install_charge_id_"+id+"+ div").text("Please Enter Installation Charges").show();

            return false;
        }
        else
        {    
            $("#install_charge_id_"+id+"+ div").text("");   
            $("#install_charge_id_"+id).css('border-bottom',"");
        }*/

       /*  if(($.trim($("#base_price_id_"+id).val())) =='')
        {   

            $("#base_price_id_"+id).focus();
            $("#base_price_id_"+id).css('border-bottom',"red 1px solid");
            $("#base_price_id_"+id+"+ div").text("Please Enter Base Price").show();

            return false;
        }
        else
        {    
            $("#base_price_id_"+id+"+ div").text("");   
            $("#base_price_id_"+id).css('border-bottom',"");
        }*/

     
            

     
    }
      
