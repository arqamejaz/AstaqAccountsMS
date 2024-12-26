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
class Prints extends CI_Controller

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

	// Prints
	public function index()
	{
		redirect('homepage');
	}
	// Prints/invoice
	// USED TO PRINT INVOICE DETAILS
	function invoice_print($invoice_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['invoice_data'] = $this->Crud_model->fetch_record_by_id('mp_invoices', $invoice_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_invoice($invoice_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['invoice_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Invoice print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/invoice';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/estimate
	// USED TO PRINT INVOICE DETAILS
	function estimate($estimate_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['estimate_data'] = $this->Crud_model->fetch_record_by_id(' mp_estimate', $estimate_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_estimate($estimate_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['estimate_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Estimate print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/estimate';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/refund
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
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/creditnote
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
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/expense
	// USED TO PRINT EXPENSE DETAILS
	function expense($expense_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['expense_data'] = $this->Crud_model->fetch_record_by_id('mp_expense', $expense_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_expense($expense_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['expense_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Expense print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/expense';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/bank_expense
	// USED TO PRINT BANK EXPENSE DETAILS
	function bank_expense($expense_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['expense_data'] = $this->Crud_model->fetch_record_by_id('mp_expense', $expense_id);
		$data['transaction'] = $this->Crud_model->fetch_attr_record_by_id('mp_bank_transaction', 'transaction_id', $data['expense_data'][0]->transaction_id);
		$data['bank_data'] = $this->Crud_model->fetch_record_by_id('mp_banks', $data['transaction'][0]->bank_id);
		$data['sales_data'] = $this->Crud_model->fetch_product_expense($expense_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['expense_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Expense print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/bank_expense';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/sales
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
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/purchase
	// USED TO PRINT PURCHASE DETAILS
	function purchase($purchase_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_record_by_id('mp_purchase_receipt', $purchase_id);
		$data['purchase_data'] = $this->Crud_model->fetch_purchase_sales($purchase_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Purchase print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/purchase';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/purchase_return
	// USED TO PRINT PURCHASE RETURN DETAILS
	function purchase_return($purchase_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_record_by_id('mp_purchase_return', $purchase_id);
		$data['purchase_data'] = $this->Crud_model->fetch_purchase_return($purchase_id);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Purchase return print';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/purchase_return';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/debit_voucher
	// USED TO PRINT DEBIT VOUCHER DETAILS
	function debit_voucher($transaction_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_single_voucher($transaction_id, 0);
		$data['trans_data'] = $this->Crud_model->get_single_child_trans($data['receipt_data'][0]->transaction_id, '0');
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Debit Voucher';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/debit_voucher';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/journal_voucher
	// USED TO PRINT JOURNAL VOUCHER DETAILS
	function journal_voucher($transaction_id,$v_id = 2)
	{

		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_single_voucher($transaction_id, $v_id);

		$data['trans_data'] = $this->Crud_model->get_single_child_trans($data['receipt_data'][0]->transaction_id, '');

		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Journal Voucher';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/journal_voucher';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	// USED TO PRINT CREDIT VOUCHER DETAILS
	function credit_voucher($transaction_id)
	{
		$this->load->model('Crud_model');
		$data['default_data'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		$data['receipt_data'] = $this->Crud_model->fetch_single_voucher($transaction_id, 1);
		$data['trans_data'] = $this->Crud_model->get_single_child_trans($data['receipt_data'][0]->transaction_id, 1);
		$data['user_data'] = $this->Crud_model->fetch_record_by_id('mp_payee', $data['receipt_data'][0]->payee_id);
		// DEFINES PAGE TITLE
		$data['title'] = 'Credit Voucher';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/credit_voucher';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// Prints/receive_receipt
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
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// USED TO PRINT CHEQUE
	public function cheque($trans_id)

	{
		$this->load->model('Crud_model');
		// USED TO FETCH BANK TRANSACTION
		$data['trans_data'] = $this->Crud_model->get_single_cheque($trans_id, 0);
		// DEFINES PAGE TITLE
		$data['title'] = 'Print cheque';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/cheque';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// USED TO PRINT DEPOSIT
	public function deposit($trans_id)

	{
		$this->load->model('Crud_model');
		// USED TO FETCH BANK TRANSACTION
		$data['trans_data'] = $this->Crud_model->get_single_cheque($trans_id, 1);
		// DEFINES PAGE TITLE
		$data['title'] = 'Deposit';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/deposit';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	// USED TO PRINT DEPOSIT
	public function bank_collection($trans_id)

	{
		$this->load->model('Crud_model');
		// USED TO FETCH BANK TRANSACTION
		$data['trans_data'] = $this->Crud_model->get_single_bank_collection($trans_id, 1);
		// DEFINES PAGE TITLE
		$data['title'] = 'Bank Collection';
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'print/bank_collection';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
	public function transaction($tran_id)

	{

		$this->load->model('Crud_model');
		$transa_data = $this->Crud_model->fetch_record_by_id('mp_generalentry', $tran_id);
		$source = $transa_data[0]->generated_source;


		// FIND THE SOURCE AND GETTING THE ID
		if ($source == 'sales_receipt')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_sales_receipt', 'transaction_id', $tran_id);
			$this->sales($result[0]->id);
		}
		else if ($source == 'refund_receipt')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_refund', 'transaction_id', $tran_id);
			$this->refund($result[0]->id);
		}
		else if ($source == 'credit_note')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_credit_note', 'transaction_id', $tran_id);
			$this->creditnote($result[0]->id);
		}
		else if ($source == 'expense')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_expense', 'transaction_id', $tran_id);
			$this->expense($result[0]->id);
		}
		else if ($source == 'bank_expense')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_expense', 'transaction_id', $tran_id);
			$this->bank_expense($result[0]->id);
		}
		else if ($source == 'received_payments')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_payee_payments', 'transaction_id', $tran_id);
			$this->receive_receipt($result[0]->id);
		}
		else if ($source == 'invoice')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_invoices', 'transaction_id', $tran_id);
			$this->invoice_print($result[0]->id);
		}
		else if ($source == 'cheque')
		{
			$this->cheque($tran_id);
		}
		else if ($source == 'deposit')
		{
			$this->deposit($tran_id);
		}
		else if ($source == 'journal_voucher')
		{
			$this->journal_voucher($tran_id,2);
		}
		else if ($source == 'Opening_balance')
		{

			$this->journal_voucher($tran_id,3);
		}
		else if ($source == 'credit_voucher')
		{
			$this->credit_voucher($tran_id);
		}
		else if ($source == 'debit_voucher')
		{
			$this->debit_voucher($tran_id);
		}
		else if ($source == 'purchase_receipt')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_purchase_receipt', 'transaction_id', $tran_id);
			$this->purchase($result[0]->id);
		}
		else if ($source == 'purchase_return')
		{
			$result = $this->Crud_model->fetch_attr_record_by_id('mp_purchase_return', 'transaction_id', $tran_id);
			$this->purchase_return($result[0]->id);
		}
	}
}
