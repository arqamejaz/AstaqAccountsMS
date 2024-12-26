<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
class Invoice_model extends CI_Model
{
	//USED TO ADD EXPENSES TRANSACTIONS
	function add_invoice_transaction($data_fields)
	{
		$this->db->trans_start();
		$data1  = array(
		'date'                 => date('Y-m-d'),
		'naration'             => $data_fields['memo'],
		'generated_source'     => 'invoice'
		);

		$this->db->insert('mp_generalentry',$data1);
		$data_fields['transaction_id'] = $this->db->insert_id();

		 $data1  = array(
		  'transaction_id'  => $data_fields['transaction_id'],
		  'total_bill'      => $data_fields['total_bill'],
		  'total_paid'      => 0,
		  'date'            => $data_fields['date'],
		  'user'            => $data_fields['user'],
		  'description'     => $data_fields['memo'],
		  'invoicemessage'  => $data_fields['invoicemessage'],
		  'payee_id'        => $data_fields['payee_id'],
		  'billing'         => $data_fields['billing_address'],
		  'due_date'        => $data_fields['due_date'],
		  'attachment'      => $data_fields['attachment']
		 );

		$this->db->insert('mp_invoices',$data1);
		$data_fields['invoice_id'] = $this->db->insert_id();

		//1ST ENTRY
		$sub_data  = array(
		'parent_id'   => $data_fields['transaction_id'],
		'accounthead' => 4, //A/R
		'amount'      => $data_fields['total_bill'],
		'type'        => 0
		);
		$this->db->insert('mp_sub_entry',$sub_data);

		 //FOR IDENTIFYING ANY FINISHED ITEM IN PRODUCTS TO FIND COST
		for ($i = 0; $i < count($data_fields['product']); $i++)
		{

			$this->db->where(['id' => $data_fields['product'][$i]]);
			$query = $this->db->get('mp_product');
			$result = $query->result();

			//CHECKING OF ANY NOT SERVICE
			if($result[0]->type == 1)
			{
				//1ST ENTRY
				$sub_data  = array(
					'parent_id'   => $data_fields['transaction_id'],
					'accounthead' => 26,//COST OF GOODS
					'amount'      => $result[0]->cost * $data_fields['qty'][$i],
					'type'        => 0
				);

				$this->db->insert('mp_sub_entry',$sub_data);
			}
		}

		for ($i = 0; $i < count($data_fields['product']); $i++)
		{

			$this->db->where(['id' => $data_fields['product'][$i]]);
			$query = $this->db->get('mp_product');
			$result = $query->result();

		   //1ST ENTRY
			$sub_data  = array(
			'parent_id'   => $data_fields['transaction_id'],
			'accounthead' => $result[0]->head_id,
			'amount'      => ($data_fields['price'][$i] * $data_fields['qty'][$i]),
			'type'        => 1
			);
			$this->db->insert('mp_sub_entry',$sub_data);

			//CHECKING OF ANY NOT SERVICE
			if($result[0]->type == 1)
			{
				//1ST ENTRY
				$sub_data  = array(
					'parent_id'   => $data_fields['transaction_id'],
					'accounthead' => 3,//INVENTORY
					'amount'      => $result[0]->cost * $data_fields['qty'][$i],
					'type'        => 1
				);

				$this->db->insert('mp_sub_entry',$sub_data);
			}

			//1ST ENTRY
			$sub_data  = array(
			'invoice_id'  => $data_fields['invoice_id'],
			'product_id'  => $data_fields['product'][$i],
			'description' => $data_fields['descriptionarr'][$i],
			'qty'         => $data_fields['qty'][$i],
			'price'       => $data_fields['price'][$i],
			'tax'         => $data_fields['single_tax'][$i]
			);
			$this->db->insert('mp_sales',$sub_data);
		}

		if($data_fields['total_tax'] > 0)
		{
		   //1ST ENTRY
			$sub_data  = array(
				'parent_id'   => $data_fields['transaction_id'],
				'accounthead' => 25,
				'amount'      => $data_fields['total_tax'],
				'type'        => 1
			);

			$this->db->insert('mp_sub_entry',$sub_data);
		}


		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data_fields = NULL;
		}
		else
		{
			$this->db->trans_commit();
		}

