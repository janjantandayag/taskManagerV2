<?php
	if($_GET){
		if(isset($_GET['id']) && isset($_GET['form'])){
			$user_id = $_GET['id'];
			$action = $_GET['form'];
			$isUpdate = true;
		}
		
	$user_detail = mysqli_fetch_assoc(getUserDetails($user_id));
	}
?>
<div class="x_panel">
	<div class="x_title">
		<h2>User Details</h2>
		<ul class="nav navbar-right panel_toolbox">
			<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
		</ul>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="text" class="form-control has-feedback-left" id="inputSuccess1" value="<?=  $isUpdate ? ucfirst($user_detail['first_name']) : ''?>" required name="first_name" placeholder="First Name">
			<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="text" class="form-control has-feedback-left" id="inputSuccess2" value="<?=  $isUpdate ? ucfirst($user_detail['last_name']) : ''?>" required name="last_name" placeholder="Last Name">
			<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="email" class="form-control has-feedback-left" id="inputSuccess32" value="<?=  $isUpdate ? $user_detail['email'] : ''?>" required name="email" placeholder="Email">
			<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="text" class="form-control" id="inputSuccess4" style="padding-left:43px" required value="<?=  $isUpdate ? $user_detail['office_phone'] : ''?>" name="office_phone" placeholder="Office Phone" data-inputmask="'mask' : '(999) 999-9999'">
			<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="text" class="form-control" id="inputSuccess5" style="padding-left:43px" required value="<?=  $isUpdate ? $user_detail['cell_phone'] : ''?>" name="cell_phone" placeholder="Cellphone" data-inputmask="'mask' : '(999) 999-9999'">
			<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
			<input type="password" class="form-control has-feedback-left" id="inputSuccess6" required name="password" placeholder="Password">
			<span class="fa fa-key form-control-feedback left" aria-hidden="true"></span>
		</div>
	</div>
</div>		