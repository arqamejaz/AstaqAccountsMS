<div class="row">
	<div class="col-md-12">
				<ol class="breadcrumb pull-left">
						<li>
								<a href="<?php echo base_url('homepage'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
						</li>
						<li>
							<a href="<?php echo base_url('invoice'); ?>"> Invoice</a>
						</li>
						<li class="active">Receive payments</li>
				</ol>
	</div>
</div>
<div class="invoice">
	<section>
			<div class="row">
				<h4 class="purchase-heading">
					<i class="fa fa-plus-circle"></i> Receive Payment
					<small>Receive payments from your customer and manage it automatically
							<span class="pull-right bank-section-details">
										Available balance :
										PKR <span id="available_balance">0</span>
							</span>
					</small>

				</h4>
			</div>
	</section>
	<section class="content">
			<div class="box" id="print-section">
				<div class="box-body ">
					<?php
							$attributes = array('id'=>'add_received_payment','method'=>'post','class'=>'');
					?>
					<?php echo form_open_multipart('invoice/add_received_payment',$attributes); ?>
					<div class="row">
						 <div class="col-md-4 col-sm-12">
								<div class="form-group">
										<label>Customer : </label>
										<select class="form-control select2" name="payee_id" id="payee_id">
											<option value="0"> Choose </option>
												<?php
												//category_names from mp_category table;
												if($payee_list != NULL)
												{
														foreach ($payee_list as $single_payee)
														{
												?>
																<option <?php echo ($payee_id == $single_payee->id ? 'selected' : ''); ?> value="<?php echo $single_payee->id; ?>" ><?php echo $single_payee->customer_name.' | '.$single_payee->cus_email; ?>
																</option>
												<?php
																}
														}
														else
														{
																echo "No Record Found";
														}
												?>
										</select>
								</div>
						 </div>
						<div class="col-md-4 col-sm-12">
								<div class="form-group">
										<?php echo form_label('Payment  date :'); ?>
										<?php
												$data = array('class'=>'form-control input-lg ','type'=>'date','name'=>'date','value'=>date('Y-m-d'));
												echo form_input($data);
										?>
								</div>
						</div>
						<div class="col-md-4 col-sm-12">
								<div class="form-group">
										<?php echo form_label('Payment  method :'); ?>
										<select class="form-control input-lg" id="payment_method" name="payment_method">
												<option value="Cash">Cash</option>
												<option value="Cheque">Cheque</option>
										</select>
								</div>
						</div>
						<div class="col-md-4 col-sm-12">
							 <div class="form-group">
										<?php echo form_label('Reference no :'); ?>
										<?php
												$data = array('class'=>'form-control input-lg ','type'=>'text','name'=>'ref_no','reqiured'=>'');
												echo form_input($data);
										?>
								</div>
						</div>
						<div class="col-md-4 col-sm-12 bank-section-details">
								<div class="form-group">
										<label>Deposited to : </label>

										<select class="form-control input-lg" name="bank_id" id="bank_id">
											<option value="0" > Choose </option>
												<?php
												//category_names from mp_category table;
												if($bank_list != NULL)
												{
														foreach ($bank_list as $bank)
														{
												?>
															<option value="<?php echo $bank->id; ?>" >
																<?php echo $bank->bankname.' | '.$bank->branch.' | '.$bank->title; ?>
															</option>
											<?php
																}
														}
														else
														{
																echo "No Record Found";
														}
												?>
										</select>
								</div>
						</div>
					</div>
					<hr>
					<div class="row" id="transaction_table_body">
						<?php
				
							if($invoice_list != NULL)
							{
						?>
						<div class="col-md-12 table-responsive">
							<h3>Outstanding Transactions</h3>
								 <table class="table table-striped table-hover  ">
										 <thead class="purchase-heading">
											<tr>
												 <td class="col-md-8 ">Description</td>
												 <td class="col-md-1 ">Due date</td>
												 <td class="col-md-1 ">Total</td>
												 <td class="col-md-1 ">Amount due</td>
												 <td class="col-md-1 ">Payment</td>
										 </tr>
										 </thead>
										 <tbody   >
												<?php
													foreach ($invoice_list as $single_invoice)
													{
												?>
												<tr>
													 <td>
															<a href="javascript:void(0)">  Invoice # <?php echo $single_invoice->id.' ('.$single_invoice->date.')'; ?> </a>
													 </td>
														<td>
															 <?php echo $single_invoice->due_date; ?>
													 </td>
													 <td>
																<?php echo $single_invoice->total_bill; ?>
													 </td>
													 <td>
																<?php echo $single_invoice->total_bill-$single_invoice->total_paid; ?>
													 </td>
													 <td>
															 <input type="number" value="0" class="form-control  total_payment_received" name="payments[]" step=".01" />

															 <input type="hidden" value="<?php echo $single_invoice->id; ?>" name="invoice_id[]"  />

															 <input type="hidden" value="<?php echo $single_invoice->total_paid; ?>" name="invoice_paid[]"  />

															 <input type="hidden" value="<?php echo $single_invoice->total_bill; ?>" name="invoice_bill[]"  />
													 </td>
												</tr>
												<?php
													}
												?>
										 </tbody>
										 <tfoot>
												<tr>
														 <td colspan="5">
																<button type="button" onclick="clearalllines()" class="btn btn-primary btn-add-setting pull-right" name="addline" onclick="add_new_row('<?php echo base_url().'expense/popup/new_bill_row';?>')"> <i class="fa fa-trash"></i>    Clear payments
																</button>
														 </td>
												 </tr>
											 </tfoot>
								 </table>
								</div>
								<?php
									}
								?>
							</div>
							<div class="row">
								<div class="col-md-5 ">
										<div class="form-group">
												<?php echo form_label('Memo :'); ?>
												<?php
														$data = array('class'=>'form-control input-lg ','type'=>'text','name'=>'memo','reqiured'=>'');
														echo form_input($data);
												?>
										</div>
								</div>
							 </div>
							 <div class="row">
								<div class="col-md-12 ">
										<div class="form-group">
												<center>
												<?php
														$data = array('class'=>'btn btn-info  margin btn-lg  ','type' => 'submit','name'=>'btn_submit_customer','value'=>'true','id'=>'btn_save_transaction','content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i>
																Save payment');
														echo form_button($data);
												 ?>
												 </center>
										</div>
							</div>
						<?php echo form_close(); ?>
					</div>
			</div>
	</section>
</div>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends-->
<script type="text/javascript">
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});

	 //CALCAULATE AND ASSIGN WHEN QUANTITY NAME IS SELECTED AND SET ITS VALUE TO TEXT BOX
	 $('body').delegate('.total_payment_received', 'keyup', function(n) {

		calculateSubTotal();

	 });

	 function calculateSubTotal()
	 {
				var totalAmount = 0;
				$('.total_payment_received').each(function(i, e) {
						var amount = $(this).val() - 0;
						totalAmount += amount;
				});
	 }


	 function clearalllines()
	 {
			 $('.total_payment_received').each(function(i, e) {
						$(this).val('0');
				});


			calculateSubTotal();
	 }

		$('#payment_method').change(function(){
		var method = $('#payment_method').val();
		if(method == 'Cheque')
		{
			$('.bank-section-details').css('display','block');
		}
		else
		{
			$('.bank-section-details').css('display','none');
		}
		});


	$('#bank_id').change(function(){
	var bank_id = $('#bank_id').val();
	if(bank_id != 0)
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax({
					url: '<?php echo base_url('bank/check_available_balance/'); ?>'+bank_id,
					success: function(response)
					{
							$('#available_balance').html(response);
							$('#save_available_balance').val(response);
					}
			});

		$('#bank-cheque-no').css('display','block');
	}
});

	$('#payee_id').change(function()
	{
		var payee_id = $('#payee_id').val();
		if(payee_id != 0)
		{
			// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax({
						url: '<?php echo base_url('invoice/get_payee_invoices/'); ?>'+payee_id,
						success: function(response)
						{
								$('#transaction_table_body').html(response);
						}
				});
		}
		$('#show_total_payment_received').html(0);
	});
</script>