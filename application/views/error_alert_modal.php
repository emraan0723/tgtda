    <?php if($this->form_validation->run() === false && validation_errors() !='') 
    {
    ?>
    <button type="button" style="display:none" class="btn btn-danger" data-toggle="modal" data-target="#danger-alert-modal">Error Alert</button>
    <script>
        $(function()
        {
            $(".btn-danger").click();
        });
    </script>
    <?php
    }
    ?> 

 <!-- Danger Alert Modal -->
                                <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content modal-filled bg-danger">
                                            <div class="modal-body p-4">
                                                <div class="text-center">
                                                    <i class="dripicons-wrong h1"></i>
                                                    <h4 class="mt-2 text-white">Error</h4>
                                                    <p class="mt-3 op-7">
                                                        <?php
                                                        echo validation_errors();
                                                        ?>
                                                    </p>
                                                    <button type="button" class="btn btn-light my-2"
                                                        data-dismiss="modal">Continue</button>
                                                </div>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->