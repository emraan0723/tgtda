
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <h6 class="card-title" style="margin-bottom: -27px;">Add District</h6>
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>

                           <form class="form-material mt-5 novalidate" method="post">

                            <div class="row">
                                 <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tax_id">
                                                <option value=""></option>
                                                <option value="GST">India</option>
                                                <option value="HI">Russia</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Select State <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tax_id">
                                                <option value=""></option>
                                                <option value="GST">Andra Pradesh</option>
                                                <option value="HI">Telagana</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>District Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name_id"  class="form-control form-control-line"> 
                                  
                                     </div>
                                </div>

                                

                               
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        
                                        <label>District Code</label>
                                        <input type="text" name="first_name" id="first_name_id"  class="form-control form-control-line"> 
                                  
                                     </div>
                                </div>
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="card-body">
                                          <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                
                                

                                
                            
                              
                            </form>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <!-- Column -->
                   <div class="col-md-12">
                        <div class="card card-body">
                           <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">View Districts</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    >
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">Sno</th>
                                            <th>Country Name</th>
                                            <th >State Name </th>
                                            <th >District Name </th>
                                            <th >District Code </th>
                                            
                                            <th>Status</th>
                                             <th>Action</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>India</td>
                                            <td>Andra Pradesh</td>
                                            <td>Nellore</td>
                                            <td>NLR</td>
                                            <td class="center">Active</td>
                                            <td class="center">
                                                <a class="remove" href="javascript:void(0)" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                               </td>
                                        </tr>
                                       <tr>
                                            <td>2</td>
                                            <td>India</td>
                                            <td>Telagana</td>
                                            <td>Nalgonda</td>
                                            <td>NLG</td>
                                            <td class="center">Active</td>
                                            <td class="center">
                                                <a class="remove" href="javascript:void(0)" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                               </td>
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

