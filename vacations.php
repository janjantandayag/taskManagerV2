<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/vacation_functions.php');
  include('database/user_functions.php');
?>

  <div class="right_col" role="main">
	<div class="row" style="margin-top:70px">
		<div class="page-title">
          <div class="title_left">
            <h3>All Vacation Requests</h3>
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
<!-- 	                  <th  style="cursor: pointer;">Start Date</th>
	                  <th  style="cursor: pointer;">End Date</th> -->
	                  <th  style="cursor: pointer;">Confirmed Date</th>
	                  <th  style="cursor: pointer;">Confirmed By</th>
	                  <th  style="cursor: pointer;">Status</th>
	                </tr>
	              </thead>
		          <tbody>
		          	<?php
		          		$vacations_query = getVacationRequests();
		          		while($vacation = mysqli_fetch_assoc($vacations_query)) {
			          		$vacation_status =[];
	                        if($vacation['status'] === 'PENDING'){
	                          $vacation_status['text'] = 'PENDING';
	                          $vacation_status['class'] = 'warning';
	                        } elseif($vacation['status'] === 'REJECTED'){
	                          $vacation_status['text'] = 'REJECTED';
	                          $vacation_status['class'] = 'danger';
	                        } else {
	                          $vacation_status['text'] = 'APPROVED';
	                          $vacation_status['class'] = 'success';                      
	                        }
		          	?>
		          	<tr>
		          		<td>
		          			<a href="vacations_view.php?vacation_id=<?=$vacation['vacation_id']; ?>" target="_blank" class="hoverAnimateText" >
		          				<?= ucwords($vacation['title']) ?>
		          			</a>
		          		</td>
		          		<td><?= ucfirst($vacation['description']) ?></td>
		          		<td>
						<a href="users_view.php?user_id=<?=$vacation['requester_id'];?>" target="_blank" class="hoverAnimateText" >							
		          			<?= ucwords(mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['first_name'] . ' ' . mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['last_name']); ?>
						</a>
						</td>
		          		<td>
							<?= strtotime($vacation['submitted_date']) ? date('F d, Y | l @ g:i A', strtotime($vacation['submitted_date'])) : 'NOT SET' ?>
		          		</td>
		          		<!-- <td>		          			
							<?= strtotime($vacation['start_date']) ? date('F d, Y | l', strtotime($vacation['start_date'])) : 'NOT SET' ?>
		          		</td>
		          		<td>
							<?= strtotime($vacation['end_date']) ? date('F d, Y | l', strtotime($vacation['end_date'])) : 'NOT SET' ?>
						</td> -->
		          		<td>		          			
							<?= strtotime($vacation['approved_date']) ? date('F d, Y | l @ g:i A', strtotime($vacation['approved_date'])) : 'NOT SET' ?>
		          		</td>
		          		<td>
		          			<a href="users_view.php?user_id=<?=$vacation['approved_by'];?>" target="_blank" class="hoverAnimateText">							
		          			<?= $vacation['approved_by'] ? ucwords(mysqli_fetch_assoc(getUserDetails($vacation['approved_by']))['first_name'] . ' ' . mysqli_fetch_assoc(getUserDetails($vacation['approved_by']))['last_name']) : 'NOT SET' ?>
							</a>
		          		</td>
		          		<td>
	                		<span class="label label-<?=$vacation_status['class']?>" style="width: 100% !important;display: block"><?= $vacation_status['text'] ?></span>
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
       
