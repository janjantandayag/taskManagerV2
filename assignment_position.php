<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
  include('database/entity_functions.php');
  include('database/position_functions.php');
  include('database/user_functions.php');

?>

<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">          
				<div style="margin-top:20px;padding-bottom: 25px;margin-bottom:25px;text-align: right;border-bottom: 1px solid #d2d2d2">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm assignment_position_btn"> ASSIGN POSITION </a>
				</div>
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
			         <thead>
		                <tr>
		                  	<th  style="cursor: pointer;">User</th>
		                  	<th  style="cursor: pointer;">Position Title</th>
		                  	<th  style="cursor: pointer;">Position Description</th>
		                  	<th  style="cursor: pointer;">Entity</th>
		                  	<th  style="cursor: pointer;">Deal Group</th>
		                  	<th  style="cursor: pointer;">Start Date</th>
		                  	<th  style="cursor: pointer;">End Date</th>
		                  	<th  style="cursor: pointer;">Status</th>
		                </tr>
	              	</thead>
		          	<tbody>	
		          		<?php
		          			$positions_query = getRolePositions();
		          			while($position = mysqli_fetch_assoc($positions_query)) : 
		          		?>
		          		<tr>
		          			<td>
								<a href="users_view.php?user_id=<?=$position['user_id']?>" class="hoverAnimateText" target="_blank" >
		          				<?= ucwords(getUserDetailFields($position['user_id'],'first_name')) . ' ' . ucwords(getUserDetailFields($position['user_id'],'last_name')) ?></a>
		          			</td>
		          			<td><?= ucwords($position['position_title']) ?></td>
		          			<td><?= ucfirst(substr($position['position_description'],0,20)) ?></td>
		          			<td>
		          				<a href="entities_view.php?entity_id=<?=$position['entity_id']?>" class="hoverAnimateText" target="_blank" >
		          				<?= ucwords(getEntityDetailFields($position['entity_id'],'entity_legal_name')) ?>		          					
		          				</a>
		          			</td>
		          			<td>
		          				<a href="dealgroups_view.php?dealgroup_id=<?=$position['dealgroup_id']?>" class="hoverAnimateText" target="_blank" >
		          				<?= ucwords(getDealGroupDetailsField($position['dealgroup_id'],'group_name') . ' ( ' . getDealGroupDetailsField($position['dealgroup_id'],'code_name') . ' )') ?>
		          				</a>
		          			</td>
		          			<td><?= date('F d, Y | D', strtotime($position['start_date']))  ?></td>
                               <td><?= ($position['status'] == 'ACTIVE' && empty(strtotime($position['end_date']))) ? 
                                '<b>____</b>' : 
                                date('F d, Y | D', strtotime($position['end_date'])) ?>
                          	</td>
                          	<td>                                
                            	<button style="width:100%;cursor: none" class="btn btn-<?= $position['status']=='ACTIVE' ? 'success' : 'default' ?> btn-xs" type="button"><?= $position['status'] ?></button>
                          	</td>
		          		</tr>	          			          
		          		<?php endwhile; ?>
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
       
