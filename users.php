<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');
?>



<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="row">
				 		<div class="col-md-12 col-sm-12 col-xs-12 text-center"></div>

                  		<div class="clearfix"></div>
						<?php
							$query = getUsers();
							while($user = mysqli_fetch_assoc($query)) {
						?>
                      	<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
								<div class="col-sm-12">
									<div class="left col-xs-7">
										<h4><?= strtoupper($user['first_name']) . ' ' . strtoupper($user['last_name']) ?></h4>
										<h5><strong>Position<?= countPosition($user['user_id']) ?>: </strong> <br/><br/>
										<?= getUserPositions($user['user_id']) ?> </h5>
									</div>
									<div class="right col-xs-5 text-center">
										<img src="database/attachment/images/profile/<?= $user['profile_image'] ?>" alt="" class="img-circle img-responsive" />
									</div>
								</div>
								<div class="col-xs-12 bottom">
									<div class="emphasis" style="padding-left:15px">									
										<button type="button" class="btn btn-<?= $user['status'] === 'ACTIVE' ? 'success' : 'default' ?> btn-xs"><?= $user['status'] === 'ACTIVE' ? 'ACTIVE' : 'DEACTIVATED' ?></button>
										<a href="users_view.php?user_id=<?=$user['user_id']?>" class="btn btn-primary btn-xs"><i class="fa fa-user"> </i> View Profile</a>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
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
       
