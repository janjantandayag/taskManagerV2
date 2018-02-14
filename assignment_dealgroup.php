<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/entity_functions.php');
?>

<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">          
				<div style="margin-top:20px;padding-bottom: 25px;margin-bottom:25px;text-align: right;border-bottom: 1px solid #d2d2d2">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm assigned_entity_deal_group_btn"> ASSIGN ENTITY-DEAL GROUP </a>
				</div>
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
			         <thead>
		                <tr>
		                  	<th  style="cursor: pointer;">Entity</th>
		                  	<th  style="cursor: pointer;">Deal Groups Count</th>
		                  	<th  style="cursor: pointer;">Deal Groups Assigned</th>
		                </tr>
	              	</thead>
		          	<tbody>	
		          		<?php
		          			$entities_query = getEntities();
		          			while($entity = mysqli_fetch_assoc($entities_query)) : 
          					$dealgroups = getAssignedDealGroups($entity['entity_id']);
		          		?>
		          		<tr>
		          			<td>
		          				<a class="hoverAnimateText" href="entities_view.php?entity_id=<?= $entity['entity_id'] ?>" target="_blank" > <?= ucwords($entity['entity_legal_name']) ?> </a> 
		          			</td>
		          			<td>
		          				<?= $dealgroups->num_rows ?>
		          			</td>

		          			<td>
		          				<?php
		          					if($dealgroups->num_rows > 0 ) {
		          					while($deal_group = mysqli_fetch_assoc($dealgroups)){
		          				?>
		          				<a class="hoverAnimateText" target="_blank" href="dealgroups_view.php?dealgroup_id=<?= $deal_group['dealgroup_id']; ?>"> <?= ucwords($deal_group['group_name'] . ' ( ' . $deal_group['code_name'] .' )') ?> </a> <span style="font-weight: bold;color:#000000"> | </span>
		          				<?php }  } else {
		          					echo '<span style="color:#f44336;font-weight:bold">NOT SET</span>';
		          				} 
		          				?>
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
       
