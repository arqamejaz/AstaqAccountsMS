<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author    : Muhammad Ibrahim
 *  @Mail      : aliibrahimroshan@gmail.com
 *  @Created   : 11th December, 2018
 *  @Developed : Team Spantik Lab
 *  @URL       : www.spantiklab.com
 *  @Envato    : https://codecanyon.net/user/spantiklab
 */
class Backup extends CI_Controller
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
    
    //Backup
    public function index()
    {
        
        // DEFINES PAGE TITLE 
        $data['title'] = 'Take backup';
        
        // DEFINES WHICH PAGE TO RENDER
        $data['main_view'] = 'backup';
        
        // DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
        $this->load->view('main/index.php', $data);
    }
    
    public function take_backup()
    {
        $tables = array(
            'mp_langingpage',
            'mp_head',
            'mp_generalentry',
            'mp_sub_entry',
            'mp_product',
            'mp_banks',
            'mp_bank_opening',
            'mp_bank_transaction',
            'mp_bank_transaction_payee',
            'mp_credit_note',
            'mp_credit_sales',
            'mp_estimate',
            'mp_estimate_sales',
            'mp_expense',
            'mp_invoices',
            'mp_menu',
            'mp_menulist',
            'mp_multipleroles',
            'mp_payee',
            'mp_payee_payments',
            'mp_payment_voucher',
            'mp_purchasereturn_receipt',
            'mp_purchase_receipt',
            'mp_purchase_return',
            'mp_refund',
            'mp_refund_sales',
            'mp_sales',
            'mp_sales_receipt',
            'mp_sub_expense',
            'mp_sub_purchase',
            'mp_sub_purchase_return',
            'mp_sub_receipt',
            'mp_users'
        );
        
        $this->load->dbutil();
        $db_name = $this->db->database . '_' . date('Y-m-d_H-i-s', time()) . '_backup.txt';
        $prefs   = array(
            'tables' => $tables,
            'ignore' => array(),
            'format' => 'txt',
            'filename' => $db_name,
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n",
            'foreign_key_checks' => FALSE
        );
        
        $sql = $this->dbutil->backup($prefs);
        
        $data = $sql;
        
        $backup_path = './assets/db_backup/' . $prefs['filename'];
        
        if (write_file($backup_path, $data)) {
            // Load the download helper and send the file to your desktop
            $this->load->helper('download');
            force_download($db_name, $data);
            
            return true;
        } else {
            return false;
        }
    }
    
    //backup/restore
    public function restore()
    {
        $config['upload_path']   = './uploads/files';
        $config['allowed_types'] = 'txt';
        $config['max_width']     = 0;
        $config['max_height']    = 0;
        $config['max_size']      = 0;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('backup_file')) {
            $result = '';
        } else {
            $data   = $this->upload->data();
            // $data will contain full inforation
            $result = $data['full_path'];
        }
        
        $isi_file = file_get_contents($result);
        
        foreach (explode(";\n", $isi_file) as $sql) {
            $sql = trim($sql);
            
            if ($sql) {
                $this->db->query($sql);
            }
        }
        
        if ($result != '') {
            $array_msg = array(
                'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="true"/> Data restored Successfully',
                'alert' => 'info'
            );
            $this->session->set_flashdata('status', $array_msg);
        } else {
            $array_msg = array(
                'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="true"/> Data restored failed',
                'alert' => 'danger'
            );
            $this->session->set_flashdata('status', $array_msg);
        }
        
        redirect('homepage');
    }
    
    function upload_restore()
    {
        // DEFINES PAGE TITLE
        $data['title'] = 'Restore backup';
        
        // DEFINES WHICH PAGE TO RENDER
        $data['main_view'] = 'restore_backup';
        
        // DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
        $this->load->view('main/index.php', $data);
    }
}