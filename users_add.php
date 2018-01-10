<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');
?>



<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-xs-12">			
			<br />
			<form class="form-horizontal form-label-left input_mask">
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
							<input type="text" class="form-control has-feedback-left" id="inputSuccess1" placeholder="First Name">
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
							<input type="text" class="form-control has-feedback-left" id="inputSuccess2" placeholder="Last Name">
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
							<input type="text" class="form-control has-feedback-left" id="inputSuccess32" placeholder="Email">
							<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
							<input type="text" class="form-control has-feedback-left" id="inputSuccess4" placeholder="Office Phone">
							<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
							<input type="text" class="form-control has-feedback-left" id="inputSuccess5" placeholder="Cellphone">
							<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
							<input type="password" class="form-control has-feedback-left" id="inputSuccess6" placeholder="Password">
							<span class="fa fa-key form-control-feedback left" aria-hidden="true"></span>
						</div>
					</div>
				</div>		
				<div class="x_panel">
					<div class="x_title">
						<h2>Positions</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content" id="position_container">
						<div class="row initialInput" style="border-bottom:1px solid #f5eded;padding-top:5px">
							<div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
								<label>Position</label>
								<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
								<select class="form-control has-feedback-left" >
									<option value=""></option>
									<option value="AK">Alaska</option>
									<option value="AK">Alaska</option>
									<option value="AK">Alaska</option>
								</select>
							</div>		
							<div class="col-md-4 col-sm-4 col-xs-12">
								<label>Start Date</label>
								<div class="form-group">
			                        <div class="input-group date" id="myDatepicker1">
			                            <input type="text" class="form-control">
			                            <span class="input-group-addon">
			                               <span class="glyphicon glyphicon-calendar"></span>
			                            </span>
			                        </div>
			                    </div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<label>End Date</label>								
								<div class="form-group">
			                        <div class="input-group date" id="myDatepicker2">
			                            <input type="text" class="form-control">
			                            <span class="input-group-addon">
			                               <span class="glyphicon glyphicon-calendar"></span>
			                            </span>
			                        </div>
			                    </div>
							</div>	 
							<div class="col-xs-12">							
                				<button class="btn btn-danger btn-xs" onclick="removeRow(this)" type="button" style="display: none"><span class="fa fa-minus"></span></button>        	
							</div>                   									
                    	</div>                           	             	     
					</div>
                	<button class="btn btn-primary btn-xs addPositionButton" type="button" ><span class="fa fa-plus"></span> Add Position</button>        	
				</div>			
			</form>
		</div>
	</div>
</div>


</div>
</div>
</div>
<?php

  include('includes/footer.php');



} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
