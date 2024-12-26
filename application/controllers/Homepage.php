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
class Homepage extends CI_Controller

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
	
	// Homepage
	public function index()

	{
		// DEFINES TO LOAD THE CATEGORY RECORD FROM DATABSE TABLE mp_Categoty
		$this->load->model('Crud_model');
		$this->load->model('Statement_model');
		$this->load->model('Accounts_model');
		// DEFINES PAGE TITLE
		$data['title'] = 'Dashboard';
		$data['default'] = $this->Crud_model->fetch_record_by_id('mp_langingpage', 1);
		// CASH IN HAND
		$data['cash_in_hand'] = $this->Statement_model->count_head_amount_by_id(2);
		// ACCOUNT RECEIVABLE
		$data['account_recieveble'] = $this->Statement_model->count_head_amount_by_id(4);
		// PAYABLES
		$data['payables'] = $this->Statement_model->count_head_amount_by_id(5);
		// CASH IN BANK
		$data['cash_in_bank'] = $this->Statement_model->count_head_amount_by_id(16);
		// OVER DUE INVOICES
		$data['over_due_invoices'] = $this->Accounts_model->overdue_invoices();
		// GET PRIVIOUS YEARS INCOME
		$data['get_incomes'] = $this->Accounts_model->get_incomes();
		// GET MONTHLY EXPENSES
		$data['get_expense'] = $this->Accounts_model->get_current_expense();
		// MONTH EXPENSES
		$date1 = Date('Y-m') . '-1';
		$date2 = Date('Y-m') . '-31';
		$data['month_expenses'] = $this->Accounts_model->fetch_record_date_limit('mp_expense', $date1, $date2);
		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'dashboard';
		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}
}

