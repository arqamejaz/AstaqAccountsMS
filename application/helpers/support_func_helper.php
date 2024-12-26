<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
if (!function_exists('color_options'))
{
	//USED TO FETCH AND COUNT THE NUMBER OF OCCURANCE IN RETURN STOCK
	function color_options()
	{
		$CI	=&	get_instance();
		$CI->load->database();
		$color_arr = $CI->db->get_where('mp_langingpage', array('id' =>1))->result_array()[0];
		return  array('primary' =>$color_arr['primarycolor'],'hover' =>$color_arr['theme_pri_hover']);
	}
}

if (!function_exists('source_identifier'))
{
	//USED TO FETCH AND COUNT THE NUMBER OF OCCURANCE IN RETURN STOCK
	function source_identifier($source)
	{
		if($source == 'debit_voucher' OR $source == 'expense' OR $source == 'purchase_receipt' OR $source == 'cheque'OR $source == 'refund_receipt')
		{
			$data = 'yes';
		}
		else
		{
			$data = 'no';
		}	

		return $data;
	}
}

if (!function_exists('fetch_assigned_roles'))
{
	function fetch_assigned_roles($user_id,$para_menu_id) 
	{
		$CI	=&	get_instance();
		$CI->load->database();
		$CI->db->select("*");
		$CI->db->from('mp_multipleroles');
		$CI->db->where('mp_multipleroles.menu_Id',$para_menu_id);
		$CI->db->where('mp_multipleroles.user_id',$user_id);
		$query = $CI->db->get();
		
		if($query->num_rows() > 0)
		{
			return  $query->result();
		}
		else
		{
			return NULL;
		}		
	}
}


if (!function_exists('balance_identifier'))
{
	//USED TO FETCH AND COUNT THE NUMBER OF OCCURANCE IN RETURN STOCK
	function balance_identifier($source,$current_balance,$total_bill,$total_paid)
	{
		$balance = 0;
		
		switch($source)
		{	

			case  'Opening_balance':
			{
				$balance = $current_balance + $total_bill;
				$balance = $balance - $total_paid;
				break;
			}

			case  'bank_collection':
			{
				$balance = $current_balance - $total_paid;
				break;
			}

			case  'debit_voucher':
			{
				$balance = $current_balance + $total_paid;	
				break;
			}
			case  'expense':
			{
			
				$balance = $current_balance - ($total_bill - $total_paid);
				break;
			}
			case  'purchase_receipt':
			{

				$balance = $current_balance - ($total_bill - $total_paid);
				break;

			}
			case  'purchase_return':
			{
				
				$balance = $current_balance  + ($total_bill - $total_paid);
				break;
			}
			case  'cheque':
			{

				$balance = $current_balance + $total_paid;
				break;
			}
			case  'refund_receipt':
			{
				
				$balance = $current_balance - ($total_bill - $total_paid);
				break;
			}
			case  'credit_note':
			{

				$balance = $current_balance -  $total_paid;
				break;
			}
			case  'deposit':
			{
				$balance = $current_balance -  $total_paid;
				break;
			}
			case  'sales_receipt':
			{

				$balance = $current_balance +  ($total_bill - $total_paid);
				break;
			}	
				

			case  'credit_voucher':
			{

				$balance = $current_balance -  $total_paid;
				break;
			}	
			default :
			{

			}
		}

		return $balance;
	}
}
// ------------------------------------------------------------------------
/* End of file helper.php */
/* Location: ./system/helpers/Side_Menu_helper.php */