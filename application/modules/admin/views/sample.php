
                
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <!-- Row -->
                            <div class="d-flex align-items-center justify-content-end">
                                    
                                <button type="button" class="btn btn-info btn-rounded m-t-10 mb-2" data-toggle="modal" data-target="#add-admin">Add New Admin</button>
                                </div>


                                  <!-- Add Contact Popup Model -->
                                <div id="add-admin" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h4 class="modal-title" id="myModalLabel">Add New Admin</h4>
                                                <button type="button" class="close ml-auto" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                               <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="inputfname" class="control-label col-form-label">First Name</label>
                                            <input type="text" class="form-control" id="inputfname" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="inputlname2" class="control-label col-form-label">Last Name</label>
                                            <input type="text" class="form-control" id="inputlname2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label col-form-label">Gender</label>
                                                <input name="radio-stacked" type="radio" id="customControlValidation2" class="radio-col-indigo material-inputs">
                                                <label for="customControlValidation2">Male</label>
                                                <input name="radio-stacked" type="radio" id="customControlValidation3" class="radio-col-indigo material-inputs">
                                                <label for="customControlValidation3">Female</label>
                                            </div>
                                        </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="uname" class="control-label col-form-label">Username</label>
                                            <input type="email" class="form-control" id="uname" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="nname" class="control-label col-form-label">Email</label>
                                            <input type="text" class="form-control" id="nname">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="uname" class="control-label col-form-label">Mobile</label>
                                            <input type="email" class="form-control" id="uname" >
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="uname" class="control-label col-form-label">Phone</label>
                                            <input type="email" class="form-control" id="uname" >
                                        </div>
                                    </div>
                                   
                                    
                                </div>
                                 <div class="row">
                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label col-form-label">Address</label>
                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info waves-effect"
                                                    data-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>


                           <div class="card">
                            <div class="card-body">
                            <div class="table-responsive">
                                   
                                <table class="table table-striped table-bordered table-striped m-b-0" id="editable-datatable">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Mobile</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="1" class="gradeX">
                                            <td>1</td>
                                            <td>venkat</td>
                                            <td>Male</td>
                                            <td class="center">950242698</td>
                                            <td class="center">Active</td>
                                        </tr>
                                        <tr id="1" class="gradeC">
                                            <td>1</td>
                                            <td>Karthik</td>
                                            <td>Male</td>
                                            <td class="center">9010109895</td>
                                            <td class="center">Active</td>
                                        </tr>
                                        
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

