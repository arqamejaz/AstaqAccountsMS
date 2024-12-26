<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
if ( !function_exists('fetch_users_access_control_menu'))
{
	function fetch_users_access_control_menu($para_user_id = '')
	{

		$CI	=&	get_instance();
		$CI->load->database();
		$CI->db->select("mp_menu.id as main_id,mp_menu.name,mp_menu.icon");
		$CI->db->from('mp_menu');
		$CI->db->join('mp_menulist', "mp_menu.id = mp_menulist.menu_id");
		$CI->db->join('mp_multipleroles', "mp_menulist.id = mp_multipleroles.menu_Id and mp_multipleroles.user_id = '$para_user_id' and mp_multipleroles.permission != 'no'");
		$CI->db->order_by('mp_menu.priority','ASC');
		$CI->db->group_by('main_id');
		$query = $CI->db->get();
		$result = $query->result_array();

		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return NULL;
		}
	}
}

if ( !function_exists('fetch_users_access_control_sub_menu'))
{
	function fetch_users_access_control_sub_menu($para_user_id,$para_menu_id = '')
	{
		$CI	=&	get_instance();
		$CI->load->database();
		$CI->db->select("*");
		$CI->db->from('mp_menulist');
		$CI->db->join('mp_multipleroles', "mp_menulist.id = mp_multipleroles.menu_Id and mp_multipleroles.user_id = '$para_user_id' and mp_multipleroles.permission != 'no'");
		$CI->db->where('mp_menulist.menu_Id',$para_menu_id);
		$query = $CI->db->get();
		$result = $query->result_array();

		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return NULL;
		}
	}
}
// ------------------------------------------------------------------------
/* End of file helper.php */
/* Location: ./system/helpers/Side_Menu_helper.php */