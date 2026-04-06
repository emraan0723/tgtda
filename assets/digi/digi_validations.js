 $(function()
 {
    $('.alpha_s').keypress(function (e) 
    {
        var get_id = this.id;
        var regex = new RegExp("^([A-Za-z\ ]+)$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) 
        {
            $("#"+get_id).css('border-bottom',"");
            $("#"+get_id+"+ div").hide();
            return true;

        }
        else
        { 
            $("#"+get_id).focus();
            $("#"+get_id).css('border-bottom',"red 1px solid");
            $("#"+get_id+"+ div").text("Please Enter Alphabets & Space Only").show();
        }

        e.preventDefault();
        return false;

    });

    $('.alpha').keypress(function (e) 
    {
        var get_id = this.id;
        var regex = new RegExp("^([A-Za-z]+)$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) 
        {
            $("#"+get_id).css('border-bottom',"");
            $("#"+get_id+"+ div").hide();
            return true;

        }
        else
        { 
            $("#"+get_id).focus();
            $("#"+get_id).css('border-bottom',"red 1px solid");
            $("#"+get_id+"+ div").text("Please Enter Alphabets Only").show();
        }

        e.preventDefault();
        return false;

    });

     $(".alphanumeric").keypress(function (e) 
     {

        var get_id = this.id;
        var regex = new RegExp("^([A-Za-z0-9]+)$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) 
        {
            $("#"+get_id).css('border-bottom',"");
            $("#"+get_id+"+ div").hide();
            return true;

        }
        else
        { 
            $("#"+get_id).focus();
            $("#"+get_id).css('border-bottom',"red 1px solid");
            $("#"+get_id+"+ div").text("Please enter alphabets and numbers only").show();
        }

        e.preventDefault();
        return false;


           
    });


    $('.num').keyup(function (e) 
    {
        var val = $(this).val();
        var get_id = this.id;
        if(isNaN(val))
        {
           val = val.replace(/[^0-9\.]/g,'');
           if(val.split('.').length>2) val =val.replace(/\.+$/,"");
        }
        $(this).val(val);
    });


  
    $('.mobilevalidation').keypress(function (e) 
    {
        var get_id = this.id;
        var regex = new RegExp("^[0-9]$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) 
        {
            $("#"+get_id).css('border-bottom',"");
            $("#"+get_id+"+ div").hide();
            return true;

        }
        else
        { 
            $("#"+get_id).focus();
            $("#"+get_id).css('border-bottom',"red 1px solid");
            $("#"+get_id+"+ div").text("Please Enter Valid 10 Digit Mobile Number Only").show();
        }

        e.preventDefault();
        return false;

    });

    $('.mobilevalidation').blur(function (e) 
    {

             var get_id = this.id;
            var re = /^[0-9]{10}$/.test(this.value);
            if(!re) 
            {   
                $("#"+get_id).val("");
                $("#"+get_id).focus();
                $("#"+get_id).css('border-bottom',"red 1px solid");
                $("#"+get_id+"+ div").text("Please Enter Valid 10 Digit Mobile Number Only").show();
            } 
            else
            {
                    $("#"+get_id).css('border-bottom',"");
                    $("#"+get_id+"+ div").hide();
                          
            }

        e.preventDefault();
        return false;
    });


     $('.emailvalidation').blur(function (e) 
    {   
            var get_id = this.id;
            var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
            if(!re) 
            {   
                $("#"+get_id).val("");
                $("#"+get_id).focus();
                $("#"+get_id).css('border-bottom',"red 1px solid");
                $("#"+get_id+"+ div").text("Please Enter Valid Email Address").show();
            } 
            else
            {
                    $("#"+get_id).css('border-bottom',"");
                    $("#"+get_id+"+ div").hide();
                          
            }

        e.preventDefault();
        return false;

    });

    $('.usernamevalidation').blur(function (e) 
    {

            var get_id = this.id;
            var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
            if(!re) 
            {   
                $("#"+get_id).val("");
                $("#"+get_id).focus();
                $("#"+get_id).css('border-bottom',"red 1px solid");
                $("#"+get_id+"+ div").text("Please Enter Valid Username (email Address)").show();
            } 
            else
            {
                    $("#"+get_id).css('border-bottom',"");
                    $("#"+get_id+"+ div").hide();
                          
            }

        e.preventDefault();
        return false;
    });

    $('.passwordvalidation').blur(function (e) 
    {


            var get_id = this.id;
            var re = /^(?=.*[a-z])[A-Za-z0-9\d=!\-@._*]+$/.test(this.value);
            if(!re) 
            {   
                $("#"+get_id).val("");
                $("#"+get_id).focus();
                $("#"+get_id).css('border-bottom',"red 1px solid");
                $("#"+get_id+"+ div").text("Please Enter Password Alpha Numeric & Caps & Small lettes & Special (!\-@._*) Characters , EX - Bv@1 ").show();
            } 
            else
            {
                    $("#"+get_id).css('border-bottom',"");
                    $("#"+get_id+"+ div").hide();
                          
            }


        e.preventDefault();
        return false;

    });



     
 });  

