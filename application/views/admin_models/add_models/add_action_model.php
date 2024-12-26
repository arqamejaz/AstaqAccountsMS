<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><i class="fa fa-plus-square" aria-hidden="true"></i>
    	<?php echo $model_title	; ?>
    </h4>
</div>
<div class="modal-body">
	<div class="row">
	    <div class="box box-danger">
	        <div class="box-body">
	            <div class="col-md-12">
              		<?php echo $model_form; ?>

              		
			     </div>	
			</div>				  
		</div>
	</div>
</div>
<?php		
if($form_id != "")
{
?>
<script type="text/javascript">
	// Validate the model form 
	$('<?php echo $form_id; ?>').validate(
		<?php 
			if($validata_data != NULL)
			{
					echo $validata_data;
			}
		
		?>
	);		
</script>
<?php
	}
?>