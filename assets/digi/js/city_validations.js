    

    function cityValidationForm(flag)
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
      
        var alpha_s = /^([A-Za-z\ ]+)$/;
        var alpha = /^([A-Za-z]+)$/;

        if(($.trim($("#country_id_"+id).val())) =='')
        {   

            $("#country_id_"+id).focus();
            $("#country_id_"+id).css('border-bottom',"red 1px solid");
            $("#country_id_"+id+"+ div").text("Please Select Country ").show();

            return false;
        }
        else
        {    
           $("#country_id_"+id+"+ div").text("");   
            $("#country_id_"+id).css('border-bottom',"");
        }


        /*if(($.trim($("#state_id_"+id).val())) =='')
        {   

            $("#state_id_"+id).focus();
            $("#state_id_"+id).css('border-bottom',"red 1px solid");
            $("#state_id_"+id+"+ div").text("Please Select State ").show();

            return false;
        }
        else
        {    
           $("#state_id_"+id+"+ div").text("");   
            $("#state_id_"+id).css('border-bottom',"");
        }

        if($.trim($("#disticts_id_"+id).val()) =='')
        {   
            $("#disticts_id_"+id).focus();
            $("#disticts_id_"+id).css('border-bottom',"red 1px solid");
             $("#disticts_id_"+id+"+ div").text("Please Select  District").show();

            return false;
        }
        else
        {
            $("#disticts_id_"+id+"+ div").text("");   
             $("#disticts_id_"+id).css('border-bottom',"");
        }*/

        if(($.trim($("#city_name_id_"+id).val()) =='' )|| ($.trim($("#city_name_id_"+id).val().length) <= 2))
        {   
            $("#city_name_id_"+id).focus();
            $("#city_name_id_"+id).css('border-bottom',"red 1px solid");
             $("#city_name_id_"+id+"+ div").text("Please Enter city Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {
            $("#city_name_id_"+id+"+ div").text("");   
             $("#city_name_id_"+id).css('border-bottom',"");
        }

        
     
    }
      
