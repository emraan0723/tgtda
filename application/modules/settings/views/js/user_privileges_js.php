  <script type="text/javascript">
    var table;

    $(document).ready(function() {
      var url = "<?php echo base_url();?>"+"settings/user_privilleges_ajax_list";
    //datatables
    table = $('#user_privilleges_datatable').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "bSort" : false,
        "bPaginate": false, //hide pagination
        // "bFilter": false, //hide Search bar
          "bInfo": false, // hide showing entries
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
            "data":{"<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},          
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload();  //just reload table
    });

    });



        function PrivilegesStatus(m_access,adminid,id,privilege)
        {
            //alert(m_access+'--'+adminid+'--'+id+'--'+privilege);
            var getid = m_access+privilege+id;
            var cls = m_access+'_'+adminid;

            if($("#"+getid).prop('checked')==true)
            { 
                if(privilege=='no_access')
                {
                    $("."+cls).prop( "checked", false);
                    $("#"+getid).prop( "checked", true);
                }
                else
                {
                    $("#"+m_access+'no_access'+id).prop( "checked", false);
                }

                if(privilege=='full_access')
                {
                    $("."+cls).prop( "checked", true);
                    $("#"+m_access+'no_access'+id).prop( "checked", false);
                    $("#"+getid).prop( "checked", true);
                }
                
               // 1--> ACCESS
               accessPrivileges(m_access,adminid,privilege,1,0);
              
            } 
            else
            {   var fullacessremove = 0;
                if(($("#"+m_access+"adding"+id+":checkbox:checked").length ==0) || ($("#"+m_access+"edit"+id+":checkbox:checked").length ==0) || ($("#"+m_access+"view"+id+":checkbox:checked").length ==0))
                {
                    
                    $("#"+m_access+"full_access"+id).prop("checked", false);
                    fullacessremove =1;
                }
                else
                {
                    
                }

                if(privilege=='full_access')
                {
                    $("."+cls).prop("checked", false);
                    
                }
                
                 //0--> NO-ACCESS , 1-->FULL-ACESS REMOVE
                accessPrivileges(m_access,adminid,privilege,0,fullacessremove);
            }
   
        }

        function accessPrivileges(m_access,adminid,privilege,val,fullacessremove)
        {
            var url = "<?php echo base_url();?>"+"privilegesAccess";
            $.ajax({
            url: url,
            type:'POST',
            data:{ 'acessmodule':m_access,'id':adminid,'privilege':privilege,'values':val,'fullacessremove':fullacessremove,
                "<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
            dataType:'JSON',
            success: function(data)
            {

                if(data =='UPDATE_SUCCESS')
                {
                    $(".alert_digi_msgs").show();
                    $(".alert_digi_msgs").attr('style','background-color:#3ebd09de !important;padding:4px;');
                    $(".alert_digi_msgs").html("<?php echo  $this->errormsgs->add_suceess; ?>");
                    setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 1000);
                }
                else
                if(data =='INSERT_SUCCESS')
                {
                    $(".alert_digi_msgs").show();
                    $(".alert_digi_msgs").attr('style','background-color:#3ebd09de !important;padding:4px;');
                    $(".alert_digi_msgs").html("<?php echo  $this->errormsgs->add_suceess; ?>");
                    setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 1000);
                }
                else
                {
                    $(".alert_digi_msgs").show();
                    $(".alert_digi_msgs").attr('style','background-color:#fc4b6c !important;padding:4px;');
                    $(".alert_digi_msgs").html("<?php echo  $this->errormsgs->add_error; ?>");
                    setTimeout(function(){ $(".alert_digi_msgs").hide(); }, 1000);
                }

                
            }

            });
        }

        
    </script>