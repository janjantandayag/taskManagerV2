<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/task_functions.php');
?>

  <div class="right_col" role="main">
	<div class="row" style="margin-top:70px">
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">          
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
		         <thead>
	                <tr>
	                  <th  style="cursor: pointer;">Task</th>
	                  <th  style="cursor: pointer;">Credit</th>
	                  <th  style="cursor: pointer;">Document Reference</th>
	                  <th  style="cursor: pointer;">Due Date</th>
	                  <th  style="cursor: pointer;">Status</th>
	                  <th  style="cursor: pointer;">Action</th>
	                </tr>
	              </thead>
		          <tbody>		          	
		              <?php
		              $task_query = getAllTasks();
		              while($task = mysqli_fetch_assoc($task_query)) {
		              	$task_bg = $task['status'] === 'IN PROGRESS' ? 'danger' : 'success';
		              ?>
	                 <tr>
	                	<td><a class="dashboard_table_link_hover" href="task_view.php?task_id=<?=$task['task_id'];?>"><?= $task['title'] ?></a></td>
	                	<td><a class="dashboard_table_link_hover" href="dealgroup_view.php?dealgroup_id=<?= $task['dealgroup_id'] ?>"><?= $task['group_name'] ?></a></td>
	                	<td><a class="dashboard_table_link_hover" href="<?= $task['document_link'] ?>" target="_blank"><?= $task['document_name'] ?></a></td>
	                	<td><?= date('F d, Y  l', strtotime($task['due_date'])) ?></td>
	                	<td>
	                		<span class="label label-<?=$task_bg?>" style="width: 100% !important;display: block"><?= $task['status'] ?></span>
	                	</td>
	                	<td>
                            <a href="task_view.php?task_id=<?=$task['task_id'];?>" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
                            <a href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
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
       
