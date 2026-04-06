    

    function customerValidateForm(flag)
    {
       
        var id="";
        if(flag =='edit')
        {

           id=2;
        }
        else
        if(flag=='create')
        {
            id=1;
        } 

      
   
        if(($.trim($("#customer_name_id_"+id).val())) =='' || ($.trim($("#customer_name_id_"+id).val().length) <= 2))
        {   

            $("#customer_name_id_"+id).focus();
            $("#customer_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#customer_name_id_"+id+"+ div").text("Please Enter Customer Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {    
            $("#customer_name_id_"+id+"+ div").text("");   
            $("#customer_name_id_"+id).css('border-bottom',"");
        }
          
        if(id ==1)
        {
            if(($.trim($("#customer_code_id_"+id).val())) =='' || ($.trim($("#customer_code_id_"+id).val().length) <= 4))
            {   

                $("#customer_code_id_"+id).focus();
                $("#customer_code_id_"+id).css('border-bottom',"red 1px solid");
                $("#customer_code_id_"+id+"+ div").text("Please Enter Customer Code Minimum Length is 5 Characters").show();

                return false;
            }
            else
            {    
                $("#customer_code_id_"+id+"+ div").text("");   
                $("#customer_code_id_"+id).css('border-bottom',"");
            }
        }

 
          if(($.trim($("#customer_mobile_id_"+id).val())) =='' || ($.trim($("#customer_mobile_id_"+id).val().length) <= 9))
        {   

            $("#customer_mobile_id_"+id).focus();
            $("#customer_mobile_id_"+id).css('border-bottom',"red 1px solid");
            $("#customer_mobile_id_"+id+"+ div").text("Please Enter Mobile Number").show();

            return false;
        }
        else
        {    
            $("#customer_mobile_id_"+id+"+ div").text("");   
            $("#customer_mobile_id_"+id).css('border-bottom',"");
        }

          if(($.trim($("#phone_id_"+id).val())) =='')
        {   

            $("#phone_id_"+id).focus();
            $("#phone_id_"+id).css('border-bottom',"red 1px solid");
            $("#phone_id_"+id+"+ div").text("Please Enter Phone Number").show();

            return false;
        }
        else
        {    
            $("#phone_id_"+id+"+ div").text("");   
            $("#phone_id_"+id).css('border-bottom',"");
        }

        if(($.trim($("#cus_email_id_"+id).val())) =='')
        {   

            $("#cus_email_id_"+id).focus();
            $("#cus_email_id_"+id).css('border-bottom',"red 1px solid");
            $("#cus_email_id_"+id+"+ div").text("Please Enter Email").show();

            return false;
        }
        else
        {    
            $("#cus_email_id_"+id+"+ div").text("");   
            $("#cus_email_id_"+id).css('border-bottom',"");
        }


         if(($.trim($("#country_id_"+id).val())) =='')
        {   

            $("#country_id_"+id).focus();
            $("#country_id_"+id).css('border-bottom',"red 1px solid");
            $("#country_id_"+id+"+ div").text("Please Select Country").show();

            return false;
        }
        else
        {    
            $("#country_id_"+id+"+ div").text("");   
            $("#country_id_"+id).css('border-bottom',"");
        }

         if(($.trim($("#state_id_"+id).val())) =='' && $.trim($("#country_id_"+id).val()) =='101')
        {   

            $("#state_id_"+id).focus();
            $("#state_id_"+id).css('border-bottom',"red 1px solid");
            $("#state_id_"+id+"+ div").text("Please Select State").show();

            return false;
        }
        else
        {    
            $("#state_id_"+id+"+ div").text("");   
            $("#state_id_"+id).css('border-bottom',"");
        }


         if(($.trim($("#disticts_id_"+id).val())) =='' && $.trim($("#country_id_"+id).val()) =='101')
        {   

            $("#disticts_id_"+id).focus();
            $("#disticts_id_"+id).css('border-bottom',"red 1px solid");
            $("#disticts_id_"+id+"+ div").text("Please Select District").show();

            return false;
        }
        else
        {    
            $("#disticts_id_"+id+"+ div").text("");   
            $("#disticts_id_"+id).css('border-bottom',"");
        }


         if(($.trim($("#city_id_"+id).val())) =='')
        {   

            $("#city_id_"+id).focus();
            $("#city_id_"+id).css('border-bottom',"red 1px solid");
            $("#city_id_"+id+"+ div").text("Please Select City").show();

            return false;
        }
        else
        {    
            $("#city_id_"+id+"+ div").text("");   
            $("#city_id_"+id).css('border-bottom',"");
        }

      

         

        if(($.trim($("#address_id_"+id).val())) =='')
        {   

            $("#address_id_"+id).focus();
            $("#address_id_"+id).css('border-bottom',"red 1px solid");
            $("#address_id_"+id+"+ div").text("Please Enter Address").show();

            return false;
        }
        else
        {    
            $("#address_id_"+id+"+ div").text("");   
            $("#address_id_"+id).css('border-bottom',"");
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

         if($('.gstapplicable:checked').length ==0)
         {   
            $(".gstapplicable").focus();
            $(".gstapplicable").css('border-bottom',"red 1px solid");
            $(".gstappdiv").text("Please Select GST Applicable ?").show();

            return false;
        }
        else
        {    
            $(".gstappdiv").text("");   
            $(".gstapplicable").css('border-bottom',"");
        }

        
        


    
    }
      

    function countryName(e)
    {
         $(".country_s_d_c").val(""); // names empty all country,state, dis, city
         
         $("#"+get_id+"+ input").val("");
        var get_id = e.id;
        var getname =$("#"+get_id+" option:selected").text();
        $("#"+get_id+"+ input").val(getname);
    }


  
     function stateName(e)
    {
         $("#"+get_id+"+ input").val("");
        var get_id = e.id;
        var getname =$("#"+get_id+" option:selected").text();
        $("#"+get_id+"+ input").val(getname);
    }

  
     function districtName(e)
    {
         $("#"+get_id+"+ input").val("");
        var get_id = e.id;
        var getname =$("#"+get_id+" option:selected").text();
        $("#"+get_id+"+ input").val(getname);
    }

    function cityName(e)
    {
         $("#"+get_id+"+ input").val("");
        var get_id = e.id;
        var getname =$("#"+get_id+" option:selected").text();
        $("#"+get_id+"+ input").val(getname);
    }

        

     


        

     