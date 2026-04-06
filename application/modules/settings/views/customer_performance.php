
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        <div class="card card-body">
                            <h6 class="card-title" style="margin-bottom: -27px;">Customer Performance Setup</h6>
                            

                           <form class="form-material mt-5 novalidate" method="post">

                            <div class="row">
                                                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group ">
                                        <label>Select Customer <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tax_id">
                                                <option value=""></option>
                                                <option value="GST">LV Prasad Eye Institute</option>
                                                <option value="HI">LV Prasad Eye Institute-Madhapur</option>
                                            </select>
                                    </div>
                                </div>
                                
                              
                                <div class="col-sm-12 col-lg-3">

                                    <div class="form-group ">
                                         <label>Performance Validity<span class="text-danger">*</span></label>
                                         <div class="input-daterange input-group" id="date-range">
                                                <input type="text" readonly="readonly"  class="datepicker form-control" name="start" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-info b-0 text-white">TO</span>
                                                </div>
                                                <input type="text" readonly="readonly" class="datepicker form-control" name="end" />
                                            </div>
                                     </div>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <div class="form-group ">
                                        <label>&nbsp;</label>
                                           <div class="col-md-3">
                                                <input type="checkbox" id="md_checkbox_34" class="material-inputs filled-in chk-col-light-green">
                                                <label for="md_checkbox_34">Performance</label>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-sm-12 col-lg-3">
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
                                <h6 class="card-title">View Performance Log</h6>
                            <div class="table-responsive">
                                   
                                 <table class="tablesaw table-striped table-hover table-bordered table no-wrap"
                                    >
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border">Sno</th>
                                            <th>Customer</th>
                                           <th >Performance Enabled</th>
                                            <th >Performance Validity</th>
                                            <th >Created By</th>
                                            <th>Created Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>LV Prasad Eye Institute</td>
                                            <td><div class="col-md-3">
                                        <input type="checkbox" id="md_checkbox_11" class="material-inputs chk-col-light-green" checked="">
                                        <label for="md_checkbox_11"></label>
                                    </div></td>
                                   
                                            <td>17-07-2020 to 18-07-2020</td>
                                             <td>kiran Kumar</td>
                                            <th>01-07-2020 10:00 AM</th>
                                            
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>LV Prasad Eye Institute - Madhapur</td>
                                           <td><div class="col-md-3">
                                        <input type="checkbox" id="md_checkbox_11" class="material-inputs chk-col-light-green" checked="">
                                        <label for="md_checkbox_11"></label>
                                    </div></td>

                                            <td>19-07-2020 to 20-07-2020</td>
                                            <td>Mahesh Kumar</td>
                                            <th>05-07-2020 10:30 AM</th>
                                            
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

