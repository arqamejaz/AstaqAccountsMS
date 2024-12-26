<?php
	$user_name = $this->session->userdata('user_id');
	//SIDEMENU CONFIGURATION FROM HELPER CLASS VISIT HELPER CLASS FOR  MORE details
	$SideMenu_records = fetch_users_access_control_menu($user_name['id']);
?>
<header class="main-header">
	<div class="stellarnav">
		<?php
		if($SideMenu_records != NULL)
		{
		?>
		<ul>
			<?php
			foreach ($SideMenu_records as  $obj_SideMenu_records)
			{
			?>
				<li>
					<a href="">
						<i class="<?php echo $obj_SideMenu_records['icon']; ?> icon-settings" aria-hidden="true" >
						</i>
						<span class="text-center link-settting" >
							<?php echo $obj_SideMenu_records['name']; ?>
						</span>
					</a>
					<ul>
						<?php
						//DEFINES TO FETCH THE ROLES ASSIGNED TO USER SUB MENU DATA mp_menulist TABLE
						$SideSubMenu_records = fetch_users_access_control_sub_menu($user_name['id'],$obj_SideMenu_records['main_id']);

						if($SideSubMenu_records != NULL)
						{
							foreach ($SideSubMenu_records as $obj_SideSubMenu_records)
							{
						?>
							<li>
								<a href="<?php echo base_url($obj_SideSubMenu_records['link']);?>">
									<i class="sub-icon-settings fa fa-circle-o"></i>
									<?php echo $obj_SideSubMenu_records['title']; ?>
								</a>
							</li>
						<?php
							}
						}
						?>
					</ul>
				</li>
			<?php
			}
			?>
			<li>
				<a class="company-profile-name" href="<?php echo base_url('profile');?>">
					<?php echo img(array('width'=>'30','height'=>'30','class'=>'img-circle img-settings','alt'=>'User Image','src'=>'uploads/users/'.$this->db->get_where('mp_users', array('id' =>$user_name['id']))->result_array()[0]['cus_picture'])); ?>
					<?php echo $user_name['name']; ?>
				</a>
			</li>
		</ul>
		<?php
		}
		?>
	</div>
</header>