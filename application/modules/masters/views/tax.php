
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <h6 class="card-title" style="margin-bottom: -27px;">Add Tax</h6>
                            <!-- Row -->

                            <?php //echo validation_errors(); ?>

                           <form class="form-material mt-5 novalidate" method="post">

                            <div class="row">
                                 <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tax_id">
                                                <option value=""></option>
                                                <option value="GST">India</option>
                                                <option value="HI">Russia</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        <label>Select State <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tax_id">
                                                <option value=""></option>
                                                <option value="GST">Andra Pradesh</option>
                                                <option value="HI">Telagana</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-3">
                                    <div class="form-group ">
                                        
                                        <label>Tax Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name_id" required data-validation-required-message="This field is required" class="form-control form-control-line"> 
                                  
                                     </div>
                                </div>

                                 <div class="col-sm-12 col-lg-3">
                                        <div class="card-body">
                                          <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            <button type="reset" class="btn btn-dark waves-effect waves-light">Reset</button>
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
                                <h6 class="card-title">View Taxs</h6>
                            <div class="">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    >
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">Sno</th>
                                            <th>Country Name</th>
                                            <th >State Name </th>
                                            <th >Tax Name </th>
                                            
                                            <th>Status</th>
                                             <th>Action</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>India</td>
                                            <td>Andra Pradesh</td>
                                            <td>IGST</td>
                                           
                                            <td class="center">Active</td>
                                              <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                         <tr>
                                            <td>2</td>
                                            <td>India</td>
                                            <td>Andra Pradesh</td>
                                            <td>CGST</td>
                                           
                                            <td class="center">Active</td>
                                            <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>India</td>
                                            <td>Telagana</td>
                                            <td>CGST</td>
                                           
                                            <td class="center">Active</td>
                                            <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>India</td>
                                            <td>Telagana</td>
                                            <td>IGST</td>
                                           
                                            <td class="center">Active</td>
                                            <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>India</td>
                                            <td>Telagana</td>
                                            <td>SGST</td>
                                           
                                            <td class="center">Active</td>
                                            <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>India</td>
                                            <td>Telagana</td>
                                            <td>UTGST</td>
                                           
                                            <td class="center">Active</td>
                                            <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                            
                                                            <a class="dropdown-item" onclick="AdminEdit();" href="javascript:void(0)"><i
                                                                    class="ti-pencil-alt"></i> Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0)">In-Active</a>
                                                             
                                                          
                                                        </div>
                                                    </div>
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

