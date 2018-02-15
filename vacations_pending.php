<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('database/vacation_functions.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');
?>

  <div class="right_col" role="main">
	<div class="row" style="margin-top:70px">
		<div class="page-title">
          <div class="title_left">
            <h3>Pending For Confirmation Vacation Requests</h3>
          </div>
        </div>
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">   
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
		         <thead>
	                <tr>
	                  <th  style="cursor: pointer;">Title</th>
	                  <th  style="cursor: pointer;">Description</th>
	                  <th  style="cursor: pointer;">Requested By</th>
	                  <th  style="cursor: pointer;">Requested Date</th>
	                  <th  style="cursor: pointer;">Start Date</th>
	                  <th  style="cursor: pointer;">End Date</th>
	                  <th  style="cursor: pointer;">Action</th>
	                </tr>
	              </thead>
		          <tbody>
		          	<?php
		          		$vacations_query = getPendingVacationRequests();
		          		while($vacation = mysqli_fetch_assoc($vacations_query)) {
		          	?>
		          	<tr>
		          		<td>
		          			<a href="vacations_view.php?vacation_id=<?=$vacation['vacation_id']; ?>" target="_blank" class="hoverAnimateText" >		          					
		          				<?= ucwords($vacation['title']) ?>
		          			</a>		          			
		          		</td>
		          		<td><?= ucfirst($vacation['description']) ?></td>
		          		<td>
						<a href="users_view.php?user_id=<?=$vacation['requester_id'];?>" target="_blank" class="hoverAnimateText">							
		          			<?= ucwords(mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['first_name'] . ' ' . mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['last_name']); ?>
						</a>
						</td>
		          		<td>
							<?= strtotime($vacation['submitted_date']) ? date('F d, Y | l | g:i A', strtotime($vacation['submitted_date'])) : 'NOT SET' ?>
		          		</td>
		          		<td>		          			
							<?= strtotime($vacation['start_date']) ? date('F d, Y | l', strtotime($vacation['start_date'])) : 'NOT SET' ?>
		          		</td>
		          		<td>
							<?= strtotime($vacation['end_date']) ? date('F d, Y | l', strtotime($vacation['end_date'])) : 'NOT SET' ?>
						</td>
		          		<td>
                            <a href="javascript:void(0);" class="btn btn-primary btn-xs approveVacationRequest" data-title="<?= strtoupper($vacation['title']) ?>" data-id="<?= $vacation['vacation_id'] ?>"><i class="fa fa-check"></i> Approve </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs rejectVacationRequest" data-title="<?= strtoupper($vacation['title']) ?>" data-id="<?= $vacation['vacation_id']; ?>" ><i class="fa fa-remove"></i> Reject </a>
	                	</td>
		          	</tr>
		          	<?php } ?>
		          </tbody>
		        </table>
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
       
