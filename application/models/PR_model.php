<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
class PR_model extends CI_Model
{
    //USED TO ADD SALES TRANSACTIONS 
    function add_purchase_return_transaction($data_fields)
    {
        $this->load->model('Crud_model');

        $this->db->trans_start();
        $data1  = array(
        'date'                 => date('Y-m-d'), 
        'naration'             => $data_fields['memo'], 
        'generated_source'     => 'purchase_return'
        );

        $this->db->insert('mp_generalentry',$data1);
        $data_fields['transaction_id'] = $this->db->insert_id();

         $data1  = array(
          'transaction_id'  => $data_fields['transaction_id'], 
          'date'            => $data_fields['date'], 
          'user'            => $data_fields['user'],
          'payee_id'        => $data_fields['payee_id'],
          'method'          => $data_fields['payment_method'],
          'ref_no'          => $data_fields['ref_no'],
          'billing_address' => $data_fields['billing_address'],
          'total_bill'      => $data_fields['total_bill'],
          'total_paid'      => $data_fields['total_received'],
          'invoicemessage'  => $data_fields['invoicemessage'],
          'memo'            => $data_fields['memo'],
          'attachment'      => $data_fields['attachment']
         );

        $this->db->insert('mp_purchase_return',$data1);
        $data_fields['purchase_id'] = $this->db->insert_id();

        

        if($data_fields['total_bill'] == $data_fields['total_received'])
        {
            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => $data_fields['debithead'], 
            'amount'      => $data_fields['total_bill'], 
            'type'        => 0
            );

            $this->db->insert('mp_sub_entry',$sub_data);   
        }
        else if($data_fields['total_bill'] > $data_fields['total_received'])
        {
            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => $data_fields['debithead'], 
            'amount'      => $data_fields['total_received'], 
            'type'        => 0
            ); 

