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
class Refund_receipt extends CI_Controller

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

	// Refund_receipt
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
		$data['title'] = 'Refund List';
		// DEFINES NAME OF TABLE HEADING
		$data['table_name'] = 'Refund receipt  from ' . $date1 . ' to ' . $date2;
		// DEFINES BUTTON NAME ON THE TOP OF THE TABLE
		$data['page_add_button_name'] = 'Refund Receipt';
		// DEFINES THE TITLE NAME OF THE POPUP
		$data['page_title_model'] = 'Refund Receipt';
		// DEFINES THE NAME OF THE BUTTON OF POPUP MODEL
		$data['page_title_model_button_save'] = 'Save Receipt';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'refundreceipt';
		// DEFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'Serial',
			'Refund Id',
			'Receipt date',
			'Payment method',
			'Type',
			'Account',
			'Total',
			'Refunded',
			'Created',
			'Status',
			'',
		);
		// PARAMETER 0 MEANS ONLY FETCH THAT RECORD WHICH IS VISIBLE 1 MEANS FETCH ALL
		$this->load->model('Crud_model');
		$data['refund_record'] = $this->Crud_model->fetch_record_refund($date1, $date2);
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Refund_receipt/add_refund
	function add_refund()
	{
		$this->load->model('Crud_model');
		// DEFINES PAGE TITLE
		$data['title'] = 'Create refund';
		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);
		// DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('all', 'status');
		// DEFINE TO FETCH THE LIST OF BANK
		$data['bank_list'] = $this->Crud_model->fetch_record('mp_banks', 'status');
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'create_refund';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Estimate/add_refund_receipt
	// USED TO ADD REFUND INTO THE TABLE
	function add_refund_receipt()
	{
		$this->load->model('Crud_model');
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$bank_id = html_escape($this->input->post('bank_id'));
		$date = html_escape($this->input->post('refund_receipt_date'));
		$payment_method = html_escape($this->input->post('payment_method'));
		$ref_no = html_escape($this->input->post('ref_no'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = $this->input->post('descriptionarr');
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		$single_tax = html_escape($this->input->post('single_tax'));
		$total_tax = html_escape($this->input->post('total_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_refunded = html_escape($this->input->post('total_refunded'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$bank_amount = html_escape($this->input->post('bank_amount'));
		$send_mail = html_escape($this->input->post('send_mail'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/refund_reciept/");
		if ($payment_method == 'Cheque' AND $bank_id == 0)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Please select bank acccount',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
			redirect(base_url('refund_receipt'));
		}
		if ($payment_method == 'Cheque' AND $bank_amount <= 0)
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Unsufficient amount in account !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{
			if (count($product) > 0 AND $total_bill > 0)
			{
				// $picture = html_escape($this->input->post('picture'));
				// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
				$this->load->model('Refund_model');
				// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
				$args = array(
					'payee_id' => $payee_id,
					'billing' => $billing_address,
					'date' => ($date == NULL ? date('Y-m-d') : $date) ,
					'payment_method' => $payment_method,
					'user' => $added_by,
					'product' => $product,
					'descriptionarr' => $descriptionarr,
					'qty' => $qty,
					'price' => $price,
					'single_tax' => $single_tax,
					'total_bill' => $total_bill,
					'total_tax' => $total_tax,
					'total_refunded' => $total_refunded,
					'invoicemessage' => $invoicemessage,
					'memo' => $memo,
					'ref_no' => $ref_no,
					'bank_id' => $bank_id,
					'credithead' => ($payment_method == 'Cash' ? '2' : '16') ,
					'attachment' => $attachment,
				);
				// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
				$result = $this->Refund_model->add_refund_transaction($args);
				if ($result != NULL)
				{
					// SEND EMAIL
					if (isset($send_mail) == 1)
					{
						$this->sendmail($result['refund_id'], 'avoid');
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
						'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Sorry cannot created',
						'alert' => 'danger',
					);
					$this->session->set_flashdata('status', $array_msg);
				}
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty estimate !!',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		redirect('refund_receipt');
	}
	// USED TO SHOW EDIT REFUND
	// Credit_note/edit_refund
	function edit_refund($refund_id)
	{
		// DEFINES PAGE TITLE
		$data['title'] = 'Edit refund';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'edit_refund_receipt';
		$this->load->model('Crud_model');
		// PARENT DATA OF REFUND
		$data['parent_row'] = $this->Crud_model->fetch_record_by_id(' mp_refund', $refund_id);
		// CHILD DATA OF REFUND
		$data['child_row'] = $this->Crud_model->fetch_attr_record_by_id('mp_refund_sales', 'refund_id', $refund_id);
		// BANK TRANSACTION ID IF FOUND
		$data['bank_row'] = $this->Crud_model->fetch_attr_record_by_id(' mp_bank_transaction', 'transaction_id', $data['parent_row'][0]->transaction_id);
		if ($data['bank_row'] != NULL)
		{
			$data['bank_balance'] = $this->Crud_model->check_available_balance($data['bank_row'][0]->bank_id);
		}
		else
		{
			$data['bank_balance'] = NULL;
			$data['bank_row'] = 0;
		}
		// DEFINES TO LOAD THE LIST OF PRODUCTS FROM TABLE
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);
		// DEFINE TO FETCH THE LIST OF CUSTOMERS OR PAYEE
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('all', 'status');
		// DEFINE TO FETCH THE LIST OF BANK
		$data['bank_list'] = $this->Crud_model->fetch_record('mp_banks', 'status');
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// USED TO UPDATE REFUND AMOUNT
	function update_refund_receipt()
	{
		$this->load->model('Crud_model');
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$bank_id = html_escape($this->input->post('bank_id'));
		$date = html_escape($this->input->post('refund_receipt_date'));
		$payment_method = html_escape($this->input->post('payment_method'));
		$ref_no = html_escape($this->input->post('ref_no'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = $this->input->post('descriptionarr');
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		$single_tax = html_escape($this->input->post('single_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_tax = html_escape($this->input->post('total_tax'));
		$total_refunded = html_escape($this->input->post('total_refunded'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$bank_amount = html_escape($this->input->post('bank_amount'));
		$transaction_id = html_escape($this->input->post('transaction_id'));
		$refund_id = html_escape($this->input->post('refund_id'));
		$send_mail = html_escape($this->input->post('send_mail'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/refund_reciept/");
		if ($payment_method == 'Cheque' AND $bank_id == 0)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Please select bank acccount',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
			redirect(base_url('refund_receipt'));
		}
		if ($payment_method == 'Cheque' AND $bank_amount <= 0)
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Unsufficient amount in account !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{
			if (count($product) > 0)
			{
				$this->load->model('Refund_model');
				// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
				$args = array(
					'payee_id' => $payee_id,
					'billing' => $billing_address,
					'date' => ($date == NULL ? date('Y-m-d') : $date) ,
					'payment_method' => $payment_method,
					'user' => $added_by,
					'product' => $product,
					'descriptionarr' => $descriptionarr,
					'qty' => $qty,
					'price' => $price,
					'single_tax' => $single_tax,
					'total_bill' => $total_bill,
					'total_refunded' => $total_refunded,
					'invoicemessage' => $invoicemessage,
					'total_tax' => $total_tax,
					'memo' => $memo,
					'ref_no' => $ref_no,
					'bank_id' => $bank_id,
					'credithead' => ($payment_method == 'Cash' ? '2' : '16') ,
					'transaction_id' => $transaction_id,
					'refund_id' => $refund_id,
					'attachment' => $attachment,
				);
				if ($attachment != "default.jpg")
				{
					// DEFINES TO DELETE IMAGE FROM FOLDER PARAMETER REQIURES ARRAY OF IMAGE PATH AND ID
					$this->Crud_model->delete_image_custom('./uploads/refund_reciept/', $refund_id, 'attachment', 'mp_refund');
				}
				// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
				$result = $this->Refund_model->update_refund_transaction($args);
				if ($result != NULL)
				{
					// SEND EMAIL
					if (isset($send_mail) == 1)
					{
						$this->sendmail($result['refund_id'], 'avoid');
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
						'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Sorry cannot created',
						'alert' => 'danger',
					);
					$this->session->set_flashdata('status', $array_msg);
				}
			}
		}
		redirect('refund_receipt');
	}
	// USED TO SEND EMAIL
	function sendmail($refund_id, $avoid = '')
	{
		$this->load->model('Crud_model');
		$this->load->model('Email_model');
		$default_data = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$refund_data = $this->Crud_model->fetch_record_by_id('mp_refund', $refund_id);
		$user_data = $this->Crud_model->fetch_record_by_id('mp_payee', $refund_data[0]->payee_id);
		// MAILING INFO
		$mail_data = array(
			'company' => $default_data[0]->companyname,
			'customer_email' => $user_data[0]->cus_email,
			'sender_email' => $default_data[0]->email,
			'title' => 'RECEIPT NO ' . $refund_id . ' ' . $default_data[0]->companyname,
			'customer_name' => $user_data[0]->customer_name,
			'title1' => 'TOTAL',
			'balance' => $default_data[0]->currency . ' ' . $refund_data[0]->total_bill,
			'title2' => 'METHOD',
			'due_date' => $refund_data[0]->method,
			'request_no' => $refund_id,
			'logo' => base_url() . 'uploads/systemimgs/' . $default_data[0]->logo,
			'button_text' => 'View receipt',
			'type' => 'RECEIPT NO',
			'source' => 'refund',
			'color' => $default_data[0]->primarycolor,
			'payee_id' => $refund_data[0]->payee_id,
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
			redirect('refund_receipt');
		}
	}
	// Refund_receipt/popup
	// DEFINES A POPUP MODEL OG GIVEN PARAMETER
	function popup($page_name = '', $param = '')
	{
		$this->load->model('Crud_model');
		if ($page_name == 'add_product_model')
		{
			$data['redirect_link'] = 'refund_receipt/add_refund';
			$data['income_heads'] = $this->Crud_model->fetch_attr_record_by_id('mp_head', 'nature', 'Revenue');
			// model name available in admin models folder
			$this->load->view('admin_models/add_models/add_product_model.php', $data);
		}
	}
	// Refund_receipt/view_attachment
	// USED TO VIEW ATTACHMENT
	function view_attachment($id)
	{
		$this->load->model('Crud_model');
		// FETCH THE PROVIED ID OF ATTACHMENT TO VIEW OR PRINT
		$refund_data = $this->Crud_model->fetch_record_by_id('mp_refund', $id);
		// CONTROLLER LINK TO REDIRECT
		$data['controller_link'] = 'refund_receipt';
		// BREADCRUMB NAME OR TITLE
		$data['controller_name'] = 'Refund receipt';
		// ATACHMENT IMAGE PATH
		$data['img_path'] = 'uploads/refund_reciept/' . $refund_data[0]->attachment;
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/print_attachment.php';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
}