		return $data_fields;
	}

	//USED TO ADD PAYMENT TRANSACTIONS
	function add_payment_transaction($data_fields)
	{
		$this->db->trans_start();

		for ($i = 0; $i < count($data_fields['invoice_id']); $i++)
		{
			$total_paid_amount = $data_fields['payments'][$i]  + $data_fields['invoice_paid'][$i];

			if($data_fields['payments'][$i] > 0 AND  $total_paid_amount <= $data_fields['invoice_bill'][$i])
			{

				if($total_paid_amount == $data_fields['invoice_bill'][$i])
				{
					$status = 2;
				}
				else if($total_paid_amount < $data_fields['invoice_bill'][$i] AND $total_paid_amount > 0)
				{

					$status = 1;
				}
				else
				{
					$status = 0;
				}

				$data  = array(
					'total_paid' => $data_fields['payments'][$i] + $data_fields['invoice_paid'][$i],
					'status' => $status
				);

				$this->db->where('id',$data_fields['invoice_id'][$i]);
				$this->db->update('mp_invoices',$data);

				$data1  = array(
				'date'             => date('Y-m-d'),
				'naration'         => $data_fields['memo'],
				'generated_source' => 'received_payments'
				);

				$this->db->insert('mp_generalentry',$data1);
				$transaction_id = $this->db->insert_id();

				$data1  = array(
					'transaction_id'  => $transaction_id,
					'invoice_id'      => $data_fields['invoice_id'][$i],
					'total_bill'      => 0,
					'total_paid'      => $data_fields['payments'][$i],
					'method'          => $data_fields['payment_method'],
					'date'            => $data_fields['date'],
					'agentname'       => $data_fields['user'],
					'description'     => $data_fields['memo'],
					'payee_id'        => $data_fields['payee_id'],
					'mode'            => 'received',
				   // 'attachment'      => $data_fields['attachment'],
					'ref_no'           => $data_fields['ref_no']
				);

				$this->db->insert('mp_payee_payments',$data1);
				$payment_id = $this->db->insert_id();

				//1ST ENTRY
				$sub_data  = array(
				'parent_id'   => $transaction_id,
				'accounthead' => $data_fields['debithead'], //CASH
				'amount'      => $data_fields['payments'][$i],
				'type'        => 0
				);

				$this->db->insert('mp_sub_entry',$sub_data);

			   //1ST ENTRY
				$sub_data  = array(
				'parent_id'   => $transaction_id,
				'accounthead' => 4, //A/R
				'amount'      => $data_fields['payments'][$i],
				'type'        => 1
				);
				$this->db->insert('mp_sub_entry',$sub_data);

				if($data_fields['debithead'] == 16)
				{

					//TRANSACTION DETAILS
					$sub_data  = array(
					'transaction_id'      => $transaction_id,
					'bank_id'             => $data_fields['bank_id'],
					'method'              => $data_fields['payment_method'],
					'total_bill'          => $data_fields['payments'][$i],
					'total_paid'          => $data_fields['payments'][$i],
					'ref_no'              => $data_fields['ref_no'],
					'transaction_status'  => 1,
					'transaction_type'    => 'recieved'
					);
					$this->db->insert('mp_bank_transaction',$sub_data);


					//TRANSACTION DETAILS
					$sub_data  = array(
					'transaction_id'      => $transaction_id,
					'payee_id'            => $data_fields['payee_id'],
					);

					$this->db->insert('mp_bank_transaction_payee',$sub_data);
				}
			}
			else
			{
			 // $this->db->trans_rollback();
			 // return $data_fields = NULL;
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$data_fields = NULL;
			}
			else
			{
				$this->db->trans_commit();
			}


		}

		  return $data_fields;
	}

	//USED TO ADD EXPENSES TRANSACTIONS
	function update_invoice_transaction($data_fields)
	{
		$this->db->trans_start();

		$data  = array(
			'naration' => $data_fields['memo']
		);

		$this->db->where('id',$data_fields['transaction_id']);
		$this->db->update('mp_generalentry',$data);

		if($data_fields['attachment'] == 'default.jpg')
		{
			$data  = array(
			  'transaction_id'  => $data_fields['transaction_id'],
			  'total_bill'      => $data_fields['total_bill'],
			  'description'     => $data_fields['memo'],
			  'invoicemessage'  => $data_fields['invoicemessage'],
			  'payee_id'        => $data_fields['payee_id'],
			  'billing'         => $data_fields['billing_address'],
			  'due_date'        => $data_fields['due_date']
			);
		}
		else
		{
			$data  = array(
			  'transaction_id'  => $data_fields['transaction_id'],
			  'total_bill'      => $data_fields['total_bill'],
			  'description'     => $data_fields['memo'],
			  'invoicemessage'  => $data_fields['invoicemessage'],
			  'payee_id'        => $data_fields['payee_id'],
			  'billing'         => $data_fields['billing_address'],
			  'due_date'        => $data_fields['due_date'],
			  'attachment'      => $data_fields['attachment']
			);
		}


		$this->db->where('id',$data_fields['invoice_id']);
		$this->db->update('mp_invoices',$data);

		//DELETEING THE PREVIOUS ACCOUNTS TRANSACTION
		$this->db->where(['parent_id' => $data_fields['transaction_id']]);
		$this->db->delete('mp_sub_entry');

		//DELETEING THE PREVIOUS SUB CREDIT ENTRY
		$this->db->where(['invoice_id' => $data_fields['invoice_id']]);
		$this->db->delete('mp_sales');

		//1ST ENTRY
		$sub_data  = array(
		'parent_id'   => $data_fields['transaction_id'],
		'accounthead' => 4, //A/R
		'amount'      => $data_fields['total_bill'],
		'type'        => 0
		);
		$this->db->insert('mp_sub_entry',$sub_data);

		//FOR IDENTIFYING ANY FINISHED ITEM IN PRODUCTS TO FIND COST
		for ($i = 0; $i < count($data_fields['product']); $i++)
		{

			$this->db->where(['id' => $data_fields['product'][$i]]);
			$query = $this->db->get('mp_product');
			$result = $query->result();

			//CHECKING OF ANY NOT SERVICE
			if($result[0]->type == 1)
			{
				//1ST ENTRY
				$sub_data  = array(
					'parent_id'   => $data_fields['transaction_id'],
					'accounthead' => 26,//COST OF GOODS
					'amount'      => $result[0]->cost * $data_fields['qty'][$i],
					'type'        => 0
				);

				$this->db->insert('mp_sub_entry',$sub_data);
			}
		}

		for ($i = 0; $i < count($data_fields['product']); $i++)
		{

			$this->db->where(['id' => $data_fields['product'][$i]]);
			$query = $this->db->get('mp_product');
			$result = $query->result();

		   //1ST ENTRY
			$sub_data  = array(
			'parent_id'   => $data_fields['transaction_id'],
			'accounthead' => $result[0]->head_id,
			'amount'      => ($data_fields['price'][$i] * $data_fields['qty'][$i]),
			'type'        => 1
			);
			$this->db->insert('mp_sub_entry',$sub_data);

			//CHECKING OF ANY NOT SERVICE
			if($result[0]->type == 1)
			{
				//1ST ENTRY
				$sub_data  = array(
					'parent_id'   => $data_fields['transaction_id'],
					'accounthead' => 3,//INVENTORY
					'amount'      => $result[0]->cost * $data_fields['qty'][$i],
					'type'        => 1
				);

				$this->db->insert('mp_sub_entry',$sub_data);
			}

			//1ST ENTRY
			$sub_data  = array(
			'invoice_id'  => $data_fields['invoice_id'],
			'product_id'  => $data_fields['product'][$i],
			'description' => $data_fields['descriptionarr'][$i],
			'qty'         => $data_fields['qty'][$i],
			'price'       => $data_fields['price'][$i],
			'tax'         => $data_fields['single_tax'][$i]
			);
			$this->db->insert('mp_sales',$sub_data);
		}

		if($data_fields['total_tax'] > 0)
		{
		   //1ST ENTRY
			$sub_data  = array(
				'parent_id'   => $data_fields['transaction_id'],
				'accounthead' => 25,
				'amount'      => $data_fields['total_tax'],
				'type'        => 1
			);

			$this->db->insert('mp_sub_entry',$sub_data);
		}


		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data_fields = NULL;
		}
		else
		{
			$this->db->trans_commit();
		}

		return $data_fields;
	}
}