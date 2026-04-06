  <link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                
                <div class="row">
                    <!-- Column -->
                   <div class="col-md-12">
                        <div class="card card-body">
                           <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">User Privileges</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    id="user_privilleges_datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">SNO</th>
                                            <th>Admin</th>
                                            <th >Access Module</th>
                                            <th >Add</th>
                                            <th >Edit</th>
                                            <th>view</th>
                                            <th>No Access</th>
                                            <th>Full Access</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
<script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/js/pages/datatable/custom-datatable.js"></script>
  

<?php $this->load->view('js/user_privileges_js');   ?>
    