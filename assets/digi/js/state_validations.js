    

    function stateValidationForm(flag)
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

        if(($.trim($("#state_name_id_"+id).val()) =='' ) || ($.trim($("#state_name_id_"+id).val().length) <= 2))
        {   
            $("#state_name_id_"+id).focus();
            $("#state_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#state_name_id_"+id+"+ div").text("Please Enter State Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {   
             $("#state_name_id_++id+ div").text("");  
            $("#state_name_id_"+id+"+ div").css('border-bottom',"");
        }

       /* if(($.trim($("#state_code_id_"+id).val()) =='' ) || ($.trim($("#state_code_id_"+id).val().length) <= 1))
        {   
            $("#state_code_id_"+id).focus();
            $("#state_code_id_"+id).css('border-bottom',"red 1px solid");
            $("#state_code_id_"+id+"+ div").text("Please Enter State Code Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {
            $("#state_code_id_"+id+"+ div").text("");  
             $("#state_code_id_"+id).css('border-bottom',"");
        }*/
    

     
    }
      
