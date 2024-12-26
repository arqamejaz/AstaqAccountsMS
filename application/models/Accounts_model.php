<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/

class Accounts_model extends CI_Model
{
    public function fetch_record_date($tablename, $first_date, $second_date)
    {
        $this->db->where('date >=', $first_date);
        $this->db->where('date <=', $second_date);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tablename);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }

    public function fetch_record_date_limit($tablename, $first_date, $second_date)
    {
        $this->db->where('date >=', $first_date);
        $this->db->where('date <=', $second_date);
        $this->db->limit('8');

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tablename);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }

    public function fetch_record_sales($tablename, $tablefield, $id)
    {
        $this->db->where([$tablefield => $id]);
        $query = $this->db->get($tablename);
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }

    public function fetch_customer_ledger($cus_id = '')
    {

    
        $this->db->select('mp_invoices.id,mp_invoices.discount,mp_invoices.cus_id,mp_invoices.total_bill,mp_invoices.bill_paid,mp_invoices.date,mp_payee.customer_name,mp_payee.cus_contact_1,mp_payee.cus_email');
        $this->db->from('mp_invoices');
        $this->db->join('mp_payee', "mp_payee.id = mp_invoices.cus_id");
        $this->db->where('mp_invoices.cus_id',$cus_id);
        $this->db->order_by('mp_invoices.id','DESC');
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }    


    public function fetch_customer_ledger_return($date1,$date2,$cus_id = '')
    {

    
        $this->db->select('mp_invoices.id,mp_invoices.discount,mp_invoices.cus_id,mp_invoices.total_bill,mp_invoices.bill_paid,mp_invoices.date,mp_customer.customer_name,mp_customer.cus_contact_1,mp_customer.cus_email');
        $this->db->from('mp_invoices');
        $this->db->join('mp_customer', "mp_customer.id = mp_invoices.cus_id");
        $this->db->where('mp_invoices.date >=', $date1);
        $this->db->where('mp_invoices.date <=', $date2);
        $this->db->where('mp_invoices.cus_id',$cus_id);
        $this->db->order_by('mp_invoices.id','DESC');
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }

    //USED TO FIND THE PREVIOUS BALANCES OF THE CUSTOMER 
    function previous_balance($cus_id)
    {
        $total_balance = 0;
        $total_return_balance = 0;
        $total_paid_amount = 0;

        $this->db->select('*');
        $this->db->from('mp_invoices');
        $this->db->where('mp_invoices.cus_id',$cus_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $invoices_data =  $query->result();
            foreach ($invoices_data as $invoice) 
            {
                $total_balance = $total_balance + $invoice->total_bill - $invoice->bill_paid;  
            }
        }

        $this->db->select('*');
        $this->db->from('mp_return_item');
        $this->db->where('mp_return_item.cus_id',$cus_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $return_data =  $query->result();
            foreach ($return_data as $return_invoice) 
            {
                $total_return_balance = $total_return_balance + $return_invoice->total_bill-$return_invoice->return_amount;  
            }
        }       

        $this->db->select('*');
        $this->db->from('mp_customer_payments');
        $this->db->where('mp_customer_payments.customer_id',$cus_id);
        $query = $this->db->get();
       
        if ($query->num_rows() > 0)
        {
            $paid_data =  $query->result();
            foreach ($paid_data as $paid_bills) 
            {
                $total_paid_amount = $total_paid_amount + $paid_bills->amount;  
            }
        }

        return number_format($total_balance - ($total_return_balance+$total_paid_amount),'2','.','');
    }

    //USED TO FIND THE CHEQUES 
    function written_cheques($date1,$date2)
    {
        $this->db->select('mp_generalentry.id as main_trans_id,mp_generalentry.date,mp_banks.bankname,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.ref_no,mp_bank_transaction.transaction_status,mp_head.name as headname');
        $this->db->from('mp_generalentry');
        $this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = 0 ");
        $this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
        $this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
        $this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
        $this->db->where('mp_generalentry.generated_source','cheque');
        $this->db->where('date >=', $date1);
        $this->db->where('date <=', $date2);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }

    } 


    //USED TO FIND THE CHEQUES 
    function bank_collection_transaction($date1,$date2)
    {
        $this->db->select('mp_generalentry.id as main_trans_id,mp_generalentry.date,mp_generalentry.naration,mp_banks.bankname,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.ref_no,mp_bank_transaction.transaction_status,mp_bank_transaction.id as bank_trans_id,mp_head.name as headname');
        $this->db->from('mp_generalentry');
        $this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = 1 ");
        $this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
        $this->db->join('mp_head',  "mp_head.id = mp_sub_entry.accounthead");
        $this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
        $this->db->where('mp_generalentry.generated_source','bank_collection');
        $this->db->where('mp_generalentry.date >=', $date1);
        $this->db->where('mp_generalentry.date <=', $date2);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }

    }

    //USED TO FIND THE DEPOSITS 
    function bank_deposits($date1,$date2)
    {
    
        $this->db->select('mp_generalentry.date,mp_banks.bankname,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.transaction_id,mp_bank_transaction.ref_no,mp_bank_transaction.transaction_status,mp_head.name as headname');
        $this->db->from('mp_generalentry');
        $this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = 1 ");
        $this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
        $this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
        $this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
        $this->db->where('mp_generalentry.generated_source','deposit');
        $this->db->where('date >=', $date1);
        $this->db->where('date <=', $date2);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }

    }    

    //USED TO FIND THE DEPOSITS 
    function bank_book($date1,$date2,$source,$bank_id)
    {
    
        $this->db->select('mp_generalentry.date,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.method,mp_bank_transaction.ref_no');
        $this->db->from('mp_generalentry');
        $this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = 1 ");
        $this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id"); 
        $this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
         $this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id");
        $this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
       
        $this->db->where('mp_generalentry.generated_source',$source);
        $this->db->where('date >=', $date1);
        $this->db->where('date <=', $date2);
         $this->db->where('mp_bank_transaction.transaction_status',0);
         $this->db->where('mp_bank_transaction.bank_id',$bank_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }

    }


    //USED TO COUNT AMOUNT OF EXPENSE 
    function expense_amount()
    {
        $date1 = date('Y-m-').'1';
        $date2 = date('Y-m-').'31';
        $amount = 0; 

        $this->db->select('mp_expense.total_bill');
        $this->db->from('mp_expense');
        $this->db->where('date >=', $date1);
        $this->db->where('date <=', $date2);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result =  $query->result();
            if($result != NULL)
            {
                foreach ($result as $single_item) 
                {
                    $amount = $amount  + $single_item->total_bill;
                }
            }
        }
        return $amount;
    }

    //USED TO FIND OVER DUE INVOICES 
    function overdue_invoices()
    {
        $this->db->select('mp_invoices.*,mp_payee.customer_name');
        $this->db->from('mp_invoices');
        $this->db->join('mp_payee', "mp_payee.id = mp_invoices.payee_id");
        $this->db->where('mp_invoices.due_date <=', date('Y-m-d'));
        $this->db->where('mp_invoices.status !=', '2');
        $this->db->order_by('id','DESC');
        $this->db->limit(8);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }


    //USED TO GET INCOMES OF PREVIOUS YEAR 
    function get_incomes()
    {
        $year  = Date('Y');

        $CI =& get_instance();
        $CI->load->model('Statement_model');
        
        $revenue = array();
        $expense = array();

        for($i = $year; $i >= $year-3; $i--)
        {
            $date1 = $i.'-01-01';
            $date2 = $i.'-12-31';

            $this->db->select("*");
            $this->db->from('mp_generalentry');
            $this->db->where('date >=', $date1);
            $this->db->where('date <=', $date2);
            $query = $this->db->get();
            if($query->num_rows() > 0)
            {
                $record_data =  $query->result();

                $total_revenue = 0;

                $this->db->select("*");
                $this->db->from('mp_head');
                $this->db->where(['mp_head.nature' => 'Revenue']);
                $query = $this->db->get();
                if($query->num_rows() > 0)
                {
                    $record_data =  $query->result();
                    if($record_data != NULL)
                    {
                        foreach ($record_data as $single_head) 
                        {
                            $amount =  $CI->Statement_model->count_head_amount($single_head->id,$date1,$date2);
                            if($amount != 0)
                            {
                                $total_revenue = $total_revenue + $amount;
                            }
                        }  
                    }
                }

                 array_push($revenue, $total_revenue);

                $total_expense = 0;

                $this->db->select("*");
                $this->db->from('mp_head');
                $this->db->where(['mp_head.nature' => 'Expense']);
                $query = $this->db->get();
                if($query->num_rows() > 0)
                {
                    $record_data =  $query->result();
                    if($record_data != NULL)
                    {
                        foreach ($record_data as $single_head) 
                        {
                            $amount =  $CI->Statement_model->count_head_amount($single_head->id,$date1,$date2);

                            if( $amount != 0)
                            {
                                $total_expense = $total_expense + $amount; 
                            }
                        }
                    }
                }

                array_push($expense, $total_expense);

            }
            else
            {
                break;
            }

        }
       
       return array('revenue' => $revenue, 'expense' => $expense);
    }

    //USED TO FIND CURRENT MONTS EXPENSE 
    function get_current_expense()
    {
        $color = array('#f56954','#00a65a','#f39c12','#00c0ef','#3c8dbc','#d2d6de');
        $total_expense = 0;
        $expense = array();

        $CI =& get_instance();
        $CI->load->model('Statement_model');

        $date1 = Date('Y-m').'-01';
        $date2 = Date('Y-m').'-31';

        $this->db->select("*");
        $this->db->from('mp_head');
        $this->db->where(['mp_head.nature' => 'Expense']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $record_data =  $query->result();
            if($record_data != NULL)
            {
                foreach ($record_data as $single_head) 
                {
                     $this->db->select("*");
                     $this->db->from('mp_head');
                     $this->db->where(['mp_head.id' => $single_head->id]);
                     $query = $this->db->get();
                     $headname = $query->result();
                    $amount =  $CI->Statement_model->count_head_amount($single_head->id,$date1,$date2);

                    if( $amount != 0)
                    {
                       //HERE ADD TO ARRAY 
                        $expense [] =  array('value' => $amount ,'color' => $color[rand(0,5)],'highlight' => $color[rand(0,5)], 'label' => $headname[0]->name);
                    }
                }
            }
        }

       return json_encode($expense);
       
    }
}