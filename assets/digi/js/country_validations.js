    

    function countryValidationForm(flag)
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
        if(($.trim($("#country_name_id_"+id).val()) =='' )|| ($.trim($("#country_name_id_"+id).val().length) <= 2))
        {   
            $("#country_name_id_"+id).focus();
            $("#country_name_id_"+id).css('border-bottom',"red 1px solid");
            $(".country_name_cls").text("Please Enter Country Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {
             $("#country_name_id_"+id).css('border-bottom',"");
        }

        /*if(($.trim($("#country_code_id_"+id).val()) =='' )|| ($.trim($("#country_code_id_"+id).val().length) <= 1))
        {   
            $("#country_code_id_"+id).focus();
            $("#country_code_id_"+id).css('border-bottom',"red 1px solid");
            $(".country_code_cls").text("Please Enter Country  Code Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {
             $("#country_code_id_"+id).css('border-bottom',"");
        }*/
     
    }
      
