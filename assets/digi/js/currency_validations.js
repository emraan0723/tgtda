    

    function currencyValidationForm(flag)
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

        /*if(($.trim($("#country_id_"+id).val())) =='')
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
        }*/

        if(($.trim($("#currency_name_id_"+id).val()) =='' ) || ($.trim($("#currency_name_id_"+id).val().length) <= 2))
        {   
            $("#currency_name_id_"+id).focus();
            $("#currency_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#currency_name_id_"+id+"+ div").text("Please Enter Currency Name Minimum Length is 3 Characters").show();

            return false;
        }
        else
        {   
             $("#currency_name_id_++id+ div").text("");  
            $("#currency_name_id_"+id+"+ div").css('border-bottom',"");
        }


        

        
    

     
    }

     function countryName(e)
    {
         $("#"+get_id+"+ input").val("");
        var get_id = e.id;
        var getname =$("#"+get_id+" option:selected").text();
        $("#"+get_id+"+ input").val(getname);
    }

      
