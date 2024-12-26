<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign roles</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="box box-danger">
            <div class="box-body">
                <div class="">
                    <?php
        				$attributes = array('id'=>'multiple_roles_form','method'=>'post');
        			?>
                    <?php echo form_open('multiple_roles/add_role',$attributes); ?>
                        <div class="form-group">
                            <?php echo form_label('Select User :'); ?>
                                <select class="form-control" name="user_id">
                                    <option value="0"> Select User </option>
                                        <?php
        								if($user_list != NULL)
                                        { 
        									foreach ($user_list as $obj_user_list)
                                            {
            						    ?>
                                                <option value="<?php echo $obj_user_list->id; ?>">
                                                    <?php echo 'Name : '.$obj_user_list->user_name.' | Email : '.$obj_user_list->user_email; ?>
                                                </option> 
                                        <?php
            								}
                                        }
                                        else
                                        {
                                            echo "No User Record Found";
                                        }
        				                ?>
                                </select>
                            </div>
                        <hr />
                        <?php
                            
        					if($result_roles != NULL)
                            {
                                $ids = 1;
        						foreach ($result_roles as $obj_result_roles)
                                {
                        ?>
                                <div class="set-multi-setting-row row">
                                    <p class="menu-name"><b><i class="fa fa-check-circle" ></i> <?php echo $obj_result_roles[0]['name']; ?></b></p>
                                <?php
                                    $counter = 1;
                                    foreach($obj_result_roles[0]['options'] as $innerOptions)
                                    {
                                ?>              
                                    <div class="assign-roles-row">  
                                            <div class="col-md-5 roles-settings-option">
                                                <?php echo $counter.' : '.$innerOptions->title; ?>
                                            </div>
                                            <div class="col-md-5 roles-settings-option"> 
                                                <b>
                                                   <input type="hidden" name="menulistid[]" value="<?php echo $innerOptions->id; ?>" /> 
                                                   <label class="role-radio" for="<?php echo 'no'.$ids; ?>" ><input type="radio" id="<?php echo 'no'.$ids; ?>" checked="checked" value="no" name="<?php echo 'permission'.$ids; ?>"/> No access </label> 
                                                   <label class="role-radio" for="<?php echo 'read'.$ids; ?>" ><input type="radio" id="<?php echo 'read'.$ids; ?>" value="read"  name="<?php echo 'permission'.$ids; ?>"/> Read </label>
                                                   <label class="role-radio" for="<?php echo 'write'.$ids; ?>" ><input type="radio" id="<?php echo 'write'.$ids; ?>" value="write"  name="<?php echo 'permission'.$ids; ?>"/> Write </label>
                                                </b>
                                            </div>
                                        </div>
                                        <hr> 
                                <?php       
                                        $counter++;   
                                        $ids++;
                                           
                                    }
                                ?>
                                  </div>  
                            <?php  

        						}
        					}
                            else
                            {
        						echo "No Menu Items Found";
        					}
        				    ?>
                    <div class="form-group">
                        <?php
                            $data = array('class'=>'btn btn-info btn-flat btn-lg','type' => 'submit','name'=>'btn_submit_category','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> Update Role');
                            echo form_button($data);
                        ?>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Form Validation -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>