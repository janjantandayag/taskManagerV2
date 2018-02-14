<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
?>

<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">          
				<div style="margin-top:20px;padding-bottom: 25px;margin-bottom:25px;text-align: right;border-bottom: 1px solid #d2d2d2">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm assigned_dealgroup_document"> ASSIGN DEAL GROUP DOCUMENTS </a>
				</div>
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
			         <thead>
		                <tr>
		                  	<th  style="cursor: pointer;">Deal Group</th>
		                  	<th  style="cursor: pointer;">Document Count</th>
		                  	<th  style="cursor: pointer;">Documents</th>
		                </tr>
	              	</thead>
		          	<tbody>	
		          		<?php
		          			$dealgroups_query = getDealGroups();
		          			while($dealgroup = mysqli_fetch_assoc($dealgroups_query)) : 
          					$documents = getAssignedDocuments($dealgroup['dealgroup_id']);
		          		?>
		          		<tr>
		          			<td>
		          				<a class="hoverAnimateText" href="dealgroups_view.php?dealgroup_id=<?= $dealgroup['dealgroup_id'] ?>" target="_blank" > <?= ucwords($dealgroup['group_name'] . ' ( ' . $dealgroup['code_name'] . ' )') ?> </a> 
		          			</td>
		          			<td>
		          				<?= $documents->num_rows ?>
		          			</td>

		          			<td>
		          				<?php
		          					if($documents->num_rows > 0 ) {
		          					while($document = mysqli_fetch_assoc($documents)){
		          				?>
		          				<a class="hoverAnimateText" target="_blank" href="http://<?= $document['document_link'] ?>"> <?= ucwords($document['document_name']); ?> </a><br/>
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