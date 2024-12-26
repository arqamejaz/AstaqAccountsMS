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
class Multiple_roles extends CI_Controller
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
	
	// MultipleRoles
	public function index()
	{
		// DEFINES PAGE TITLE
		$data['title'] = 'Multiple Roles';

		// DEFINES NAME OF TABLE HEADING
		$data['table_name'] = 'Assign Roles :';

		// DEFINES WHICH PAGE TO RENDER
		$data['main_view'] = 'multiple_roles';

		// DIFINES THE TABLE HEAD
		$data['table_heading_names_of_coloums'] = array(
			'No',
			'User Name',
			'Email',
			'Description',
			'Action',
		);
		// DEFINES TO LOAD THE CATEGORY LIST FROM DATABSE TABLE mp_Categoty
		$this->load->model('Crud_model');

		$data['privileges'] = $this->Crud_model->get_user_details_menus();

		// DEFINES GO TO MAIN FOLDER FOND INDEX.PHP  AND PASS THE ARRAY OF DATA TO THIS PAGE
		$this->load->view('main/index.php', $data);
	}

	//MultipleRoles/Add
	public function add_role()
	{
		// DEFINES READ CATEROTY NAME FORM Multiple FORM
		$user_id 		  = html_escape($this->input->post('user_id'));
		//$menu_id 		  = html_escape($this->input->post('menu_id'));
		$menulistid 	  = html_escape($this->input->post('menulistid'));
		//$permission_read  = html_escape($this->input->post('permission_read1'));
		//$permission_write = html_escape($this->input->post('permission_write1'));
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['id'];


		// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
		$this->load->model('Crud_model');
		if ($user_id != 0)
		{
			$counter = 1;
			foreach($menulistid as $read)
			{
				$permission_read  = html_escape($this->input->post('permission'.$counter));
				
				$permission = $this->Crud_model->check_role_duplication($user_id,$read);
	
				if ($permission == TRUE)
				{
					
					// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY FOR EVERY ITERATION
					$args = array(
						'user_id'   => $user_id,
						'menu_Id'   => $read,
						'agentid'   => $added_by,
						'permission'  => $permission_read
					);

					// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
					$result = $this->Crud_model->insert_data('mp_multipleroles', $args);
					
					if($result == 1)
					{
						$array_msg = array(
							'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="TRUE"></i> Roles added Successfully',
							'alert' => 'info',
						);
						$this->session->set_flashdata('status', $array_msg);
					}
					else
					{
						$array_msg = array(
							'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="TRUE"></i> Something went wrong',
							'alert' => 'danger',
						);
						$this->session->set_flashdata('status', $array_msg);
					}
					
				}

				$counter++;
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="TRUE"></i> Error No user is selected',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}


		redirect('multiple_roles');
	}

	//MultipleRoles/edit_role
	public function edit_role()
	{
		// DEFINES READ CATEROTY NAME FORM Multiple FORM
		$user_id 		  = html_escape($this->input->post('user_id'));
		$menulistid 	  = html_escape($this->input->post('menulistid'));
		$user_name = $this->session->userdata('user_id');
		$added_by = $user_name['id'];


		// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
		$this->load->model('Crud_model');
		if ($user_id != 0)
		{
			$this->Crud_model->delete_attr_record('mp_multipleroles','user_id',$user_id);

			$counter = 1;
			foreach($menulistid as $read)
			{
				$permission_read  = html_escape($this->input->post('permission'.$counter));
				
				$permission = $this->Crud_model->check_role_duplication($user_id,$read);
	
				if ($permission == TRUE)
				{
					
					// ASSIGN THE VALUES OF TEXTBOX TO ASSOCIATIVE ARRAY FOR EVERY ITERATION
					$args = array(
						'user_id'   => $user_id,
						'menu_Id'   => $read,
						'agentid'   => $added_by,
						'permission'  => $permission_read
					);

					// DEFINES CALL THE FUNCTION OF insert_data FORM Crud_model CLASS
					$result = $this->Crud_model->insert_data('mp_multipleroles', $args);
					
					if($result == 1)
					{
						$array_msg = array(
							'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="TRUE"></i> Roles updated successfully',
							'alert' => 'info',
						);
						$this->session->set_flashdata('status', $array_msg);
					}
					else
					{
						$array_msg = array(
							'msg' => '<i style="color:#fff" class="fa fa-check-circle-o" aria-hidden="TRUE"></i> Something went wrong',
							'alert' => 'danger',
						);
						$this->session->set_flashdata('status', $array_msg);
					}
					
				}

				$counter++;
			}
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="TRUE"></i> Error No user is selected',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}


		redirect('multiple_roles');
	}
	// DEFINES A POPUP MODEL OG GIVEN PARAMETER
	function popup($page_name = '', $param = '')
	{
		$this->load->model('Crud_model');

		if ($page_name == 'add_multipleroles_model')
		{
			$result_roles = $this->Crud_model->get_availabel_options();

			$data['result_roles'] = $result_roles;

			$data['user_list'] = $this->Crud_model->fetch_record('mp_users', 'status');

			// model name available in admin models folder
			$this->load->view('admin_models/add_models/add_multipleroles_model.php', $data);
		}
		else if($page_name == 'edit_roles_model')
		{
			$result_roles = $this->Crud_model->get_availabel_options();

			$data['user_list'] = $this->Crud_model->fetch_record('mp_users', 'status');

			$data['result_roles'] = $result_roles;

			$data['user_id'] = $param;

			// model name available in admin models folder
			$this->load->view('admin_models/edit_models/edit_multiple_roles_model.php', $data);
		}
	}
	// Multiple_roles/Delete
	public function delete($args)

	{
		// DEFINES LOAD CRUDS_MODEL FORM MODELS FOLDERS
		$this->load->model('Crud_model');
		$result = $this->Crud_model->delete_record('mp_multipleroles', $args);
		if ($result == 1)
		{
			$array_msg = array(
				'msg' => '<i style="color:#fff" class="fa fa-trash-o" aria-hidden="TRUE"></i> Privilege removed Successfully',
				'alert' => 'info',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		else
		{
			$array_msg = array(
				'msg' => '<i style="color:#c00" class="fa fa-exclamation-triangle" aria-hidden="TRUE"></i> Error Privilege record cannot be romved',
				'alert' => 'danger',
			);
			$this->session->set_flashdata('status', $array_msg);
		}
		redirect('multiple_roles');
	}
}
