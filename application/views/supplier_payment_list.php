<section class="content-header">
    <div class="row">
        <div class="col-md-6">
            <div class="pull pull-left">
               <ol class="breadcrumb pull-right">
                    <li>
                        <a href="<?php echo base_url('homepage'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="active">Received payments</li>
                </ol>
            </div>
        </div>
        <div class="col-md-6">
            <div class="pull pull-right">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-flat pull-right "><i class="fa fa-print  pull-left"></i> Print / Pdf</button>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box" id="print-section">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> 
                        <?php echo $table_name; ?>
                    </h3>
                </div>
                <div class="box-body">
                    <?php
                        $attributes = array('id'=>'vouchers_payments','method'=>'post','class'=>'');
                    ?>
                    <?php echo form_open('payee/payment_list',$attributes); ?>
                    <div class="row no-print">
                        <div class="col-md-3 col-sm-4 ">
                            <div class="form-group">
                                <label for="date_from" class="col-sm-5 control-label">
                                    Date From
                                </label>
                                <div class="col-sm-7">
                                    <?php 
                                        $data = array('class'=>'form-control','id'=>'date_from','type'=>'date','name'=>'date1');
                                        echo form_input($data); 
                                    ?>
                                </div>   
                            </div>
                        </div> 
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <label for="date_from" class="col-sm-4 control-label">
                                    Date To
                                </label>
                                <div class="col-sm-8 col-md-8">
                                    <?php 
                                         $data1 = array('class'=>'form-control','type'=>'date','name'=>'date2');
                                        echo form_input($data1);
                                    ?>
                                </div>   
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php
                                    $data = array('class'=>'btn btn-info','type' => 'submit','name'=>'btnSubmit','value'=>'true', 'content' => '<i class="fa fa-search" aria-hidden="true"></i> Search payments');
                                    echo form_button($data);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" onchange="search_transaction(this.value)" name="timeperiod">
                                    <option value="Filter">Filter </option>
                                    <option value="month">This Month </option>
                                    <option value="three">Last 3 Months </option>
                                    <option value="year"> This Year </option>
                                    <option value="all">  All </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?> 
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
                                if($payee_payment != NULL)
                                {
                                    foreach ($payee_payment as $single_payment)
                                    {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $single_payment->id; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_payment->invoice_id; ?>
                                        </td>
                                        <td>
                                            <?php echo $this->db->get_where('mp_payee', array('id' => $single_payment->payee_id))->result_array()[0]['customer_name'] ; ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($single_payment->mode == 0)
                                                {
                                                    echo 'Paid';
                                                } 
                                                else
                                                {
                                                    echo 'Recieved';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $single_payment->total_paid; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_payment->method; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_payment->ref_no; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_payment->date; ?>
                                        </td>
                                        <td>
                                            <?php echo substr($single_payment->description,0,45); ?>..
                                        </td>
                                        <td>
                                            <div class="btn-group pull no-print pull-right">
                                                <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li >
                                                        <a href="<?php echo base_url('prints/receive_receipt/'.$single_payment->id); ?>">
                                                        <i class="fa fa-link"></i> Preview 
                                                        </a>
                                                    </li>
                                                    <?php

                                                    if($single_payment->attachment != 'default.jpg')
                                                    {
                                                    ?> 
                                                    <li ><a href="<?php echo base_url().'invoice/view_attachment_payment/'.$single_payment->id; ?>"><i class="fa fa-paperclip"></i> 
                                                        View Attachment </a>
                                                    </li> 
                                                    <?php 
                                                    }
                                                    ?>
                                                     <li ><a href="<?php echo base_url().'payee/sendmail/'.$single_payment->id; ?>"><i class="fa fa-envelope"></i> Mail Receipt </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
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
</section>
<script type="text/javascript">
    function search_transaction(period)
    {
        window.location = '<?php echo base_url('payee/payment_list/')?>'+period;
     
    }
</script>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends--> 