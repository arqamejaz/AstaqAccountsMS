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
class Credit_note extends CI_Controller
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
		$data['title'] = 'Credit notes';

		// DEFINES NAME OF TABLE HEADING
		$data['table_name'] = 'Credit note  from ' . $date1 . ' to ' . $date2;

		// DEFINES BUTTON NAME ON THE TOP OF THE TABLE
		$data['page_add_button_name'] = 'Credit note';

		// DEFINES THE TITLE NAME OF THE POPUP
		$data['page_title_model'] = 'Credit note';

		// DEFINES THE NAME OF THE BUTTON OF POPUP MODEL
		$data['page_title_model_button_save'] = 'Save Note';

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'creditlist';

		// DEFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'Serial no',
			'Credit id',
			'Date',
			'Type',
			'Account',
			'Total',
			'Created',
			'Status',
			'Action'
		);

		// PARAMETER 0 MEANS ONLY FETCH THAT RECORD WHICH IS VISIBLE 1 MEANS FETCH ALL
		$this->load->model('Crud_model');
		$data['credit_record'] = $this->Crud_model->fetch_record_credit($date1, $date2);

		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

		//DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}


	//Credit_note/credit_notes
	//USED TO CREATE A CREDIT NOTE
	function credit_notes()
	{
		// DEFINES PAGE TITLE
		$data['title'] = 'Credit notes';

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'create_credit_note';

		// DEFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'No',
			'Receipt date',
			'Payment method',
			'Type',
			'Refund Id',
			'Customer',
			'Total',
			'Created',
			'Status',
			'Action'
		);

		$this->load->model('Crud_model');

		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

		//DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}


	//Credit_note/add_credit_note
	//USED TO ADD CREDIT INTO THE TABLE
	function add_credit_note()
	{
		$this->load->model('Crud_model');

		$user_name = $this->session->userdata('user_id');
		$added_by  = $user_name['name'];
		// DEFINES READ medicine details FORM medicine FORM

		$payee_id = html_escape($this->input->post('payee_id'));

		$billing_address = html_escape($this->input->post('billing_address'));

		$credit_date = html_escape($this->input->post('credit_date'));

		$product        = html_escape($this->input->post('product'));
		$descriptionarr = html_escape($this->input->post('descriptionarr'));
		$qty            = html_escape($this->input->post('qty'));
		$price          = html_escape($this->input->post('price'));
		$single_tax     = html_escape($this->input->post('single_tax'));
		$total_bill     = html_escape($this->input->post('total_bill'));
		$invoicemessage = html_escape($this->input->post('invoicemessage'));
		$memo           = html_escape($this->input->post('memo'));
		$send_mail      = html_escape($this->input->post('send_mail'));
		$attachment     = $this->Crud_model->do_upload_picture("attachment", "./uploads/credit_note/");

		if (count($product) > 0 AND $total_bill > 0)
		{
			// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
			$this->load->model('Credit_model');

			// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
			$args = array(
				'payee_id' => $payee_id,
				'date' => ($credit_date == NULL ? date('Y-m-d') : $credit_date),
				'user' => $added_by,
				'billing_address' => $billing_address,
				'product' => $product,
				'descriptionarr' => $descriptionarr,
				'qty' => $qty,
				'price' => $price,
				'single_tax' => $single_tax,
				'total_bill' => $total_bill,
				'invoicemessage' => $invoicemessage,
				'memo' => $memo,
				'credithead' => 4, //A/R,
				'attachment' => $attachment
			);


			// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
			$result = $this->Credit_model->add_credit_transaction($args);

			if ($result != NULL)
			{
				//SEND EMAIL
				if (isset($send_mail) == 1)
				{
					$this->sendmail($result['credit_id'], 'avoid');
				}

				$array_msg = array(
					'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Created Successfully',
					'alert' => 'info'
				);
				$this->session->set_flashdata('status', $array_msg);
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Sorry cannot created',
					'alert' => 'danger'
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty note !!',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
		}

		redirect('credit_note');
	}

	//USED TO SHOW EDIT NOTE
	//Credit_note/edit_note
	function edit_note($note_id)
	{

		// DEFINES PAGE TITLE
		$data['title'] = 'Edit credit notes';

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'edit_credit_note';

		$this->load->model('Crud_model');

		//PARENT DATA OF CREDIT NOTE
		$data['parent_row'] = $this->Crud_model->fetch_record_by_id('mp_credit_note', $note_id);

		//CHILD DATA OF CREDIT NOTE
		$data['child_row'] = $this->Crud_model->fetch_attr_record_by_id('mp_credit_sales', 'credit_id', $note_id);

		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_supplier
		$data['product_list'] = $this->Crud_model->fetch_record('mp_product', NULL);

		//DEFINE TO FETCH THE LIST OF SUPPLIER
		$data['payee_list'] = $this->Crud_model->fetch_payee_record('customer', 'status');

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	//USED TO UPDATE CREDIT NOTE
	//Credit_note/update_credit_note
	function update_credit_note()
	{

		$this->load->model('Crud_model');

		// DEFINES READ UPDATE CREDIT DETAILS

		$payee_id        = html_escape($this->input->post('payee_id'));
		$billing_address = html_escape($this->input->post('billing_address'));
		$product         = html_escape($this->input->post('product'));
		$descriptionarr  = html_escape($this->input->post('descriptionarr'));
		$qty             = html_escape($this->input->post('qty'));
		$price           = html_escape($this->input->post('price'));
		$single_tax      = html_escape($this->input->post('single_tax'));
		$total_bill      = html_escape($this->input->post('total_bill'));
		$invoicemessage  = html_escape($this->input->post('invoicemessage'));
		$memo            = html_escape($this->input->post('memo'));
		$credit_note_id  = html_escape($this->input->post('credit_note_id'));
		$transaction_id  = html_escape($this->input->post('transaction_id'));
		$send_mail       = html_escape($this->input->post('send_mail'));
		$attachment      = $this->Crud_model->do_upload_picture("attachment", "./uploads/credit_note/");

		if (count($product) > 0)
		{
			// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
			$this->load->model('Credit_model');

			// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY
			$args = array(
				'payee_id' => $payee_id,
				'credit_id' => $credit_note_id,
				'transaction_id' => $transaction_id,
				'billing_address' => $billing_address,
				'product' => $product,
				'descriptionarr' => $descriptionarr,
				'qty' => $qty,
				'price' => $price,
				'single_tax' => $single_tax,
				'total_bill' => $total_bill,
				'invoicemessage' => $invoicemessage,
				'memo' => $memo,
				'credithead' => 4, //A/R,
				'attachment' => $attachment
			);

			if ($attachment != "default.jpg")
			{
				// DEFINES TO DELETE IMAGE FROM FOLDER PARAMETER REQIURES ARRAY OF IMAGE PATH AND ID
				$this->Crud_model->delete_image_custom('./uploads/credit_note/', $credit_note_id, 'attachment', 'mp_credit_note');
			}

			// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
			$result = $this->Credit_model->update_credit_transaction($args);

			if ($result != NULL)
			{
				//SEND EMAIL
				if (isset($send_mail) == 1)
				{
					$this->sendmail($credit_note_id, 'avoid');
				}

				$array_msg = array(
					'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"></i> Updated Successfully',
					'alert' => 'info'
				);
				$this->session->set_flashdata('status', $array_msg);
			}
			else
			{
				$array_msg = array(
					'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Sorry cannot updated',
					'alert' => 'danger'
				);
				$this->session->set_flashdata('status', $array_msg);
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"></i> Empty credit note !!',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
		}

		redirect('credit_note');
	}

	//USED TO SEND EMAIL
	function sendmail($credit_id, $avoid = '')
	{
		$this->load->model('Crud_model');

		$this->load->model('Email_model');

		$default_data = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);

		$credit_data = $this->Crud_model->fetch_record_by_id('mp_credit_note', $credit_id);

		$user_data = $this->Crud_model->fetch_record_by_id('mp_payee', $credit_data[0]->payee_id);

		//MAILING INFO
		$mail_data = array(
			'company' => $default_data[0]->companyname,
			'customer_email' => $user_data[0]->cus_email,
			'sender_email' => $default_data[0]->email,
			'title' => 'CREDIT NO ' . $credit_id . ' ' . $default_data[0]->companyname,
			'customer_name' => $user_data[0]->customer_name,
			'title1' => 'TOTAL CREDIT',
			'balance' => $default_data[0]->currency . ' ' . $credit_data[0]->total_bill,
			'title2' => 'DATE',
			'due_date' => $credit_data[0]->credit_date,
			'request_no' => $credit_id,
			'logo' => base_url() . 'uploads/systemimgs/' . $default_data[0]->logo,
			'button_text' => 'View note',
			'type' => 'CREDIT NO',
			'source' => 'creditnote',
			'color' => $default_data[0]->primarycolor,
			'payee_id' => $credit_data[0]->payee_id
		);

		$result = $this->Email_model->email_request($mail_data);

		if ($result)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"/> Send successfully',
				'alert' => 'info'
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"/> Cannot be Sent',
				'alert' => 'danger'
			);
			$this->session->set_flashdata('status', $array_msg);
		}

		if ($avoid == '')
		{
			redirect('credit_note');
		}
	}

	//Credit_note/popup
	//DEFINES A POPUP MODEL OG GIVEN PARAMETER
	function popup($page_name = '', $param = '')
	{
		$this->load->model('Crud_model');

		if ($page_name == 'add_product_model')
		{
			$data['redirect_link'] = 'credit_note/credit_notes';

			$data['income_heads'] = $this->Crud_model->fetch_attr_record_by_id('mp_head', 'nature', 'Revenue');

			//model name available in admin models folder
			$this->load->view('admin_models/add_models/add_product_model.php', $data);
		}
	}


	//Credit_note/view_attachment
	//USED TO VIEW ATTACHMENT
	function view_attachment($id)
	{
		$this->load->model('Crud_model');

		// FETCH THE PROVIED ID OF ATTACHMENT TO VIEW OR PRINT
		$credit_data = $this->Crud_model->fetch_record_by_id('mp_credit_note', $id);

		$data['title'] = 'Credit | Attachment';

		//CONTROLLER LINK TO REDIRECT
		$data['controller_link'] = 'credit_note';

		//BREADCRUMB NAME OR TITLE
		$data['controller_name'] = 'Credit note';

		//ATACHMENT IMAGE PATH
		$data['img_path'] = 'uploads/credit_note/' . $credit_data[0]->attachment;

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/print_attachment.php';

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
}