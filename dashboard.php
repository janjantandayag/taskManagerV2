<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');
  include('database/task_functions.php');


	$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
	$dealgroup_id = isset($_GET['deal_group_id']) ? $_GET['deal_group_id'] : '';
	$to = isset($_GET['to']) ? $_GET['to'] : '';
	$from = isset($_GET['from']) ? $_GET['from'] : '';
?>

  <div class="right_col" role="main">
	<div class="row" style="margin-top:70px">
		<div class="col-md-2 col-sm-12 col-xs-12" >
			 <div class="panel-group">
			<?php			
				$query = getAllPMA();
				$i=0;
				while($pma = mysqli_fetch_assoc($query)){					
	        		$deal_group_query = pmaDealGroups($pma['role_position_id']);
			?>
			    <div class="panel panel-<?=$user_id === $pma['user_id'] ? 'success' : ''?>">
			      <div class="panel-heading">
			        <h4 class="panel-title" style="font-size:15px !important">
			        	<?php
			        		$uid = $pma['user_id'];
			        		$link = $deal_group_query->num_rows === 0 ? "javascript:void(0)" : "dashboard.php?user_id=$uid";
			        	?>
			          <a href="<?=$link?>"><?= ucfirst($pma['first_name']) . ' ' . ucfirst($pma['last_name'])?></a> <span data-toggle="collapse" href="#collapse<?= $i ?>" class="label label-<?=$deal_group_query->num_rows === 0 ? 'default' : 'success'?> " style="float:right;font-size:13px;cursor: pointer;"><?=$deal_group_query->num_rows?></span>
			        </h4>
			      </div>
			      <div id="collapse<?= $i ?>" class="panel-collapse collapse <?= $user_id === $pma['user_id'] ? 'in' : '' ?>">			      	
		        	<?php
		        		if($deal_group_query->num_rows > 0 ) {
		        	?>
			        <ul class="list-group">
		        	<?php while($deal_groups = mysqli_fetch_assoc($deal_group_query)) : ?>
			         	<li class="list-group-item" style="<?= $dealgroup_id === $deal_groups['dealgroup_id'] ? 'border-left:10px solid #5cb85c;background:#dff0d8;font-weight: bold;font-size:12px' : '' ?>"><a href="dashboard.php?deal_group_id=<?=$deal_groups['dealgroup_id'];?>&user_id=<?=$pma['user_id'];?>"><?=$deal_groups['group_name'];?></a></li>
		          	<?php endwhile; ?>
			        </ul>
			        <?php } ?>
			      </div>
			    </div>
				<?php $i++; } ?>
			 </div>
		</div>
		<div class="col-md-10 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">          
				<div style="float:right;margin-bottom: 30px">
					<form action="database/task_functions.php" method="POST" class="form-inline">
						<?php include('includes/tasks/task_daterangeform.php'); ?>
 						<input type="hidden" name="user_id" value="<?= $user_id ?>" />
 						<input type="hidden" name="dealgroup_id" value="<?= $dealgroup_id ?>" />
						<input type="hidden" name="location" value="dashboard" />
					</form>
				</div>
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
		         <thead>
	                <tr>
	                  <th  style="cursor: pointer;">Task</th>
	                  <th  style="cursor: pointer;">Credit</th>
	                  <th  style="cursor: pointer;">Document Reference</th>
	                  <th  style="cursor: pointer;">Start Date</th>
	                  <th  style="cursor: pointer;">Due Date</th>
	                  <th  style="cursor: pointer;">Status</th>
	                </tr>
	              </thead>
		          <tbody>		          	
		              <?php
		              $task_query = getAllTasks($user_id,$dealgroup_id,$from,$to);
		              while($task = mysqli_fetch_assoc($task_query)) {		              	
		              	$task_status =[];

		              	if($task['status'] === 'UPCOMING'){
		              		$task_status['text'] = 'UPCOMING';
		              		$task_status['class'] = 'info';
		              	} elseif($task['status'] === 'IN PROGRESS'){
		              		$task_status['text'] = 'IN PROGRESS';
		              		$task_status['class'] = 'warning';
		              	} elseif($task['status'] === 'PAST DUE'){
		              		$task_status['text'] = 'PAST DUE';
		              		$task_status['class'] = 'danger';
		              	} else {
		              		$task_status['text'] = 'FINISHED';
		              		$task_status['class'] = 'success';		              		
		              	}
		              ?>
	                 <tr>
	                	<td><a class="dashboard_table_link_hover" href="task_view.php?task_id=<?=$task['task_id'];?>"><?= $task['title'] ?></a></td>
	                	<td><a class="dashboard_table_link_hover" href="dealgroup_view.php?dealgroup_id=<?= $task['dealgroup_id'] ?>"><?= $task['group_name'] ?></a></td>
	                	<td>
	                		<a class="dashboard_table_link_hover" href="<?= $task['document_link'] ?>" target="_blank" title="<?= $task['document_name'] ?>">
	                			<?= strlen($task['document_name']) === 20 ? $task['document_name'] : substr($task['document_name'], 0,20) . ' ...'  ?>	                			
	                		</a>
	                	</td>
	                	<td><?= date('F d, Y | l', strtotime($task['start_date'])) ?></td>
	                	<td><?= date('F d, Y | l', strtotime($task['due_date'])) ?></td>
	                	<td>
	                		<span class="label label-<?=$task_status['class']?>" style="width: 100% !important;display: block"><?= $task_status['text'] ?></span>
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
       