            $this->db->insert('mp_sub_entry',$sub_data);

            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => 4, //AR 
            'amount'      => $data_fields['total_bill']-$data_fields['total_received'], 
            'type'        => 0
            );

            $this->db->insert('mp_sub_entry',$sub_data); 
        }

        for ($i = 0; $i < count($data_fields['product']); $i++) 
        {

            /*$this->db->where(['id' => $data_fields['product'][$i]]);
            $query = $this->db->get('mp_product');
            $result = $query->result();*/

            //1ST ENTRY
            $sub_data  = array(
            'sales_id'    => $data_fields['purchase_id'], 
            'product_id'  => $data_fields['product'][$i], 
            'description' => $data_fields['descriptionarr'][$i], 
            'qty'         => $data_fields['qty'][$i],
            'price'       => $data_fields['price'][$i]
            //'tax'         => $data_fields['single_tax'][$i]
            );

            $this->db->insert('mp_sub_purchase_return',$sub_data);
        }

        $sub_data  = array(
        'parent_id'   => $data_fields['transaction_id'], 
        'accounthead' => 3, 
        'amount'      => $data_fields['total_bill'], 
        'type'        => 1
        );

        $this->db->insert('mp_sub_entry',$sub_data); 

        if($data_fields['debithead'] == 16)
        {
           //TRANSACTION DETAILS 
            $sub_data  = array(
            'transaction_id'      => $data_fields['transaction_id'], 
            'bank_id'             => $data_fields['bank_id'], 
            'method'              => $data_fields['payment_method'],
            'total_bill'          => $data_fields['total_bill'],
            'total_paid'          => $data_fields['total_received'],
            'ref_no'              => $data_fields['ref_no'],
            'transaction_status'  => 1,
            'transaction_type'    => 'recieved'
            );
            $this->db->insert('mp_bank_transaction',$sub_data); 

            //TRANSACTION DETAILS 
            $sub_data  = array(
            'transaction_id'      => $data_fields['transaction_id'], 
            'payee_id'            => $data_fields['payee_id']
            );
            $this->db->insert('mp_bank_transaction_payee',$sub_data); 
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

    //USED TO ADD SALES TRANSACTIONS 
    function update_purchase_return_transaction($data_fields)
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
              'payee_id'        => $data_fields['payee_id'],
              'method'          => $data_fields['payment_method'],
              'ref_no'          => $data_fields['ref_no'],
              'billing_address' => $data_fields['billing_address'],
              'total_bill'      => $data_fields['total_bill'],
              'total_paid'  => $data_fields['total_received'],
              'invoicemessage'  => $data_fields['invoicemessage'],
              'memo'            => $data_fields['memo']
             );
        }
        else
        {
            $data  = array(
              'transaction_id'  => $data_fields['transaction_id'], 
              'payee_id'        => $data_fields['payee_id'],
              'method'          => $data_fields['payment_method'],
              'ref_no'          => $data_fields['ref_no'],
              'billing_address' => $data_fields['billing_address'],
              'total_bill'      => $data_fields['total_bill'],
              'total_paid'  => $data_fields['total_received'],
              'invoicemessage'  => $data_fields['invoicemessage'],
              'memo'            => $data_fields['memo'],
              'attachment'      => $data_fields['attachment']
            );
        }

        $this->db->where('id',$data_fields['receipt_id']);
        $this->db->update('mp_purchase_return',$data);

        //DELETEING THE PREVIOUS ACCOUNTS TRANSACTION
        $this->db->where(['parent_id' => $data_fields['transaction_id']]);
        $this->db->delete('mp_sub_entry');

        //DELETEING THE PREVIOUS SUB CREDIT ENTRY
        $this->db->where(['sales_id' => $data_fields['receipt_id']]);
        $this->db->delete('mp_sub_purchase_return');

         if($data_fields['total_bill'] == $data_fields['total_received'])
        {
            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => $data_fields['debithead'], 
            'amount'      => $data_fields['total_bill'], 
            'type'        => 0
            );

            $this->db->insert('mp_sub_entry',$sub_data);   
        }
        else if($data_fields['total_bill'] > $data_fields['total_received'])
        {
            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => $data_fields['debithead'], 
            'amount'      => $data_fields['total_received'], 
            'type'        => 0
            ); 

            $this->db->insert('mp_sub_entry',$sub_data);

            //1ST ENTRY
            $sub_data  = array(
            'parent_id'   => $data_fields['transaction_id'], 
            'accounthead' => 4, //AR 
            'amount'      => $data_fields['total_bill']-$data_fields['total_received'], 
            'type'        => 0
            );

            $this->db->insert('mp_sub_entry',$sub_data); 
        }

       

        for ($i = 0; $i < count($data_fields['product']); $i++) 
        {

            /*$this->db->where(['id' => $data_fields['product'][$i]]);
            $query = $this->db->get('mp_product');
            $result = $query->result();*/

            //1ST ENTRY
            $sub_data  = array(
            'sales_id'    => $data_fields['receipt_id'], 
            'product_id'  => $data_fields['product'][$i], 
            'description' => $data_fields['descriptionarr'][$i], 
            'qty'         => $data_fields['qty'][$i],
            'price'       => $data_fields['price'][$i]
           // 'tax'         => $data_fields['single_tax'][$i]
            );

            $this->db->insert('mp_sub_purchase_return',$sub_data);
        }

        $sub_data  = array(
        'parent_id'   => $data_fields['transaction_id'], 
        'accounthead' => 3, 
        'amount'      => $data_fields['total_bill'], 
        'type'        => 1
        );

        $this->db->insert('mp_sub_entry',$sub_data);

        if($data_fields['debithead'] == 16)
        {
             //DELETEING THE PREVIOUS ACCOUNTS TRANSACTION
            $this->db->where(['transaction_id' => $data_fields['transaction_id']]);
            $this->db->delete('mp_bank_transaction'); 

            //DELETEING THE PREVIOUS ACCOUNTS TRANSACTION
            $this->db->where(['transaction_id' => $data_fields['transaction_id']]);
            $this->db->delete('mp_bank_transaction_payee');
            
           //TRANSACTION DETAILS 
            $sub_data  = array(
            'transaction_id'      => $data_fields['transaction_id'], 
            'bank_id'             => $data_fields['bank_id'], 
            'method'              => $data_fields['payment_method'],
            'total_bill'          => $data_fields['total_bill'],
            'total_paid'          => $data_fields['total_received'],
            'ref_no'              => $data_fields['ref_no'],
            'transaction_status'  => 1,
            'transaction_type'    => 'recieved'
            );
            $this->db->insert('mp_bank_transaction',$sub_data); 

            //TRANSACTION DETAILS 
            $sub_data  = array(
            'transaction_id'      => $data_fields['transaction_id'], 
            'payee_id'            => $data_fields['payee_id'],  
            );
            $this->db->insert('mp_bank_transaction_payee',$sub_data); 
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