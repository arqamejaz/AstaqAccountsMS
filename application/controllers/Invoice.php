<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice extends CI_Controller

{
	function __construct()
	{
		parent::__construct();

		$user_id = $this->session->userdata('user_id');

		//TO AVOID USER TO ACCESS THE UNASSIGNED LINKS
		$class_name =  $this->router->fetch_class();

		$method_name =$this->router->fetch_method();

		$permission = check_allowed_access($user_id['id'],$class_name,$method_name);


		if($permission == 'access')
		{

		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i>No privilege available',
				'alert' => 'danger',
			);

			$this->session->set_flashdata('status', $array_msg);

			redirect('profile');
		}
	}

	// Invoice
	function index($period = '')
	{
		if ($period != '')
		{
			if ($period == 'month')
			{
				$date1 = date('Y-m') . '-1';
				$date2 = date('Y-m') . '-31';
			}
			else if ($period == 'three')
			{
				$month = date('m') - 2;
				$date1 = date('Y') . '-' . $month . '-1';
				$date2 = date('Y-m') . '-31';
			}
			else if ($period == 'year')
			{
				$year = date('Y');
				$date1 = $year . '-1-1';
				$date2 = $year . '-12-31';
			}
			else
			{
				$date1 = date('Y-m') . '-1';
				$date2 = date('Y-m') . '-31';
			}
		}
		else
		{
			$date1 = html_escape($this->input->post('date1'));
			$date2 = html_escape($this->input->post('date2'));
			if ($date1 == "" OR $date2 == "")
			{
				$date1 = date('Y-m') . '-1';
				$date2 = date('Y-m') . '-31';
			}
		}
		// DEFINES PAGE TITLE
		$data['title'] = 'Invoice List';
		// DEFINES NAME OF TABLE HEADING
		$data['table_name'] = 'Invoice  from ' . $date1 . ' to ' . $date2;
		// DEFINES BUTTON NAME ON THE TOP OF THE TABLE
		$data['page_add_button_name'] = 'Create Invoice';
		// DEFINES THE TITLE NAME OF THE POPUP
		$data['page_title_model'] = 'Create Invoice';
		// DEFINES THE NAME OF THE BUTTON OF POPUP MODEL
		$data['page_title_model_button_save'] = 'Save Invoice';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'invoicelist';
		// DEFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'Serial',
			'Invoice no',
			'Date',
			'Due Date',
			'Type',
			'Account',
			'Total',
			'Balance',
			'Created',
			'Status',
			'',
		);
		// PARAMETER 0 MEANS ONLY FETCH THAT RECORD WHICH IS VISIBLE 1 MEANS FETCH ALL
		$this->load->model('Crud_model');
		$data['invoice_record'] = $this->Crud_model->fetch_record_invoices($date1, $date2);
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// INVOICE/add_invoice_form
	function add_invoice_form()
	{
		$this->load->model('Crud_model');
		// DEFINES PAGE TITLE
		$data['title'] = 'Create invoice';
		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);
		// DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'create_invoice';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// INVOICE/edit_invoice_form
	function edit_invoice_form($invoice_id)
	{
		$this->load->model('Crud_model');
		// DEFINES PAGE TITLE
		$data['title'] = 'Update invoice';
		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);
		// DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');
		// PARENT DATA OF REFUND
		$data['parent_row'] = $this->Crud_model->fetch_record_by_id(' mp_invoices', $invoice_id);
		// CHILD DATA OF REFUND
		$data['child_row'] = $this->Crud_model->fetch_attr_record_by_id('mp_sales', 'invoice_id', $invoice_id);
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'edit_invoice';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Invoice/receive_payments
	// USED TO ADD PAYMENTS RECIEVED FROM PAYEE
	function receive_payments($payee_id = '')
	{
		$this->load->model('Crud_model');
		// DEFINES PAGE TITLE
		$data['title'] = 'Receive Payment ';
		if ($payee_id != '')
		{
			// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
			$data['invoice_list'] = $this->Crud_model->fetch_attr_record_by_id('mp_invoices', 'payee_id', $payee_id, NULL);
			$data['payee_id'] = $payee_id;
		}
		else
		{
			// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
			$data['invoice_list'] = NULL;
			$data['payee_id'] = NULL;
		}
		// DEFINE TO FETCH THE LIST OF BANK
		$data['bank_list'] = $this->Crud_model->fetch_record('mp_banks', 'status');
		// DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'receive_payments';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// INVOICE/get_payee_invoices
	// USED TO GET SINGLE PAYEE INVOICE THROGUH AJAX
	function get_payee_invoices($payee_id)
	{
		$this->load->model('Crud_model');
		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$payee_data = $this->Crud_model->fetch_attr_record_by_id('mp_invoices', 'payee_id', $payee_id, NULL);
		if ($payee_data != NULL)
		{
			$data['invoice_list'] = $payee_data;
			// model name available in admin models folder
			$this->load->view('admin_models/accounts/recieve_payment_row.php', $data);
		}
	}
	// INVOICE/popup
	// DEFINES A POPUP MODEL OG GIVEN PARAMETER
	function popup($page_name = '', $param = '')
	{
		$this->load->model('Crud_model');
		if ($page_name == 'add_product_model')
		{
			$data['redirect_link'] = 'invoice/add_invoice_form';
			$data['income_heads'] = $this->Crud_model->fetch_attr_record_by_id('mp_head', 'nature', 'Revenue');
			// model name available in admin models folder
			$this->load->view('admin_models/add_models/add_product_model.php', $data);
		}
		else if ($page_name == 'new_invoice_row')
		{
			$this->load->model('Crud_model');
			// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
			$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);
			// DEFINE TO FETCH THE LIST OF SUPPLIER
			$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');
			// model name available in admin models folder
			$this->load->view('admin_models/accounts/new_invoice_row.php', $data);
		}
	}
	// Invoice/view_attachment
	// USED TO VIEW ATTACHMENT
	function view_attachment($id)
	{
		$this->load->model('Crud_model');
		// FETCH THE PROVIED ID OF ATTACHMENT TO VIEW OR PRINT
		$invoice_data = $this->Crud_model->fetch_record_by_id('mp_invoices', $id);
		$data['heading'] = 'Attachment';
		// CONTROLLER LINK TO REDIRECT
		$data['controller_link'] = 'invoice';
		// BREADCRUMB NAME OR TITLE
		$data['controller_name'] = 'Invoice';
		// ATACHMENT IMAGE PATH
		$data['img_path'] = 'uploads/invoice/' . $invoice_data[0]->attachment;
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/print_attachment.php';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Invoice/view_attachment_payment
	// USED TO VIEW ATTACHMENT
	function view_attachment_payment($id)
	{
		$this->load->model('Crud_model');
		// FETCH THE PROVIED ID OF ATTACHMENT TO VIEW OR PRINT
		$payment_data = $this->Crud_model->fetch_record_by_id('mp_payee_payments', $id);
		$data['heading'] = 'Attachment';
		// CONTROLLER LINK TO REDIRECT
		$data['controller_link'] = 'payee/payment_list';
		// BREADCRUMB NAME OR TITLE
		$data['controller_name'] = 'Received payments';
		// ATACHMENT IMAGE PATH
		$data['img_path'] = 'uploads/receive_payments/' . $payment_data[0]->attachment;
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/print_attachment.php';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Invoice/add_invoice
	// USED TO ADD INVOICE INTO TABLE
	function add_invoice()
	{
		$this->load->model('Crud_model');
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$date = html_escape($this->input->post('date'));
		$due_date = html_escape($this->input->post('due_date'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = html_escape($this->input->post('descriptionarr'));
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		$single_tax = html_escape($this->input->post('single_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_tax = html_escape($this->input->post('total_tax'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$send_mail = html_escape($this->input->post('send_mail'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/invoice/");
		if (count($product) > 0 AND $total_bill > 0)
		{
			// $picture = html_escape($this->input->post('picture'));
			// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
			$this->load->model('Invoice_model');
			// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
			$args = array(
				'payee_id' => $payee_id,
				'billing_address' => $billing_address,
				'date' => ($date == NULL ? date('Y-m-d') : $date) ,
				'due_date' => ($due_date == NULL ? date('Y-m-d') : $due_date) ,
				'user' => $added_by,
				'product' => $product,
				'descriptionarr' => $descriptionarr,
				'qty' => $qty,
				'price' => $price,
				'single_tax' => $single_tax,
				'total_bill' => $total_bill,
				'total_tax' => $total_tax,
				'invoicemessage' => $invoicemessage,
				'memo' => $memo,
				'attachment' => $attachment,
			);
			// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
			$result = $this->Invoice_model->add_invoice_transaction($args);
			if ($result != NULL)
			{
				// SEND EMAIL
				if (isset($send_mail) == 1)
				{
					$this->sendmail($result['invoice_id'], 'avoid');
				}
				$array_msg = array(
					'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Created Successfully',
					'alert' => 'info',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i>  Cannot Created',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty invoice !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		redirect('invoice');
	}
	// Invoice/update_invoice
	// USED TO UPDATE INVOICE INTO TABLE
	function update_invoice()
	{
		$this->load->model('Crud_model');
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$date = html_escape($this->input->post('date'));
		$due_date = html_escape($this->input->post('due_date'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = html_escape($this->input->post('descriptionarr'));
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		$single_tax = html_escape($this->input->post('single_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_tax = html_escape($this->input->post('total_tax'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$invoice_id = html_escape($this->input->post('invoice_id'));
		$transaction_id = html_escape($this->input->post('transaction_id'));
		$send_mail = html_escape($this->input->post('send_mail'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/invoice/");
		if (count($product) > 0)
		{
			// $picture = html_escape($this->input->post('picture'));
			// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
			$this->load->model('Invoice_model');
			// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
			$args = array(
				'payee_id' => $payee_id,
				'billing_address' => $billing_address,
				'date' => ($date == NULL ? date('Y-m-d') : $date) ,
				'due_date' => ($due_date == NULL ? date('Y-m-d') : $due_date) ,
				'user' => $added_by,
				'product' => $product,
				'descriptionarr' => $descriptionarr,
				'qty' => $qty,
				'price' => $price,
				'single_tax' => $single_tax,
				'total_bill' => $total_bill,
				'total_tax' => $total_tax,
				'invoicemessage' => $invoicemessage,
				'memo' => $memo,
				'invoice_id' => $invoice_id,
				'transaction_id' => $transaction_id,
				'attachment' => $attachment,
			);
			if ($attachment != "default.jpg")
			{
				// DEFINES TO DELETE IMAGE FROM FOLDER PARAMETER REQIURES ARRAY OF IMAGE PATH AND ID
				$this->Crud_model->delete_image_custom('./uploads/invoice/', $invoice_id, 'attachment', 'mp_invoices');
			}
			// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
			$result = $this->Invoice_model->update_invoice_transaction($args);
			if ($result != NULL)
			{
				// SEND EMAIL
				if (isset($send_mail) == 1)
				{
					$this->sendmail($result['invoice_id'], 'avoid');
				}
				$array_msg = array(
					'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Updated Successfully',
					'alert' => 'info',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i>  Cannot updated',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty invoice !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		redirect('invoice');
	}
	// Invoice/add_received_payment
	// USED TO ADD PAYMENTS RECEIVED FROM PAYEE
	function add_received_payment()
	{
		$this->load->model('Crud_model');
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$date = html_escape($this->input->post('date'));
		$payment_method = html_escape($this->input->post('payment_method'));
		$ref_no = html_escape($this->input->post('ref_no'));
		$bank_id = html_escape($this->input->post('bank_id'));
		$payments = html_escape($this->input->post('payments'));
		$memo = html_escape($this->input->post('memo'));
		$invoice_id = html_escape($this->input->post('invoice_id'));
		$invoice_paid = html_escape($this->input->post('invoice_paid'));
		$invoice_bill = html_escape($this->input->post('invoice_bill'));
		// $send_mail          = html_escape($this->input->post('send_mail'));
		// $attachment      = $this->Crud_model->do_upload_picture("attachment","./uploads/receive_payments/");
		if ($payment_method == 'Cheque' AND $bank_id == 0)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Please select bank acccount',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
			redirect(base_url('invoice'));
		}
		$args = array(
			'payee_id' => $payee_id,
			'date' => ($date == NULL ? date('Y-m-d') : $date) ,
			'payment_method' => $payment_method,
			'ref_no' => $ref_no,
			'bank_id' => $bank_id,
			'payments' => $payments,
			'memo' => $memo,
			'invoice_id' => $invoice_id,
			'invoice_paid' => $invoice_paid,
			'invoice_bill' => $invoice_bill,
			'debithead' => ($payment_method == 'Cash' ? '2' : '16') ,
			'user' => $added_by,
			// 'attachment'      => $attachment
		);
		if (count($invoice_id) > 0)
		{
			// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
			$this->load->model('Invoice_model');
			// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
			$result = $this->Invoice_model->add_payment_transaction($args);
			if ($result != NULL)
			{
				$array_msg = array(
					'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Added Successfully',
					'alert' => 'info',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i>  Cannot added',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty payments !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		redirect('invoice');
	}
	// USED TO SEND EMAIL
	function sendmail($invoice_id, $avoid = '')
	{
		$this->load->model('Crud_model');
		$this->load->model('Email_model');
		$default_data = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$invoice_data = $this->Crud_model->fetch_record_by_id('mp_invoices', $invoice_id);
		$user_data = $this->Crud_model->fetch_record_by_id('mp_payee', $invoice_data[0]->payee_id);
		// MAILING INFO
		$mail_data = array(
			'company' => $default_data[0]->companyname,
			'customer_email' => $user_data[0]->cus_email,
			'sender_email' => $default_data[0]->email,
			'title' => 'INVOICE NO ' . $invoice_id . ' ' . $default_data[0]->companyname,
			'customer_name' => $user_data[0]->customer_name,
			'title1' => 'BALANCE DUE',
			'balance' => $default_data[0]->currency . ' ' . ($invoice_data[0]->total_bill - $invoice_data[0]->total_paid) ,
			'title2' => 'DUE DATE',
			'due_date' => $invoice_data[0]->due_date,
			'request_no' => $invoice_id,
			'logo' => base_url() . 'uploads/systemimgs/' . $default_data[0]->logo,
			'button_text' => 'View Invoice',
			'type' => 'INVOICE NO',
			'source' => 'invoice',
			'color' => $default_data[0]->primarycolor,
			'payee_id' => $invoice_data[0]->payee_id,
		);
		$result = $this->Email_model->email_request($mail_data);
		if ($result)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"/> Send successfully',
				'alert' => 'info',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"/> Cannot be Sent',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		if ($avoid == '')
		{
			redirect('invoice');
		}
	}
}
