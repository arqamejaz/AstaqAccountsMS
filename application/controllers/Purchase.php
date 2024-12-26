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
class Purchase extends CI_Controller
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

	// Purchase
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

			if ($date1 == "" OR $date2 == "") {
				$date1 = date('Y-m') . '-1';
				$date2 = date('Y-m') . '-31';
			}
		}

		// DEFINES PAGE TITLE
		$data['title'] = 'Purchase receipt';

		// DEFINES NAME OF TABLE HEADING
		$data['table_name'] = 'Purchase receipt  from ' . $date1 . ' to ' . $date2;

		// DEFINES BUTTON NAME ON THE TOP OF THE TABLE
		$data['page_add_button_name'] = 'Purchase receipt';

		// DEFINES THE TITLE NAME OF THE POPUP
		$data['page_title_model'] = 'Create purchase receipt';

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'purchaselist';

		// DEFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'Serial',
			'Purchase no',
			'Date',
			'Type',
			'Account',
			'Method',
			'Total',
			'Paid',
			'Created',
			'Status',
			'',
		);

		// PARAMETER 0 MEANS ONLY FETCH THAT RECORD WHICH IS VISIBLE 1 MEANS FETCH ALL
		$this->load->model('Crud_model');
		$data['purchases_record'] = $this->Crud_model->fetch_record_purchase($date1, $date2);

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	//Purchase/add_purchase_form
	function add_purchase_form()
	{
		$this->load->model('Crud_model');

		// DEFINES PAGE TITLE
		$data['title'] = 'Purchase receipt';

		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

		//DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('all', 'status');

		//DEFINE TO FETCH THE LIST OF BANK
		$data['bank_list'] = $this->Crud_model->fetch_record('mp_banks', 'status');

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'purchase_receipt';

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	//Purchase/edit_purchase_form
	function edit_purchase_form($purchase_id)
	{
		$this->load->model('Crud_model');

		// DEFINES PAGE TITLE
		$data['title'] = 'Edit purchase';

		//PARENT DATA OF REFUND
		$data['parent_row'] = $this->Crud_model->fetch_record_by_id('mp_purchase_receipt', $purchase_id);

		//CHILD DATA OF REFUND
		$data['child_row'] = $this->Crud_model->fetch_attr_record_by_id('mp_sub_purchase', 'sales_id', $purchase_id);

		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

		//DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('all', 'status');

		//DEFINE TO FETCH THE LIST OF BANK
		$data['bank_list'] = $this->Crud_model->fetch_record('mp_banks', 'status');

		//BANK TRANSACTION ID IF FOUND
		$data['bank_row'] = $this->Crud_model->fetch_attr_record_by_id('mp_bank_transaction', 'transaction_id', $data['parent_row'][0]->transaction_id);

		if ($data['bank_row'] != NULL) {
			$data['bank_balance'] = $this->Crud_model->check_available_balance($data['bank_row'][0]->bank_id);

		} else {
			$data['bank_balance'] = NULL;
			$data['bank_row'] = 0;
		}

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'edit_purchase_receipt';

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	//purchase/add_purchase
	//USED TO ADD PURCHASES INTO TABLE
	function add_purchase()
	{

		$this->load->model('Crud_model');

		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM
		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$bank_amount = html_escape($this->input->post('bank_amount'));
		$date = html_escape($this->input->post('date'));
		$payment_method = html_escape($this->input->post('payment_method'));
		$ref_no = html_escape($this->input->post('ref_no'));
		$bank_id = html_escape($this->input->post('bank_id'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = html_escape($this->input->post('descriptionarr'));
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		//$single_tax = html_escape($this->input->post('single_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_paid = html_escape($this->input->post('total_paid'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/purchase/");

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
				$this->load->model('Purchase_model');

				// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
				$args = array(
					'payee_id' => $payee_id,
					'billing_address' => $billing_address,
					'date' => ($date == NULL ? date('Y-m-d') : $date),
					'user' => $added_by,
					'payment_method' => $payment_method,
					'ref_no' => $ref_no,
					'bank_id' => $bank_id,
					'product' => $product,
					'descriptionarr' => $descriptionarr,
					'qty' => $qty,
					'price' => $price,
					'single_tax' => 0,
					'total_bill' => $total_bill,
					'total_paid' => $total_paid,
					'invoicemessage' => $invoicemessage,
					'memo' => $memo,
					'credithead' => ($payment_method == 'Cash' ? '2' : '16'),
					'attachment' => $attachment,
				);

				// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
				$result = $this->Purchase_model->add_purchase_receipt_transaction($args);
				if ($result != NULL) {

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
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty receipt !!',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}

		redirect('purchase');
	}

	//purchase/update_purchase
	//USED TO UPDATE PURCHASE INTO TABLE
	function update_purchase()
	{
		$this->load->model('Crud_model');

		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM

		$payee_id = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$date = html_escape($this->input->post('date'));
		$payment_method = html_escape($this->input->post('payment_method'));
		$bank_amount = html_escape($this->input->post('bank_amount'));
		$ref_no = html_escape($this->input->post('ref_no'));
		$bank_id = html_escape($this->input->post('bank_id'));
		$product = html_escape($this->input->post('product'));
		$descriptionarr = html_escape($this->input->post('descriptionarr'));
		$qty = html_escape($this->input->post('qty'));
		$price = html_escape($this->input->post('price'));
		$single_tax = html_escape($this->input->post('single_tax'));
		$total_bill = html_escape($this->input->post('total_bill'));
		$total_paid = html_escape($this->input->post('total_paid'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo = html_escape($this->input->post('memo'));
		$receipt_id = html_escape($this->input->post('receipt_id'));
		$transaction_id = html_escape($this->input->post('transaction_id'));
		$attachment = $this->Crud_model->do_upload_picture("attachment", "./uploads/purchase/");

		if ($payment_method == 'Cheque' AND $bank_amount <= 0) {
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Insufficient amount in account !!',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{

			if (count($product) > 0)
			{
				// $picture = html_escape($this->input->post('picture'));
				// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
				$this->load->model('Purchase_model');

				// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
				$args = array(
					'payee_id' => $payee_id,
					'billing_address' => $billing_address,
					'date' => ($date == NULL ? date('Y-m-d') : $date),
					'user' => $added_by,
					'payment_method' => $payment_method,
					'ref_no' => $ref_no,
					'bank_id' => $bank_id,
					'product' => $product,
					'descriptionarr' => $descriptionarr,
					'qty' => $qty,
					'price' => $price,
					'single_tax' => $single_tax,
					'total_paid' => $total_paid,
					'total_bill' => $total_bill,
					'invoicemessage' => $invoicemessage,
					'memo' => $memo,
					'transaction_id' => $transaction_id,
					'receipt_id' => $receipt_id,
					'credithead' => ($payment_method == 'Cash' ? '2' : '16'),
					'attachment' => $attachment,
				);

				if ($attachment != "default.jpg")
				{
					// DEFINES TO DELETE IMAGE FROM FOLDER PARAMETER REQIURES ARRAY OF IMAGE PATH AND ID
					$this->Crud_model->delete_image_custom('./uploads/purchase/', $receipt_id, 'attachment', 'mp_purchase_receipt');
				}

				// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
				$result = $this->Purchase_model->update_purchase_receipt_transaction($args);
				if ($result != NULL)
				{

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
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty receipt !!',
					'alert' => 'danger',
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}

		redirect('purchase');
	}

	//USED TO SEND EMAIL
	function sendmail($purchase_id, $avoid = '')
	{

		$this->load->model('Crud_model');

		$this->load->model('Email_model');

		$default_data = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);

		$receipt_data = $this->Crud_model->fetch_record_by_id('mp_purchase_receipt', $purchase_id);

		$user_data = $this->Crud_model->fetch_record_by_id('mp_payee', $receipt_data[0]->payee_id);

		//MAILING INFO
		$mail_data = array(
			'company' => $default_data[0]->companyname,
			'customer_email' => $user_data[0]->cus_email,
			'sender_email' => $default_data[0]->email,
			'title' => 'SALES NO ' . $purchase_id . ' ' . $default_data[0]->companyname,
			'customer_name' => $user_data[0]->customer_name,
			'title1' => 'TOTAL',
			'balance' => $default_data[0]->currency . ' ' . $receipt_data[0]->total_bill,
			'title2' => 'BALANCE DUE',
			'due_date' => $default_data[0]->currency . ' ' . '0',
			'request_no' => $purchase_id,
			'logo' => base_url() . 'uploads/systemimgs/' . $default_data[0]->logo,
			'button_text' => 'View receipt',
			'type' => 'SALES NO',
			'source' => 'sales receipt',
			'color' => $default_data[0]->primarycolor,
			'payee_id' => $receipt_data[0]->payee_id,
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
			redirect('sales');
		}

	}

	//purchase/popup
	//DEFINES A POPUP MODEL OG GIVEN PARAMETER
	function popup($page_name = '', $param = '')
	{
		$this->load->model('Crud_model');

		if ($page_name == 'add_product_model')
		{
			$data['redirect_link'] = 'purchase/add_purchase_form';

			$data['income_heads'] = $this->Crud_model->fetch_attr_record_by_id('mp_head', 'nature', 'Revenue');

			//model name available in admin models folder
			$this->load->view('admin_models/add_models/add_product_model.php', $data);
		}
		else if ($page_name == 'new_purhcase_row')
		{
			$this->load->model('Crud_model');
			// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
			$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

			//DEFINE TO FETCH THE LIST OF SUPPLIER
			$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');

			//model name available in admin models folder
			$this->load->view('admin_models/accounts/new_purhcase_row.php', $data);
		}
	}

	//Purchase/view_attachment
	//USED TO VIEW ATTACHMENT
	function view_attachment($id)
	{
		$this->load->model('Crud_model');

		$data['title'] = 'Purchase | Attachment';

		// FETCH THE PROVIED ID OF ATTACHMENT TO VIEW OR PRINT
		$purchase_data = $this->Crud_model->fetch_record_by_id('mp_purchase_receipt', $id);

		//CONTROLLER LINK TO REDIRECT
		$data['controller_link'] = 'purchase';

		//BREADCRUMB NAME OR TITLE
		$data['controller_name'] = 'Purchase';

		//ATACHMENT IMAGE PATH
		$data['img_path'] = 'uploads/purchase/' . $purchase_data[0]->attachment;

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/print_attachment.php';

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
}