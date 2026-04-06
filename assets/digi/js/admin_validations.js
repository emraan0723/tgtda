    

    function adminValidateForm(flag)
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



        if(($.trim($("#first_name_id_"+id).val())) =='' || ($.trim($("#first_name_id_"+id).val().length) <= 1))
        {   
            $("#first_name_id_"+id).focus();
            $("#first_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#first_name_id_"+id+"+ div").text("Please Enter First Name Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {    

            $("#first_name_id_"+id+"+ div").text("");   
            $("#first_name_id_"+id).css('border-bottom',"");
        }



        if(($.trim($("#last_name_id_"+id).val())) =='' || ($.trim($("#last_name_id_"+id).val().length) <= 1))
        {   

            $("#last_name_id_"+id).focus();
            $("#last_name_id_"+id).css('border-bottom',"red 1px solid");
            $("#last_name_id_"+id+"+ div").text("Please Enter Last Name Minimum Length is 2 Characters").show();

            return false;
        }
        else
        {    
            $("#last_name_id_"+id+"+ div").text("");   
            $("#last_name_id_"+id).css('border-bottom',"");
        }

         if(($.trim($("#gender_"+id).val())) =='')
        {   

            $("#gender_"+id).focus();
            $("#gender_"+id).css('border-bottom',"red 1px solid");
            $("#gender_"+id+"+ div").text("Please Select Gender").show();

            return false;
        }
        else
        {    
            $("#gender_"+id+"+ div").text("");   
            $("#gender_"+id).css('border-bottom',"");
        }

        if(($.trim($("#mobile_id_"+id).val())) =='' || ($.trim($("#mobile_id_"+id).val().length) <= 9))
        {   

            $("#mobile_id_"+id).focus();
            $("#mobile_id_"+id).css('border-bottom',"red 1px solid");
            $("#mobile_id_"+id+"+ div").text("Please Enter Mobile Number ").show();

            return false;
        }
        else
        {    
            $("#mobile_id_"+id+"+ div").text("");   
            $("#mobile_id_"+id).css('border-bottom',"");
        }

         if(($.trim($("#email_id_"+id).val())) =='')
        {   

            $("#email_id_"+id).focus();
            $("#email_id_"+id).css('border-bottom',"red 1px solid");
            $("#email_id_"+id+"+ div").text("Please Enter Email").show();

            return false;
        }
        else
        {    
            $("#email_id_"+id+"+ div").text("");   
            $("#email_id_"+id).css('border-bottom',"");
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

        if(flag=='create')
        {

                if(($.trim($("#username_id_"+id).val())) =='' || ($.trim($("#username_id_"+id).val().length) <= 5))
                {   

                    $("#username_id_"+id).focus();
                    $("#username_id_"+id).css('border-bottom',"red 1px solid");
                    $("#username_id_"+id+"+ div").text("Please Enter Username Minimum Length is 6 Characters").show();

                    return false;
                }
                else
                {    
                    $("#username_id_"+id+"+ div").text("");   
                    $("#username_id_"+id).css('border-bottom',"");
                }


                if(($.trim($("#password_id_"+id).val())) =='' || ($.trim($("#password_id_"+id).val().length) <= 5))
                {   

                    $("#password_id_"+id).focus();
                    $("#password_id_"+id).css('border-bottom',"red 1px solid");
                    $("#password_id_"+id+"+ div").text("Please Enter Password Minimum Length is 6 Characters").show();

                    return false;
                }
                else
                {    
                    $("#password_id_"+id+"+ div").text("");   
                    $("#password_id_"+id).css('border-bottom',"");
                }
         }
            

     
    }
      
