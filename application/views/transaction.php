<section class="content">
    <div class="row">
        <div style="margin-bottom:25px;" class="col-xs-12 no-print">
            <div class="col-md-12">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-flat btn-lg  pull-right "><i class="fa fa-print pull-left"></i> Print Report</button>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box" id="print-section">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <?php echo $table_name; ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($table_heading_names_of_coloums as $table_head)
                                    {
                    				?>
                                        <th>
                                            <?php echo $table_head; ?>
                                        </th>
                                    <?php
                    				}
                    				?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                				if($transaction_records != NULL)
                                {
                					$sno = 1;
                					foreach ($transaction_records as $obj_transaction_records)
                                    {
                				?>
                                    <tr>
                                        <td>
                                            <?php echo $sno; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->rescp_name; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->email; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->amount; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->currency; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->tranac_date; ?>
                                        </td>

                                        <td>
                                            <?php echo $obj_transaction_records->method; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->city; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->state; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->line; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->countrycode; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->postalcode; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_transaction_records->invoice_id; ?>
                                        </td>
    									<td>
                                            <?php echo $obj_transaction_records->txid; ?>
                                        </td>
                                    </tr>
                                    <?php
                    					$sno++;	
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
</section>