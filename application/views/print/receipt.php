<section class="content-header">
    <div class="row">
        <ol class="breadcrumb pull-right">
            <li>
                <a href="<?php echo base_url('homepage'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
              <a href="<?php echo base_url('payee/payment_list'); ?>"> Payments</a>
            </li>
            <li class="active">Print payments</li>
        </ol>
    </div> 
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-sm pull-right "><i class="fa fa-print pull-left"></i> Print Report
                </button>
            </div>
        </div>        
    </div> 
    <hr />
</section>
<section class="content" id="print-section">
    <div class="invoice invoice-body">
        <div class="row">
           <div class="col-md-4 col-sm-4 pull-left">
               <h3><?php echo $default_data[0]->companyname; ?></h3>
               <h4><?php echo $default_data[0]->address; ?></h4>
               <h4><?php echo $default_data[0]->email; ?></h4>
               <h4><?php echo $default_data[0]->contact; ?></h4>
           </div>
           <div class="col-md-4 col-sm-4 pull-right">
               <img class="print-logo-size pull-right" src="<?php echo base_url('uploads/systemimgs/'.$default_data[0]->logo); ?>" />
           </div>
        </div>  
        <div class="row"> 
           <div class="col-md-12">
                <h2 class="invoice-title"><b>RECEIVE RECEIPT</b></h2>
           </div> 
       </div> 
       <div class="row set-border-bottom"> 
           <div class="col-md-4 col-sm-4 pull-left">
                <h3 ><b>RECEIVED FROM </b></h3>
                <h4 > <?php echo $user_data[0]->customer_name; ?></h4>
           </div>  
           <div class="col-md-4 col-sm-4 pull-right">
              <span class="pull-right">
                <h4 ><b class="invoice-heading"> DATE  </b> <span class="pull-right" > <?php echo $receipts_data[0]->date; ?> </span></h4>
                <h4 ><b class="invoice-heading"> METHOD   </b> <span class="pull-right" > <?php echo $receipts_data[0]->method; ?> </span></h4>
                <h4 ><b class="invoice-heading"> REF No   </b> <span class="pull-right" > <?php echo $receipts_data[0]->ref_no; ?> </span></h4>
              </span>
           </div> 
          
       </div>
       <?php 

        $subtotal = 0;
        $tax = 0;

        if($receipts_data != NULL)
        {
        ?>
       <div class="row">
           <div class="col-md-12">
               <table class="table table-striped table-hover">
                   <tr class="table-invoice-row">
                       <th>RECEIPT NO</th>
                       <th>FOR INVOICE NO</th>
                       <th>DATE</th>
                       <th>DUE DATE</th>
                       <th>ORIGNAL AMOUNT</th>
                       <th>BALANCE</th>
                       <th>PAYMENT</th>
                   </tr>
                   <?php 
                    foreach ($receipts_data  as $receipt) 
                    {
                        
                    ?>
                   <tr>
                       <td><b><?php echo $receipt->id; ?></b></td>
                       <td><b><?php echo $receipt->invoice_id; ?></b></td>
                       <td><?php echo $receipt->date; ?></td>
                       <td><?php echo $receipt->due_date; ?></td>
                       <td><?php echo $receipt->total_bill; ?></td>
                       <td><?php echo $receipt->total_bill - $receipt->total_paid; ?></td>
                       <td><?php echo $receipt->total_paid; ?></td>
                   </tr>
                   <?php 
                    }
                   ?>
               </table>
           </div>
       </div>
       <?php 
        }
        ?>
       <div class="row">
           <div class="col-md-12">
               <p>Memo : <?php echo $receipts_data[0]->description; ?></p>
           </div>
       </div>       
       <div class="row">
           <div class="col-md-3 signature-area pull-right">
               <p>Signature: ____________________ </p>
           </div>
       </div>
       <div class="row">
           <div class="col-md-12">
               <p class="text-center"><i>Receipt generated from Bedana accounting software</i></p>
           </div>
       </div>
    </div>
</section>