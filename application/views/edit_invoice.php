<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb pull-left">
				<li>
						<a href="<?php echo base_url('homepage'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
				</li>
				<li>
					<a href="<?php echo base_url('invoice'); ?>"> Invoice</a>
				</li>
				<li class="active">Edit invoice</li>
		</ol>
	</div>
</div>
<div class="invoice">
	<section>
			<div class="row">
				<h4 class="purchase-heading">
					<i class="fa fa-plus-circle"></i> Update invoice
					<small>Update invoice and send it directly to account holder</small>
				</h4>
			</div>
	</section>
	<section class="content">
				<div class="box" id="print-section">
					<div class="box-body ">
						<?php
							$attributes = array('id'=>'update_invoice','method'=>'post','class'=>'');
						?>
						<?php echo form_open_multipart('invoice/update_invoice',$attributes); ?>
						<div class="row">
							 <div class="col-md-4 col-sm-12">
									<div class="form-group">
											<label>Account : </label>
											<select class="form-control select2 " name="payee_id" id="payee_id">
													<?php
													//category_names from mp_category table;
													if($payee_list != NULL)
													{
															foreach ($payee_list as $single_payee)
															{
													?>
																	 <option <?php echo ($parent_row[0]->payee_id == $single_payee->id) ? 'selected': ''; ?> value="<?php echo $single_payee->id; ?>" ><?php echo $single_payee->customer_name.' | '.$single_payee->cus_email; ?>
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
											<label>Billing address : </label>
											<?php
													$data = array('class'=>'form-control input-lg ','type'=>'text','name'=>'billing_address','value'=>$parent_row[0]->billing,'reqiured'=>'');
													echo form_input($data);
											?>
									</div>
							 </div>
							 <div class="col-md-4 col-sm-12">
									<div class="form-group">
											<?php echo form_label('Date :'); ?>
											<?php
													$data = array('class'=>'form-control input-lg ','type'=>'date','name'=>'date','value'=>$parent_row[0]->date,'reqiured'=>'');
													echo form_input($data);
											?>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
											<?php echo form_label('Due date :'); ?>
											<?php
													$data = array('class'=>'form-control input-lg ','type'=>'date','name'=>'due_date','value'=>$parent_row[0]->due_date,'reqiured'=>'');
													echo form_input($data);
											?>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
										<label class="check-to-email"> Mail invoice to customers:
											<?php
													$data = array('type'=>'checkbox','name'=>'send_mail','reqiured'=>'','value' => '');
													echo form_input($data);
											?>
										</label>
									</div>
								</div>
							</div>
							<div class="row">
									<div class="col-md-12 table-responsive">
											 <table class="table table-striped table-hover  ">
													 <thead class="purchase-heading">
														<tr>
															 <td class="col-md-2 ">Product/Service </td>
															 <td class="col-md-3 ">Description</td>
															 <td class="col-md-1 ">Quantity</td>
															 <td class="col-md-1 ">Price</td>
															 <td class="col-md-1 ">Tax</td>
															 <td class="col-md-1 ">Amount</td>
															 <td class="col-md-1">Action</td>
													 </tr>
													 </thead>
													 <tbody  id="transaction_table_body" >
													 <?php
															$total_tax = 0;
															$total_sub = 0;
																if($child_row != NULL)
																{
																	foreach ($child_row as $single_item)
																	{
																		$total_tax = $total_tax + ($single_item->qty*$single_item->tax);

																		$total_sub = $total_sub + ($single_item->qty*$single_item->price);
																	?>
																		<tr>
																			 <td>
																					<select class="form-control select2 "  name="product[]" id="product_name">
																								<option value="0" >Choose</option>
																								<?php
																								//category_names from mp_category table;
																								if($product_list != NULL)
																								{
																										foreach ($product_list as $single_product)
																										{
																								?>
																												<option <?php echo ($single_item->product_id == $single_product->id) ? 'selected': ''; ?>  data-price="<?php echo $single_product->price; ?>" data-description="<?php echo $single_product->description; ?>" data-tax="<?php echo $single_product->sale_tax; ?>" value="<?php echo $single_product->id; ?>" ><?php echo $single_product->product_name; ?>
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
																			 </td>
																				<td>
																						<?php
																								$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'descriptionarr[]','reqiured'=>'','id'=>'des_id','value'=>$single_item->description);
																								echo form_input($data);
																						?>
																			 </td>
																			 <td>
																						<?php
																								$data = array('class'=>'form-control input-lg qty','type'=>'number','name'=>'qty[]','id'=>'quantity_item','step'=>'.01','reqiured'=>'','value'=>$single_item->qty);
																								echo form_input($data);
																						?>
																			 </td>
																			 <td>
																						<?php
																								$data = array('class'=>'form-control input-lg price','type'=>'number','name'=>'price[]','id'=>'price','step'=>'.01','reqiured'=>'','value'=>$single_item->price);
																								echo form_input($data);
																						?>
																			 </td>
																			 <td>
																						<?php
																								$data = array('class'=>'form-control input-lg sales_tax','type'=>'number','name'=>'tax[]','id'=>'sales_tax','step'=>'.01','reqiured'=>'','readonly'=>'readonly','value'=>$single_item->qty*$single_item->tax);

																								echo form_input($data);
																								$data = array('class'=>'single_tax','type'=>'hidden','name'=>'single_tax[]','id'=>'single_tax','step'=>'.01','reqiured'=>'','value'=>$single_item->tax);
																								echo form_input($data);
																						?>
																			 </td>
																			 <td>
																						<?php
																								$data = array('class'=>'form-control input-lg item_Subtotal','type'=>'number','name'=>'subtotal[]','id'=>'amount','step'=>'.01','reqiured'=>'','value'=>$single_item->price*$single_item->qty);
																								echo form_input($data);
																						?>
																			 </td>
																			 <td>
																						<a  onclick="deleteParentElement(this)" href="javascript:void(0)">
																								<i class="fa fa-trash bill-times-icon" aria-hidden="true"></i>
																						</a>
																			 </td>
																		</tr>
																		<?php
																				}
																			}
																		?>
																 </tbody>
																 <tfoot>
																		<tr>
																	 <td colspan="5">
																			<button type="button" class="btn btn-primary btn-add-setting" name="addline" onclick="add_new_row('<?php echo base_url().'invoice/popup/new_invoice_row';?>')"> <i class="fa fa-plus-circle"></i>    Add a line
																			</button>
																			<button type="button" onclick="clearalllines()" class="btn btn-danger btn-add-setting" name="addline" onclick="add_new_row('<?php echo base_url().'expense/popup/new_bill_row';?>')"> <i class="fa fa-trash"></i>    Clear all lines
																			</button>
																	 </td>
																	 <td id="row_loading_status"></td>
															 </tr>
															<tr>
																 <td colspan="5"></td>
																 <td class=" expense-total-settings">Sub total</td>
																 <td>
																		 <?php
																			 $data = array('type'=>'number','name'=>'sub_total','step'=>'.01','value'=>$total_sub,'readonly'=>'readonly','class'=>'subtotal_amount bill-total-settings','reqiured'=>'');
																					echo form_input($data);
																			?>
																 </td>
															</tr>
															<tr>
																 <td colspan="5"></td>
																 <td class="expense-total-settings">Tax</td>
																 <td>
																		 <?php
																			 $data = array('type'=>'number','name'=>'total_tax','step'=>'.01','value'=>$total_tax,'readonly'=>'readonly','id'=>'taxfield','class'=>' bill-total-settings','reqiured'=>'');
																					echo form_input($data);
																			?>
																 </td>
															</tr>
															<tr>
																 <td colspan="5"></td>
																 <td class=" expense-total-settings">Total</td>
																 <td>
																		 <?php
																			 $data = array('type'=>'number','name'=>'total_bill','step'=>'.01','value'=>$total_sub+$total_tax,'readonly'=>'readonly','class'=>'total_bill bill-total-settings','reqiured'=>'');
																					echo form_input($data);
																			?>
																 </td>
															</tr>
															<tr>
																 <td colspan="5"></td>
																 <td class=" expense-total-settings">Balance due</td>
																 <td>
																		 <?php
																			 $data = array('type'=>'number','name'=>'balance_due','step'=>'.01','value'=>$parent_row[0]->total_bill - $parent_row[0]->total_paid,'readonly'=>'readonly','class'=>'balance_due bill-total-settings','reqiured'=>'');
																					echo form_input($data);
																			?>
																 </td>
															</tr>
														</tfoot>
											 </table>
											</div>
											<div class="col-md-5 ">
												<div class="form-group">
														<?php echo form_label('Message displayed on invoice :'); ?>
														<?php
																$data = array('class'=>'form-control input-lg ','type'=>'text','name'=>'invoicemessage','value'=>$parent_row[0]->invoicemessage,'reqiured'=>'');
																echo form_input($data);
														?>
												</div>
												<div class="form-group">
														<?php echo form_label('Message displayed on statement :'); ?>
														<?php
																$data = array('class'=>'form-control input-lg ','type'=>'text','name'=>'memo','reqiured'=>'','value'=>$parent_row[0]->description);
																echo form_input($data);
														?>
												</div>
												<div class="form-group">
													<div class="border-setting">
															<label> <i class="fa fa-paperclip" aria-hidden="true" ></i> Attachments  Maximum size: 25MB</label>
																<?php
																		$data = array('class'=>'input-lg ','type'=>'file','name'=>'attachment','reqiured'=>'');
																		echo form_input($data);

																		$data = array('class'=>'','type'=>'hidden','name'=>'transaction_id','value'=>$parent_row[0]->transaction_id);
																		echo form_input($data);

																		$data = array('class'=>'','type'=>'hidden','name'=>'invoice_id','value'=>$parent_row[0]->id);
																		echo form_input($data);
																?>
													</div>
												</div>
											</div>
											<div class="col-md-7">
												<span class="pull-right">
													<img class="img-setting" src="<?php echo base_url('uploads/invoice/').$parent_row[0]->attachment;?>" >
												</span>
											</div>
											<div class="col-md-12 ">
													<div class="form-group">
															<center>
															<?php
																	$data = array('class'=>'btn btn-info  margin btn-lg  ','type' => 'submit','name'=>'btn_submit_customer','value'=>'true','id'=>'btn_save_transaction','content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i>
																			Update invoice');
																	echo form_button($data);
															 ?>
															 </center>
													</div>
											</div>
											<?php echo form_close(); ?>
										</div>
								</div>
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

	function deleteParentElement(n)
	{
		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		calculateSubTotal();
	}

		//CALCAULATE AND ASSIGN WHEN PRODUCT NAME IS SELECTED AND SET ITS VALUE TO TEXT BOX
	 $('body').delegate('#product_name', 'change', function(n) {

		// var tableRow = $(this).parent().parent();
		var price = $(this).find(':selected').data('price');

		var tax = $(this).find(':selected').data('tax');

		var description = $(this).find(':selected').data('description');

		var tableRow = $(this).parent().parent();

		tableRow.find('#price').val(price);

		tableRow.find('#des_id').val(description);

		tableRow.find('#quantity_item').val(1);

		tableRow.find('#amount').val(1*price);

		var  taxamount = CalculatedAmountTax(1*price,tax);

		tableRow.find('.sales_tax').val(taxamount.toFixed(2));

		tableRow.find('.single_tax').val(taxamount.toFixed(2));

		calculateSubTotal();

	 });

	 //CALCAULATE AND ASSIGN WHEN QUANTITY NAME IS SELECTED AND SET ITS VALUE TO TEXT BOX
	 $('body').delegate('#quantity_item, #price', 'keyup', function(n) {

		var tableRow = $(this).parent().parent();

		var tax = tableRow.find('.single_tax').val();

		var quantity_item = tableRow.find('#quantity_item').val();

		var price = tableRow.find('#price').val();

		var  taxamount = quantity_item*tax;

		tableRow.find('.sales_tax').val(taxamount.toFixed(2));

		tableRow.find('#amount').val(quantity_item*price);

		calculateSubTotal();

	 });

	 function calculateSubTotal()
	 {
				var totalGrossAmount = 0;
				var totalTaxAmount = 0;
				$('.item_Subtotal').each(function(i, e) {
						var subAmount = $(this).val() - 0;
						totalGrossAmount += subAmount;
				});

				$('.sales_tax').each(function(i, e)
				{
						var tax_Amount = $(this).val() - 0;
						totalTaxAmount += tax_Amount;
				});

				$('.subtotal_amount').val(totalGrossAmount.toFixed(2));
				$('#taxfield').val(totalTaxAmount.toFixed(2));
				$('.total_bill').val((totalGrossAmount+totalTaxAmount).toFixed(2));
				$('.balance_due').val((totalGrossAmount+totalTaxAmount).toFixed(2));
	 }

		function CalculatedAmountTax(retail,tax)
		{
			 return (retail / 100) * tax;
		}

	 function clearalllines()
	 {
			$('#transaction_table_body').html('');

			calculateSubTotal();
	 }
</script>