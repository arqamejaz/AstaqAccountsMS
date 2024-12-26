<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/

if (!function_exists('check_allowed_access'))
{
	function check_allowed_access($user_id,$class_name,$method_name)
	{

		$url =  $class_name.'/'.$method_name;

		if($method_name != 'popup')
		{
			// $CI =& get_instance();
			// $CI->load->database();

			// $CI->db->select('*');
			// $CI->db->from('mp_routes');
			// $CI->db->where('url',$url);
			// $query = $CI->db->get();

			// if($query->num_rows() > 0)
			// {

			// }
			// else
			// {
			// 	$data = array(
			// 		'menulist_id' => 1,
			// 		'url' => $url,
			// 		'permission' => 'read'
			// 	);

			// 	$CI->db->insert('mp_routes',$data);
			// }

			$permission = '';

			$CI =& get_instance();
			$CI->load->database();
			$CI->db->select('*');
			$CI->db->from('mp_routes');
			$CI->db->where('url',$url);
			$query = $CI->db->get();
			$routes = $query->result_array();

			if($routes != NULL)
			{

				$CI->db->select('*');
				$CI->db->from('mp_multipleroles');
				$CI->db->where('menu_Id',$routes[0]['menulist_id']);
				$CI->db->where('user_id',$user_id);

				$permission_query = $CI->db->get();
				$permission = $permission_query->result_array();

				if($permission != NULL)
				{
					$assigned = $permission[0]['permission'];

					if($assigned == 'no')
					{
						$permission =  'no-access';
					}
					else if($assigned == 'write')
					{
						$permission =  'access';
					}
					else if($assigned == 'read' AND $routes[0]['permission'] == 'read')
					{
						$permission =  'access';
					}
					else
					{
						$permission =  'no-access';
					}
				}
				else
				{
					$permission =  'no-access';
				}
			}
			else
			{
				$permission =  'no-access';
			}


			if($permission ==  'access')
			{
				return $permission;
			}
			else
			{

				return "no-access";
			}

		}
		else
		{

			return "access";
		}
	}
}

if ( !function_exists('export_csv'))
{
	function export_csv($file_name,$args_fileheader,$args_table_attr,$table_name)
	{
		$newfilename = "CSV_".$file_name.date("YmdH_i_s").'.csv';
		header('Content-type:text/csv');
		header('Content-Disposition:attachment; filename='.$newfilename);
		header('Cache-Control:no-store,no-cache,must-revalidate');
		header('Cache-Control:post-check=0,pre-check=0');
		header('Pragma:no-cache');
		header('Expires:0');
		header('Content-type:text/csv');
		 $handle = fopen("php://output", "w");
		 fputcsv($handle,$args_fileheader);
		 $CI =& get_instance();
		 $CI->load->database();
		 $CI->db->select($args_table_attr);
		 $CI->db->from($table_name);
		 $query = $CI->db->get();
		 $data['tasks'] = $query->result_array();
		 foreach ($data['tasks'] as $key => $row)
		 {
			 fputcsv($handle,$row);
		 }
		 fclose($handle);
		 exit;
	}
}
// ------------------------------------------------------------------------
/* End of file helper.php */
/* Location: ./system/helpers/Authenticate_Url_helper.php */