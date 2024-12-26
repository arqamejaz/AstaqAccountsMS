<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
class Crud_model extends CI_Model

{
	public function insert_data($tablename, $arg1)

	{
		$this->db->insert($tablename, $arg1);
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function insert_data_last_id($tablename, $arg1)

	{
		$this->db->insert($tablename, $arg1);
		if ($this->db->affected_rows() > 0)
		{
			return $last_insert_id = $this->db->insert_id();
		}
		else
		{
			return NULL;
		}
	}
	public function fetch_last_record($table)

	{
		$this->db->select("id");
		$this->db->from($table);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
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
	// DEFINES TO AVOID MULTIPLE EMAILS IN DATABASE
	public function check_email_address($table_name, $tbl_attribute, $email)

	{
		$this->db->select("id");
		$this->db->from($table_name);
		$this->db->where([$tbl_attribute => $email]);
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
	public function fetch_limit_record($table, $limit)

	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit);
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
	public function fetch_limit_record_attr($table, $limit, $attr, $val)

	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit);
		$this->db->where([$attr => $val]);
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
	public function fetch_record($tablename, $args)

	{
		if ($args != NULL)
		{
			$this->db->where(['status' => 0]);
			$query = $this->db->get($tablename);
		}
		else
		{
			$query = $this->db->get($tablename);
		}
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	public function fetch_receive_payment($date1, $date2, $tablename)

	{
		$this->db->where('date >=', $date1);
		$this->db->where('date <=', $date2);
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
	public function fetch_payee_record($type, $status = '')

	{
		/*if($type != 'all')
		{
		$this->db->where(['type' => $type]);
		*/
		if ($status != '')
		{
			$this->db->where(['cus_status' => 0]);
		}
		$query = $this->db->get('mp_payee');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	public function recover_password($tablename, $email, $attribute)

	{
		$this->db->where([$attribute => $email]);
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
	public function fetch_record_by_id($tablename, $id)

	{
		$this->db->where(['id' => $id]);
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
	// USED TO FETCH THE RECORD THROUGH PROVIDED ID AND ATTTRIBUTE NAME
	public function fetch_attr_record_by_id($table_name, $attr, $val, $status = '')

	{
		if ($status != '')
		{
			$this->db->where(['status =' => $status]);
		}
		$this->db->where([$attr => $val]);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($table_name);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	// USED TO FETCH THE RECORD THROUGH PROVIDED ID AND DATES
	public function fetch_record_with_date($table_name, $attr, $val, $date1, $date2)

	{
		$this->db->where([$attr => $val]);
		$this->db->where('date >=', $date1);
		$this->db->where('date <=', $date2);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($table_name);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	// USED TO FETCH THE RECORD OF NOT DESPOSITED AND OUTSTANDING CHECKS
	public function fetch_bank_record($month, $bank_id, $type)

	{
		$date1 = date('Y') . '-' . $month . '-1';
		$date2 = date('Y') . '-' . $month . '-31';
		$this->db->select("mp_bank_transaction.*,mp_payee.customer_name,mp_bank_transaction.total_paid,mp_bank_transaction.total_bill");
		$this->db->from('mp_bank_transaction');
		$this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_bank_transaction.transaction_id");
		$this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
		$this->db->where('mp_bank_transaction.bank_id', $bank_id);
		$this->db->where('mp_bank_transaction.transaction_status', 1);
		$this->db->where('mp_bank_transaction.transaction_type', $type);
		$this->db->where('mp_bank_transaction.cleared_date >=', $date1);
		$this->db->where('mp_bank_transaction.cleared_date <=', $date2);
		$this->db->order_by('mp_bank_transaction.id', 'DESC');
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
	// USED TO FETCH THE RECORD THROUGH PROVIDED ID AND DATES
	public function fetch_record_date($table_name, $date1, $date2)

	{
		$this->db->where('date >=', $date1);
		$this->db->where('date <=', $date2);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($table_name);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	public function fetch_record_expense($date1, $date2)

	{
		$this->db->select("mp_expense.*,mp_payee.customer_name");
		$this->db->where('mp_expense.date >=', $date1);
		$this->db->where('mp_expense.date <=', $date2);
		$this->db->from('mp_expense');
		$this->db->join('mp_payee', "mp_payee.id = mp_expense.payee_id");
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
	// USED TO FETCH THE BANK EXPENSES
	public function fetch_record_bankexpense($date1, $date2)

	{
		$this->db->select("mp_expense.*,mp_bank_transaction.bank_id,mp_banks.bankname");
		$this->db->where('mp_expense.date >=', $date1);
		$this->db->where('mp_expense.date <=', $date2);
		$this->db->from('mp_expense');
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_expense.transaction_id");
		$this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
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
	// USED TO FETCH THE BANK EXPENSES
	public function fetch_bank_expense_heads()

	{
		$this->db->select("*");
		$this->db->where('nature', 'Expense');
		$this->db->where('expense_type', 'Bank Expense');
		$this->db->from('mp_head');
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

	//USED MULTIPLE HEADS
    function fetch_account_heads($assets,$libility,$equity,$revenue,$expense)
    {
        $this->db->select("*");
        $this->db->from('mp_head');
        
        if($assets != '')
        {
            $this->db->or_where('nature','Assets');
        }

        if($libility != '')
        {
            $this->db->or_where('nature','Libility');
        }

        if($equity != '')
        {
            $this->db->or_where('nature','Equity');
        }

        if($revenue != '')
        {
            $this->db->or_where('nature','Revenue');
        }

        if($expense != '')
        {
            $this->db->or_where('nature','Expense');
        }

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
	
	public function fetch_payment_vouchers($date1, $date2, $type)
	{
		$this->db->select("mp_payment_voucher.*,mp_payee.customer_name");
		$this->db->from('mp_payment_voucher');
		$this->db->join('mp_payee', "mp_payee.id = mp_payment_voucher.payee_id");
		
		if($type == 2)
		{
			$this->db->or_where('mp_payment_voucher.type', 3);
			$this->db->or_where('mp_payment_voucher.type', $type);
        }
        else
        {
            $this->db->where('mp_payment_voucher.type', $type);
        }

		
		$this->db->where('mp_payment_voucher.receipt_date >=', $date1);
		$this->db->where('mp_payment_voucher.receipt_date <=', $date2);
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
	
	public function fetch_record_invoices($date1, $date2)

	{
		$this->db->select("mp_invoices.*,mp_payee.id as invoice_payee_id , mp_payee.customer_name");
		$this->db->where('mp_invoices.date >=', $date1);
		$this->db->where('mp_invoices.date <=', $date2);
		$this->db->from('mp_invoices');
		$this->db->join('mp_payee', "mp_payee.id = mp_invoices.payee_id");
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
	public function fetch_bank_expense($month, $bank_id)

	{
		$date1 = date('Y') . '-' . $month . '-1';
		$date2 = date('Y') . '-' . $month . '-31';
		$this->db->select("mp_generalentry.id as trans_id,mp_expense.id,mp_expense.date,mp_sub_expense.head_id,mp_sub_expense.price,mp_sub_expense.expense_id,mp_head.name,mp_bank_transaction.bank_id,mp_banks.bankname");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_expense', "mp_expense.transaction_id = mp_generalentry.id");
		$this->db->join('mp_sub_expense', "mp_sub_expense.expense_id = mp_expense.id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_expense.head_id");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_expense.transaction_id");
		$this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
		$this->db->where('mp_generalentry.date >=', $date1);
		$this->db->where('mp_generalentry.date <=', $date2);
		$this->db->where('mp_bank_transaction.bank_id', $bank_id);
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
	public function fetch_bank_profit($month, $bank_id)

	{
		$date1 = date('Y') . '-' . $month . '-1';
		$date2 = date('Y') . '-' . $month . '-31';
		$this->db->select("mp_generalentry.id as trans_id,mp_head.name,mp_bank_transaction.bank_id,mp_bank_transaction.transaction_type,mp_banks.bankname,mp_sub_entry.accounthead,mp_sub_entry.amount");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_sub_entry.parent_id = mp_generalentry.id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id");
		$this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
		$this->db->where('mp_generalentry.date >=', $date1);
		$this->db->where('mp_generalentry.date <=', $date2);
		// $this->db->where('mp_bank_transaction.bank_id',$bank_id);
		$this->db->where('mp_bank_transaction.transaction_type', 'bank_collection');
		$this->db->where('mp_sub_entry.type', 1);
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
	public function fetch_record_estimate($date1, $date2)

	{
		$this->db->select(" mp_estimate.*,mp_payee.id as invoice_payee_id , mp_payee.customer_name");
		$this->db->where(' mp_estimate.date >=', $date1);
		$this->db->where(' mp_estimate.date <=', $date2);
		$this->db->from(' mp_estimate');
		$this->db->join('mp_payee', "mp_payee.id =  mp_estimate.payee_id");
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
	public function fetch_record_refund($date1, $date2)

	{
		$this->db->select(" mp_refund.*,mp_payee.id as invoice_payee_id , mp_payee.customer_name");
		$this->db->where(' mp_refund.receipt_date >=', $date1);
		$this->db->where(' mp_refund.receipt_date <=', $date2);
		$this->db->from(' mp_refund');
		$this->db->join('mp_payee', "mp_payee.id =  mp_refund.payee_id");
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
	public function fetch_record_credit($date1, $date2)

	{
		$this->db->select("mp_credit_note.*,mp_payee.id as invoice_payee_id , mp_payee.customer_name");
		$this->db->where(' mp_credit_note.credit_date >=', $date1);
		$this->db->where(' mp_credit_note.credit_date <=', $date2);
		$this->db->from(' mp_credit_note');
		$this->db->join('mp_payee', "mp_payee.id =  mp_credit_note.payee_id");
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
	public function fetch_record_sales($date1, $date2)

	{
		$this->db->select("mp_sales_receipt.*,mp_payee.id as receipt_payee_id , mp_payee.customer_name");
		$this->db->where('mp_sales_receipt.date >=', $date1);
		$this->db->where('mp_sales_receipt.date <=', $date2);
		$this->db->from('mp_sales_receipt');
		$this->db->join('mp_payee', "mp_payee.id = mp_sales_receipt.payee_id");
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
	public function fetch_record_purchase($date1, $date2)

	{
		$this->db->select("mp_purchase_receipt.*,mp_payee.id as receipt_payee_id , mp_payee.customer_name");
		$this->db->where('mp_purchase_receipt.date >=', $date1);
		$this->db->where('mp_purchase_receipt.date <=', $date2);
		$this->db->from('mp_purchase_receipt');
		$this->db->join('mp_payee', "mp_payee.id = mp_purchase_receipt.payee_id");
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
	public function fetch_record_return_purchase($date1, $date2)

	{
		$this->db->select("mp_purchase_return.*,mp_payee.id as receipt_payee_id , mp_payee.customer_name");
		$this->db->where('mp_purchase_return.date >=', $date1);
		$this->db->where('mp_purchase_return.date <=', $date2);
		$this->db->from('mp_purchase_return');
		$this->db->join('mp_payee', "mp_payee.id = mp_purchase_return.payee_id");
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
	public function expense_through_user($date1, $date2, $method, $payee_id)

	{
		$this->db->select("mp_expense.*,mp_payee.customer_name, mp_head.name as head_name,mp_head.nature");
		$this->db->from('mp_expense');
		$this->db->join('mp_head', "mp_expense.head_id = mp_head.id");
		$this->db->join('mp_payee', "mp_payee.id = mp_expense.payee_id");
		$this->db->where('mp_expense.date <=', $date2);
		$this->db->where('mp_expense.date >=', $date1);
		$this->db->where('mp_expense.payee_id', $payee_id);
		$this->db->where('mp_expense.method', $method);
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
	public function fetch_record_product($arg)

	{
		// DEFINES JOIN QUERY WHICH WOULD RETURN THE CATEGORY NAME OF product FROM
		// mp_category TABLE INSTEAD OF JUST RETURNING NUMBERIC ID FORM mp_productslist TABLE.
		// INSEAD OF CATEGORY ID 12 WILL GET THE category_name FROM TABLE.
		// IF 0 MEANS SELECT ONLY THOSE RECORDS WHORE STATUS IS 0 MEANS VISIBLE OR 1 MEANS FETCH ALL
		// WEATHER IT WOULD BE VISIBLE OR HIDDEN MEANS STATUS = 0 OR STATUS = 1
		if ($arg == 'all')
		{
			$this->db->select('mp_productslist.*,mp_category.category_name,mp_brand.name');
			$this->db->from('mp_category');
			$this->db->join('mp_productslist', 'mp_category.id = mp_productslist.category_id and mp_productslist.status != 2');
			$this->db->join('mp_brand', "mp_brand.id = mp_productslist.brand_id");
			$query = $this->db->get();
		}
		else
		{
			$this->db->select('mp_productslist.*,mp_category.category_name,');
			$this->db->from('mp_category');
			$this->db->join('mp_productslist', "mp_category.id = mp_productslist.category_id and mp_productslist.status = $arg ");
			$this->db->join('mp_brand', "mp_brand.id = mp_productslist.brand_id");
			$query = $this->db->get();
		}
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	public function delete_record($tablename, $arg)

	{
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		$this->db->where(['id' => $arg]);
		$this->db->delete($tablename);
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		$this->db->db_debug = $db_debug;
	}
	public function delete_attr_record($tablename, $attr, $arg)

	{
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		$this->db->where([$attr => $arg]);
		$this->db->delete($tablename);
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		$this->db->db_debug = $db_debug;
	}
	public function delete_all($tablename)

	{
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		$this->db->truncate($tablename);
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		$this->db->db_debug = $db_debug;
	}
	public function delete_image($path, $id, $tablename)

	{
		// IMAGE FOLDER PATH
		$image_path = $path;
		// TABLE ID TO DELETE ROW
		$args = $id;
		// DEFINES TO RETREVE DATA ROW FROM TABLE AGINST GIVEN ID
		$data = $this->get_by_id($tablename, $id);
		// WE WILL NOT DELETE THE DEAFULT PICTURE BECAUSE WE USED THIS PICTURE MANY TIMES FOR OTHER PROFILE
		// IF WE DID SO THEM THIS COULD CAUSE AN ERROR IN PROFILE IMAGES OF PEOPLE IN TABLES
		if ($data->cus_picture != "default.jpg")
		{
			// TO DELETE IMAGE FROM FOLDER TO GIVEN PATH
			@@unlink($image_path . $data->cus_picture);
		}
	}
	public function edit_record_id($args, $data)

	{
		extract($args);
		$this->db->where('id', $id);
		$this->db->update($table_name, $data);
		return TRUE;
	}

	public function edit_record_attr($args, $data)
	{
		extract($args);
		$this->db->where('set_default', $set_default);
		$this->db->update($table_name, $data);
		return TRUE;
	}

	public function edit_record_roles_read($args, $data)
	{
		extract($args);

		$this->db->where('menu_Id', $menu_Id);
		$this->db->where('user_id', $user_id);
		$this->db->update($table_name, $data);
		return TRUE;
	}

	public function edit_record_roles_write($args, $data)
	{
		extract($args);
		
		$this->db->where('menu_Id', $menu_Id);
		$this->db->where('user_id', $user_id);
		$this->db->update($table_name, $data);
		return TRUE;
	}


	public function edit_record_transac($args, $data)

	{
		extract($args);
		$this->db->where('parent_id', $id);
		$this->db->update($table_name, $data);
		return TRUE;
	}
	// DEFINES TO UPLOAD PICTURE
	public function do_upload_picture($picture, $path)

	{
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = 25000;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($picture))
		{
			$error = array(
				'error' => $this->upload->display_errors() ,
			);
			return "default.jpg";
		}
		else
		{
			$data = array(
				'upload_data' => $this->upload->data() ,
			);
			return $data['upload_data']['file_name'];
		}
	}
	public function count_sales($table_name, $first_date, $second_date)

	{
		$this->db->where('date >=', $first_date);
		$this->db->where('date <=', $second_date);
		$this->db->from($table_name);
		return $this->db->count_all_results();
	}
	public function authenticate_user($Email, $password)

	{
		$this->db->where('user_email =', $Email);
		$this->db->where('user_password =', sha1($password));
		$this->db->where('status = 0');
		$query = $this->db->get('mp_users');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	public function get_user_details_menus()
	{
		$this->db->select("mp_users.id as user_id , mp_users.user_name , mp_users.user_email , mp_users.user_description");
		$this->db->from('mp_users');
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
	public function delete_image_custom($path, $id, $attr, $tablename)

	{
		// IMAGE FOLDER PATH
		$image_path = $path;
		// TABLE ID TO DELETE ROW
		$args = $id;
		// DEFINES TO RETREVE DATA ROW FROM TABLE AGINST GIVEN ID
		$data = $this->get_by_id($tablename, $id);
		// WE WILL NOT DELETE THE DEAFULT PICTURE BECAUSE WE USED THIS PICTURE MANY TIMES FOR OTHER PROFILE
		// IF WE DID SO THEM THIS COULD CAUSE AN ERROR IN PROFILE IMAGES OF PEOPLE IN TABLES
		if ($data->$attr != "default.jpg")
		{
			// TO DELETE IMAGE FROM FOLDER TO GIVEN PATH
			@@unlink($image_path . $data->$attr);
		}
	}
	public function check_role_duplication($user_id, $menu_id)
	{
		
		$this->db->where('user_id =', $user_id);
		$this->db->where('menu_Id =', $menu_id);

		$query = $this->db->get('mp_multipleroles');
		if ($query->num_rows() > 0)
		{
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	// USED TO RECOVER FORGET PASSWORD
	function fetch_forget_password($user_email, $user_code)
	{
		$this->db->select("mp_users.id");
		$this->db->from('mp_users');
		$this->db->where(['user_email' => $user_email]);
		$this->db->where(['user_password' => $user_code]);
		$this->db->where(['status' => '0']);
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
	// USED TO RECOVER FORGET PASSWORD
	function fetch_forget_password_user($user_email, $user_code)
	{
		$this->db->select("mp_customer.id");
		$this->db->from('mp_customer');
		$this->db->where(['cus_email' => $user_email]);
		$this->db->where(['cus_password' => $user_code]);
		$this->db->where(['cus_status' => '0']);
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
	// USED TO FIND CURRENT AVAILABLE BALANCE IN BANK
	function check_available_balance($bank_id)
	{
		$total_available = 0;
		$opening = $this->fetch_attr_record_by_id('mp_bank_opening', 'bank_id', $bank_id);
		if ($opening != NULL)
		{
			$total_available = $total_available + $opening[0]->amount;
		}
		$result = $this->fetch_attr_record_by_id('mp_bank_transaction', 'bank_id', $bank_id);
		if ($result != NULL)
		{
			foreach($result as $single_transaction)
			{
				// $result = $this->fetch_attr_record_by_id('mp_sub_entry','parent_id',$single_transaction->transaction_id);
				// 1 DEPOSIT //0 CHEQUE
				if ($single_transaction->transaction_type == 'recieved')
				{
					$total_available = $total_available + $single_transaction->total_bill;
				}
				else if ($single_transaction->transaction_type == 'paid')
				{
					$total_available = $total_available - $single_transaction->total_bill;
				}
				else if ($single_transaction->transaction_type == 'bank_collection')
				{
					$total_available = $total_available + $single_transaction->total_bill;
				}
				else if($single_transaction->transaction_type == 'opening_account')
                {
                    $total_available = $total_available + $single_transaction->total_bill;
                }
                else
                {

                }
			}
		}
		return $total_available;
	}
	// USED TO FETCH THE RECORD OF INVOICE SALES USING PRODUCT ID
	function fetch_product_invoice($invoice_id)
	{
		$this->db->select("mp_sales.*,mp_product.product_name");
		$this->db->from('mp_sales');
		$this->db->join('mp_product', "mp_sales.product_id = mp_product.id");
		$this->db->where(['mp_sales.invoice_id' => $invoice_id]);
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
	// USED TO FETCH THE RECORD OF ESTIMATE USING PRODUCT ID
	function fetch_product_estimate($estimate_id)
	{
		$this->db->select("mp_estimate_sales.*,mp_product.product_name");
		$this->db->from('mp_estimate_sales');
		$this->db->join('mp_product', "mp_estimate_sales.product_id = mp_product.id");
		$this->db->where(['mp_estimate_sales.estimate_id' => $estimate_id]);
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
	// USED TO FETCH THE RECORD OF REFUND USING PRODUCT ID
	function fetch_product_refund($refund_id)
	{
		$this->db->select("mp_refund_sales.*,mp_product.product_name");
		$this->db->from(' mp_refund_sales');
		$this->db->join('mp_product', "  mp_refund_sales.product_id = mp_product.id");
		$this->db->where(['mp_refund_sales.refund_id' => $refund_id]);
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
	// USED TO FETCH THE RECORD OF CREDIT USING PRODUCT ID
	function fetch_product_credit($credit_id)
	{
		$this->db->select("mp_credit_sales.*,mp_product.product_name");
		$this->db->from(' mp_credit_sales');
		$this->db->join('mp_product', "  mp_credit_sales.product_id = mp_product.id");
		$this->db->where(['mp_credit_sales.credit_id' => $credit_id]);
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
	// USED TO FETCH THE RECORD OF CREDIT USING PRODUCT ID
	function fetch_product_expense($expense_id)
	{
		$this->db->select(" mp_sub_expense.*, mp_head.name");
		$this->db->from(' mp_sub_expense');
		$this->db->join('mp_head', "  mp_sub_expense.head_id = mp_head.id");
		$this->db->where(['mp_sub_expense.expense_id' => $expense_id]);
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
	// USED TO FETCH THE RECORD OF CREDIT USING PRODUCT ID
	function fetch_product_sales($sales_id)
	{
		$this->db->select("mp_sub_receipt.*,mp_product.product_name");
		$this->db->from(' mp_sub_receipt');
		$this->db->join('mp_product', "  mp_sub_receipt.product_id = mp_product.id");
		$this->db->where(['mp_sub_receipt.sales_id' => $sales_id]);
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
	// USED TO FETCH THE RECORD OF PURCHASE USING PURCHASE ID
	function fetch_purchase_sales($purchase_id)
	{
		$this->db->select("mp_sub_purchase.*,mp_product.product_name");
		$this->db->from(' mp_sub_purchase');
		$this->db->join('mp_product', "  mp_sub_purchase.product_id = mp_product.id");
		$this->db->where(['mp_sub_purchase.sales_id' => $purchase_id]);
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
	// USED TO FETCH THE RECORD OF PURCHASE RETURN USING PURCHASE ID
	function fetch_purchase_return($purchase_id)
	{
		$this->db->select("mp_sub_purchase_return.*,mp_product.product_name");
		$this->db->from(' mp_sub_purchase_return');
		$this->db->join('mp_product', "  mp_sub_purchase_return.product_id = mp_product.id");
		$this->db->where(['mp_sub_purchase_return.sales_id' => $purchase_id]);
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
	// USED TO FETCH THE RECORD OF CREDIT USING PRODUCT ID
	function fetch_product_receipt($receipt_id)
	{
		$this->db->select("mp_payee_payments.*,mp_invoices.total_bill,mp_invoices.total_paid,mp_invoices.date,mp_invoices.due_date");
		$this->db->from(' mp_payee_payments');
		$this->db->join('mp_invoices', "mp_payee_payments.invoice_id = mp_invoices.id");
		$this->db->where(['mp_payee_payments.id' => $receipt_id]);
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
	// USED TO FETCH THE RECORD OF PRODUCTS/SERVICES
	function fetch_product_records()
	{
		$this->db->select("mp_product.*,mp_head.name");
		$this->db->from(' mp_product');
		$this->db->join('mp_head', "mp_product.head_id = mp_head.id");
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
	// USED TO FETCH THE RECORD THROUGH TOKEN FORM URL
	public function fetch_url_record($token)

	{
		$this->db->where(['token' => $token]);
		$query = $this->db->get('mp_temp_urls');
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return NULL;
		}
	}
	// USED TO DELETE THE TOKEN
	public function delete_token($user_id, $token, $tstamp, $data_id)

	{
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		$this->db->where(['user_id' => $user_id]);
		$this->db->where(['token' => $token]);
		$this->db->where(['tstamp' => $tstamp]);
		$this->db->where(['data_id' => $data_id]);
		$this->db->delete('mp_temp_urls');
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		$this->db->db_debug = $db_debug;
	}
	public function get_by_id($table, $id)

	{
		$this->db->from($table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	// USED TO FIND THE CHEQUES
	function get_single_bank_trans($trans_id, $entry_type)
	{
		$this->db->select('mp_generalentry.date,mp_generalentry.naration,mp_sub_entry.accounthead,mp_bank_transaction.*,mp_bank_transaction_payee.payee_id');
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = $entry_type ");
		$this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id");
		$this->db->where('mp_generalentry.id', $trans_id);
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
	// USED TO GET THE SINGLE TRANSACTION
	function get_single_trans($trans_id, $entry_type)
	{
		$this->db->select('mp_generalentry.date,mp_generalentry.naration,mp_sub_entry.*,mp_head.name');
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = $entry_type ");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->where('mp_generalentry.id', $trans_id);
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
	// USED TO GET THE SINGLE TRANSACTION
	function get_single_trans_all($trans_id)
	{
		$this->db->select('mp_generalentry.date,mp_generalentry.naration,mp_sub_entry.*,mp_head.name');
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->where('mp_generalentry.id', $trans_id);
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
	// USED TO FIND PAYMENT VOUCHERS
	function fetch_record_payment_voucher($trans_id)
	{
		$this->db->select('mp_payment_voucher.*,mp_payee.customer_name');
		$this->db->from('mp_payment_voucher');
		$this->db->join('mp_payee', "mp_payee.id = mp_payment_voucher.payee_id");
		$this->db->where('mp_payment_voucher.transaction_id', $trans_id);
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
	// USED TO FIND THE CHEQUES FOR PRINT OR PREVIEW
	function get_single_cheque($trans_id, $entry_type)
	{
		$this->db->select('mp_generalentry.id as main_trans_id,mp_generalentry.date,mp_generalentry.naration,mp_banks.bankname,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.ref_no,mp_bank_transaction.total_paid,mp_bank_transaction.total_bill,mp_bank_transaction.transaction_status,mp_head.name as headname');
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = $entry_type ");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id");
		$this->db->join('mp_banks', "mp_bank_transaction.bank_id = mp_banks.id");
		$this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
		$this->db->where('mp_generalentry.id', $trans_id);
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
	// USED TO FIND THE SINGLE BANK COLLECTION
	function get_single_bank_collection($trans_id, $entry_type)
	{
		$this->db->select('mp_generalentry.id as main_trans_id,mp_generalentry.date,mp_generalentry.naration,mp_banks.bankname,mp_payee.customer_name,mp_sub_entry.amount,mp_bank_transaction.id as bank_trans_id,mp_bank_transaction.ref_no,mp_bank_transaction.total_paid,mp_bank_transaction.total_bill,mp_bank_transaction.transaction_status,mp_head.name as headname');
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = $entry_type ");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id");
		$this->db->join('mp_banks', "mp_bank_transaction.bank_id = mp_banks.id");
		$this->db->join('mp_bank_transaction_payee', "mp_bank_transaction_payee.transaction_id = mp_generalentry.id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction_payee.payee_id");
		$this->db->where('mp_generalentry.id', $trans_id);
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
	// USED TO GENETERATE ACCOUNT STATEMENTS
	function payee_account_statement($payee_id)
	{
		$this->db->select('mp_generalentry.id as main_trans_id,mp_generalentry.date,mp_generalentry.generated_source, ');
		$this->db->from('mp_generalentry');
		// $this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id AND mp_sub_entry.type = $entry_type ");
		$this->db->join('mp_sub_entry', "mp_generalentry.id = mp_sub_entry.parent_id");
		$this->db->join('mp_bank_transaction', "mp_bank_transaction.transaction_id = mp_generalentry.id");
		$this->db->join('mp_banks', "mp_banks.id = mp_bank_transaction.bank_id");
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->join('mp_payee', "mp_payee.id = mp_bank_transaction.payee_id");
		$this->db->where('mp_generalentry.id', $trans_id);
	}
	// USED TO FETCH ACCOUNTS RECORD
	function fetch_single_voucher($v_id, $type)
	{
		$this->db->select('*');
		$this->db->from('mp_payment_voucher');
		$this->db->where('type', $type);
		$this->db->where('transaction_id', $v_id);
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
	// USED TO FETCH ACCOUNTS RECORD
	function get_single_child_trans($tran_id, $type = '')
	{
		$this->db->select('mp_sub_entry.*,mp_head.name');
		$this->db->from('mp_sub_entry');
		$this->db->join('mp_head', "mp_head.id = mp_sub_entry.accounthead");
		$this->db->where('mp_sub_entry.parent_id', $tran_id);
		if ($type != '')
		{
			$this->db->where('mp_sub_entry.type', $type);
		}
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
	// USED TO COUNT SINGLE HEAD
	public function count_bank_amount($head_id, $date, $bank_id)

	{
		$count_total_amt = 0;
		$this->db->select("mp_generalentry.id as transaction_id,mp_generalentry.date,mp_generalentry.naration,mp_sub_entry.*");
		$this->db->from('mp_sub_entry');
		$this->db->join('mp_generalentry', 'mp_generalentry.id = mp_sub_entry.parent_id');
		$this->db->join('mp_bank_transaction', 'mp_bank_transaction.transaction_id = mp_generalentry.id');
		$this->db->where('mp_sub_entry.accounthead', $head_id);
		$this->db->where('mp_bank_transaction.bank_id', $bank_id);
		$this->db->where('mp_generalentry.date <=', $date);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$ledger_data = $query->result();
			$count_total_amt = 0;
			if ($ledger_data != NULL)
			{
				foreach($ledger_data as $single_ledger)
				{
					// if($this->check_condition_allowed($single_ledger->parent_id))
					// {
					if ($single_ledger->type == 0)
					{
						$count_total_amt = $count_total_amt + $single_ledger->amount;
					}
					else
					{
						$count_total_amt = $count_total_amt - $single_ledger->amount;
					}
					// }
				}
			}
		}
		if ($count_total_amt == 0)
		{
			$count_total_amt = NULL;
		}
		else
		{
			$count_total_amt = number_format($count_total_amt, '2', '.', '');
		}
		return $count_total_amt;
	}
	// USED TO CREATE ACCOUNT STATEMENT
	function fetch_account_statement($account_id, $date1, $date2, $period)
	{
		$trans_arr = array();
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_bank_transaction.*,
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_bank_transaction', 'mp_bank_transaction.transaction_id = mp_generalentry.id');
		$this->db->join('mp_bank_transaction_payee', 'mp_bank_transaction_payee.transaction_id = mp_generalentry.id');
		$this->db->where('mp_bank_transaction_payee.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_credit_note.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_credit_note', 'mp_credit_note.transaction_id = mp_generalentry.id');
		$this->db->where('mp_generalentry.date >=', $date1);
		$this->db->where('mp_generalentry.date <=', $date2);
		$this->db->where('mp_credit_note.payee_id', $account_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_expense.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_expense', 'mp_expense.transaction_id = mp_generalentry.id');
		$this->db->where('mp_expense.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_invoices.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_invoices', 'mp_invoices.transaction_id = mp_generalentry.id');
		$this->db->where('mp_invoices.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		/*  $this->db->select("
		mp_generalentry.id as transaction_id,
		mp_generalentry.date,
		mp_generalentry.naration,
		mp_generalentry.generated_source,
		mp_payee_payments.*
		");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_payee_payments', 'mp_payee_payments.transaction_id = mp_generalentry.id');
		$this->db->where('mp_payee_payments.payee_id', $account_id);
		if($period != 'all')
		{
		$this->db->where('mp_generalentry.date >=', $date1);
		$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
		$result  =  $query->result();
		foreach ($result as $single_transaction)
		{
		$trans_arr [] = $single_transaction;
		}
		*/
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_purchase_receipt.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_purchase_receipt', 'mp_purchase_receipt.transaction_id = mp_generalentry.id');
		$this->db->where('mp_purchase_receipt.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_purchase_return.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_purchase_return', 'mp_purchase_return.transaction_id = mp_generalentry.id');
		$this->db->where('mp_purchase_return.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_refund.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_refund', 'mp_refund.transaction_id = mp_generalentry.id');
		$this->db->where('mp_refund.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_sales_receipt.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_sales_receipt', 'mp_sales_receipt.transaction_id = mp_generalentry.id');
		$this->db->where('mp_sales_receipt.payee_id', $account_id);
		if ($period != 'all')
		{
			$this->db->where('mp_generalentry.date >=', $date1);
			$this->db->where('mp_generalentry.date <=', $date2);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		$this->db->select("
        mp_generalentry.id as transaction_id,
        mp_generalentry.date,
        mp_generalentry.naration,
        mp_generalentry.generated_source,
        mp_payment_voucher.*
        ");
		$this->db->from('mp_generalentry');
		$this->db->join('mp_payment_voucher', 'mp_payment_voucher.transaction_id = mp_generalentry.id');
		$this->db->where('mp_generalentry.date >=', $date1);
		$this->db->where('mp_generalentry.date <=', $date2);
		$this->db->where('mp_payment_voucher.payee_id', $account_id);
		$this->db->where('mp_payment_voucher.type !=', 2);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $single_transaction)
			{
				$trans_arr[] = $single_transaction;
			}
		}
		usort($trans_arr, function ($a, $b)
		{
			// return $a->transaction_id <=> $b->transaction_id;
			return $a->transaction_id - $b->transaction_id;
		});
		return $trans_arr;
	}

	//USED TO FETCH JOINED ROLES AND OPTIONS 
	function get_availabel_options()
	{
		$data = [];
		
		$this->db->select("*");
		$this->db->from('mp_menu');
		
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$result = $query->result();

			foreach($result as $single_row)
			{
				$this->db->select("*");
				$this->db->from('mp_menulist');
				$this->db->where('menu_id', $single_row->id);

				$querySub = $this->db->get();
				if ($querySub->num_rows() > 0)
				{
					$resultSub = $querySub->result();

					$data [] = array(array('id'=>$single_row->id,'name'=>$single_row->name,'options'=>$resultSub));
				}
				else
				{
					$data [] = array($single_row,'');
				}
				
			}		
			
		}
		else
		{
			$result = NULL;	
		}

		return $data;
	}
}
