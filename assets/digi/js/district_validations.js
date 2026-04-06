    

    function districtValidationForm(flag)
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


        if(($.trim($("#state_id_"+id).val())) =='')
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


        if(($.trim($("#district_name_id_"+id).val()) =='' )|| ($.trim($("#district_name_id_"+id).val().length) <= 2))
        {   
            $("#district_name_id_"+id).focus();
            $("#district_name_id_"+id).css('border-bottom',"red 1px solid");
             $("#district_name_id_"+id+"+ div").text("Please Enter District Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {
            $("#district_name_id_"+id+"+ div").text("");   
             $("#district_name_id_"+id).css('border-bottom',"");
        }

        /*if(($.trim($("#district_code_id_"+id).val()) =='' )|| ($.trim($("#district_code_id_"+id).val().length) <= 1))
        {   
            $("#district_code_id_"+id).focus();
            $("#district_code_id_"+id).css('border-bottom',"red 1px solid");
            $("#district_code_id_"+id+"+ div").text("Please Enter District Code Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {
            $("#district_code_id_"+id+"+ div").text("");   
             $("#district_code_id_"+id).css('border-bottom',"");
        }*/
     
    }
      
