<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title> 6Flicks | Log in </title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">

	<!-- AdminLTE Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<?php
	$banner_name = $this->db->get_where('mp_langingpage', array('id' =>1))->result_array()[0]['banner'];
 ?>
<body class="hold-transition login-page " style="background-image:url('<?php echo base_url('uploads/systemimgs/'.$banner_name); ?>'); background-size:cover; position: relative;">
	<div class="login-box-adjust">
		<div class="login-box">
			<div class="login-logo">
				<a href="<?php echo base_url('login'); ?>"> <b>6Flicks Smart Accounting </b></a>
			</div>
			<div class="login-box-body">
				<p class="login-box-msg">Please enter your Email & Password</p>

				<?php
				  $attributes = array('id'=>'Customer_form','method'=>'post','class'=>'form-horizontal');
			  ?>

				<?php echo form_open('login/authentication',$attributes); ?>

					<div class="form-group has-feedback">
						<?php

							$data = array('class'=>'form-control input-lg','id'=>'user_email','type'=>'email','name'=>'user_email','value'=>'','placeholder'=>'e.g admin@6flicks.com','reqiured'=>'','AUTOCOMPLETE'=>'OFF');
							echo form_input($data);

						?>

					</div>
					<div class="form-group has-feedback">
						<?php

						  $data = array('class'=>'form-control input-lg','id'=>'user_password','type'=>'password','name'=>'user_password','value'=>'','placeholder'=>'e.g SW434DZSWA$','reqiured'=>'','AUTOCOMPLETE'=>'OFF');
						  echo form_input($data);

						?>

					</div>

					<div class="row">
						<div class="col-xs-12">
							<?php
								  $data = array('class'=>'btn btn-success login-btn-color  btn-block btn-flat btn-lg','name'=>'btn_submit_signin','value'=>$page_title_model_button_Signin);

								  echo form_submit($data);
							   ?>
						</div>
					</div>
					<?php echo form_close(); ?>

						<!-- ForgetModel  -->
						<?php $this->load->view('admin_models/forget_model'); ?>
							<!-- ForgetModel ends-->

							<br />
							<a href="#" data-toggle="modal" data-target="#ForgetModel" class="pull-left link-set">Forget Password</a>
							<br />

					<div class="row"><hr />
						<div class="col-xs-12">
						   <table class="table table-bordered">
								<tr>
									<td>Email</td><td>admin@6flicks.com</td>
								</tr>
								<tr>
									<td>Password</td><td>12345</td>
								</tr>
							</table>
						</div>
					</div>
			</div>
		</div>
	</div>

	<!-- jQuery 2.2.3 -->
	<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>

	<!-- Validate form js -->
	<script src="<?php echo base_url(); ?>assets/jquery/jquery.validate.js"></script>

	<script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>
	<!-- Bootstrap Gowl -->
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-growl/jquery.bootstrap-growl.js"></script>

	<!-- page script -->
			<script>
				function alertFunc(status, message) {

					$.bootstrapGrowl(message, {
						ele: 'body', // which element to append to
						type: status, // (NULL, 'info', 'error', 'success')
						offset: {
							from: 'top',
							amount: 20
						}, // 'top', or 'bottom'
						align: 'right', // ('left', 'right', or 'center')
						width: 250, // (integer, or 'auto')
						delay: 4000,
						allow_dismiss: true,
						stackup_spacing: 10 // spacing between consecutively stacked growls.
					});

				};
			</script>

	<?php

		 if($this->session->flashdata('status') == "")
		 {

		 }
		 else
		 {

			$message = $this->session->flashdata('status');
			echo "<script>alertFunc('".$message['alert']."','".$message['msg']."')</script>";

		 }

		?>
</body>

</html>