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
class Public_access extends CI_Controller

{
	function index()
	{
		echo 'Silence is Golden';
	}
	function print_request($token)
	{
		$this->load->model('Crud_model');
		if (preg_match('/^[0-9A-F]{40}$/i', $token))
		{
			$fetch_access_data = $this->Crud_model->fetch_url_record($token);
			// 1 day measured in seconds = 60 seconds * 60 minutes * 24 hours
			$delta = 86400;
			$this->delete_expired_tokens($delta);
			if ($fetch_access_data != NULL)
			{
				// Check to see if link has expired
				if ($_SERVER["REQUEST_TIME"] - $fetch_access_data[0]->tstamp > $delta)
				{
					echo "Token has expired";
					$this->Crud_model->delete_token($fetch_access_data[0]->user_id, $fetch_access_data[0]->token, $fetch_access_data[0]->tstamp, $fetch_access_data[0]->data_id);
				}
				else
				{
					if ($fetch_access_data[0]->source == 'invoice')
					{
						$this->invoice_print($fetch_access_data[0]->data_id);
					}
					else if ($fetch_access_data[0]->source == 'estimate')
					{
						$this->estimate($fetch_access_data[0]->data_id);
					}
					else if ($fetch_access_data[0]->source == 'refund')
					{
						$this->refund($fetch_access_data[0]->data_id);
					}
					else if ($fetch_access_data[0]->source == 'creditnote')
					{
						$this->creditnote($fetch_access_data[0]->data_id);
					}
					else if ($fetch_access_data[0]->source == 'sales')
					{
						$this->sales($fetch_access_data[0]->data_id);
					}
					else if ($fetch_access_data[0]->source == 'receive_receipt')
					{
						$this->receive_receipt($fetch_access_data[0]->data_id);
					}
				}
			}
			else
			{
				echo 'Token has expired';
			}
		}
		else
		{
			echo 'Sorry Invalid token';
		}
	}
	// Public_access/invoice
	// USED TO PRINT INVOICE DETAILS
	function invoice_print($invoice_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['invoice_data'] = $this->Crud_model->fetch_record_by_id(' mp_invoices', $invoice_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_invoice($invoice_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['invoice_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Invoice print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/invoice';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	// Public_access/estimate
	// USED TO PRINT INVOICE DETAILS
	function estimate($estimate_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['invoice_data'] = $this->Crud_model->fetch_record_by_id(' mp_estimate', $estimate_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_estimate($estimate_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['invoice_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Estimate print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/estimate';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	// Public_access/refund
	// USED TO PRINT REFUND DETAILS
	function refund($refund_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['refund_data'] = $this->Crud_model->fetch_record_by_id(' mp_refund', $refund_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_refund($refund_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['refund_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Refund print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/refund';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	// Public_access/creditnote
	// USED TO PRINT CREDIT NOTE DETAILS
	function creditnote($credit_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['credit_data'] = $this->Crud_model->fetch_record_by_id('mp_credit_note', $credit_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_credit($credit_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['credit_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Credit print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/credit_note';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	// Public_access/sales
	// USED TO PRINT SALES DETAILS
	function sales($sales_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_record_by_id('mp_sales_receipt', $sales_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_sales($sales_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Sales print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/sales';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	// Public_access/receive_receipt
	// USED TO PRINT RECEIPT DETAILS
	function receive_receipt($receipt_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipts_data'] = $this->Crud_model->fetch_product_receipt($receipt_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipts_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Receipt print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/receipt';
		// DEFINES GO TO MAIN FOLDER FOND public_view  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/public_view', $data);
	}
	function delete_expired_tokens($delta)
	{
		$this->load->model('Crud_model');
		$temp_urls = $this->Crud_model->fetch_record('mp_temp_urls', NULL);
		if ($temp_urls != NULL)
		{
			foreach($temp_urls as $url)
			{
				// Check to see if link has expired
				if ($_SERVER["REQUEST_TIME"] - $url->tstamp > $delta)
				{
					$this->Crud_model->delete_token($url->user_id, $url->token, $url->tstamp, $url->data_id);
				}
			}
		}
	}
}
