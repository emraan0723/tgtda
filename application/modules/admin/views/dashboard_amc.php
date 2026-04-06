

            <div class="container-fluid">

              <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex no-block">
                                    <h4 class="card-title">Next 90 days AMC Payment customers list</h4>
                                    <!-- <div class="ml-auto">
                                        <select class="custom-select">
                                            <option selected="">January</option>
                                            <option value="1">February</option>
                                            <option value="2">March</option>
                                            <option value="3">April</option>
                                        </select>
                                    </div> -->
                                </div>
                                <div class="month-table">
                                    <div class="table-responsive mt-3">
                                        <table class="table stylish-table v-middle mb-0 no-wrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-0 text-muted font-weight-normal">Customer  Name</th>
                                                    <th class="border-0 text-muted font-weight-normal">Customer Code</th>
                                                    <th class="border-0 text-muted font-weight-normal">Amc Amount</th>
                                                    <th class="border-0 text-muted font-weight-normal">Service Start Date</th>
                                                    <th class="border-0 text-muted font-weight-normal">Last Paid Date</th>
                                                     <th class="border-0 text-muted font-weight-normal">Due Date</th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                  <?php 
                                                  if(isset($amc_cus) && !empty($amc_cus))
                                                  {
                                                      foreach ($amc_cus as $value) 
                                                      {
                                                        
                                                          /*print"<pre>";
                                                          print_r($value);*/
                                                    ?>
                                                        <tr style="cursor: pointer;">
                                                    <td><?php echo isset($value['tas_amc_customer_name']) ? $value['tas_amc_customer_name']  : ''; ?></td>
                                                     <td><?php echo isset($value['tas_amc_customer_code']) ? $value['tas_amc_customer_code']  : ''; ?></td>
                                                      <td><?php echo isset($value['tas_amc_amount']) ? $value['tas_amc_amount']  : ''; ?></td>
                                                      <td><?php echo isset($value['tas_amc_service_date']) ? date('d-m-Y',strtotime($value['tas_amc_service_date']))  : ''; ?></td>
                                                      <td><?php echo isset($value['tas_amc_paid_date']) && $value['tas_amc_paid_date'] !='0000-00-00'   ? date('d-m-Y',strtotime($value['tas_amc_paid_date']))  : '--'; ?></td>

                                                       <td><?php echo isset($value['tas_amc_next_due_date']) ? date('d-m-Y',strtotime($value['tas_amc_next_due_date']))  : ''; ?></td>

                                                       <tr>

                                                    <?php
                                                      }
                                                  }
                                                  ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- Row -->

                
              </div>
            
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->



    