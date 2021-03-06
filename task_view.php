<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/task_functions.php');
  include('database/user_functions.php');

  $task_id = $_GET['task_id'];
  $task = getTaskDetails($task_id);
?>

<div class="right_col" role="main">
	<?php if($task) : ?>
	<div class="">        
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-md-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><?= strtoupper($task['title']); ?> </h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>

              <div class="x_content">
                <div class="col-md-9 col-sm-9 col-xs-12">
				 	        <ul class="stats-overview">
                      <?php
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
                        <li>
                          <span class="name"> Start Date </span>
                          <span class="value text-<?= $task_status['class']?>"> <?= date('F d, Y | l',strtotime($task['start_date'])); ?> </span>
                        </li>
                        <li>
                          <span class="name"> Due Date </span>
                          <span class="value text-<?= $task_status['class']?>"> <?= date('F d, Y | l',strtotime($task['due_date'])); ?> </span>
                        </li>
                        <li>
                          <span class="name"> Status </span>
                          <span class="value text-<?= $task_status['class']?>"> <?= $task['status']; ?> </span>
                        </li>
                      </ul>
                      <br />
                  <div id="mainb">               
                  	<h4 style="font-weight: bold;">Language</h4>   	
          					<p>
          						<?=$task['language'];?>
          					</p>
                  </div>
                  <div id="comment_text" style="margin-top:50px;border-top:1px dotted #dad7d7;padding-top:20px">
                    <form>
                         <div class="form-group">
                           <label style="font-weight: 100 !important">Write a comment ...</label>
                            <textarea id="comment" required class="form-control" rows="3"></textarea>
                        </div>
                        <button type="button" name="create_comment" onclick="postComment(<?=$task_id?>,'task');" class="btn btn-success btn-xs">Comment</button>
                    </form>
                  </div>
                  <div id="comments" style="border-top:1px dotted #dad7d7;margin-top:50px;padding-top:20px">
                    <h4 style="font-weight: bold">Comments </h4>
                    <!-- end of user messages -->
                    <?php
                    	$task_commentsquery = getTaskComments($task_id);
                    ?>
                    <?php if($task_commentsquery->num_rows) : ?>
                    <?php while($task_comment = mysqli_fetch_assoc($task_commentsquery)) : ?>
                    <ul class="messages">
                      <li>
                        <img src="database/attachment/images/profile/<?=$task_comment['profile_image']?>" class="avatar" alt="Avatar">
                        <div class="message_date">
                          <p class="month"><?= date('F d, Y | h:i:s A',strtotime($task_comment['commented_date'])) ?></p>
                        </div>
                        <div class="message_wrapper">
                          <h4 class="heading"><?= ucfirst($task_comment['first_name'])  . ' ' . ucfirst($task_comment['last_name']); ?> </h4>
                          <blockquote class="message"><?= htmlspecialchars_decode($task_comment['comment']) ?></blockquote>
                          <br />
                        </div>
                      </li>
                    </ul>
                	<?php endwhile; ?>
                	<?php else : ?>
                		<h5 style="text-align: center;padding:20px">No Comments</h5>
                	<?php endif; ?> 
                    <!-- end of user messages -->
                  </div>
                </div>
                <!-- start project-detail sidebar -->
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <section class="panel">
                    <div class="x_title">
                      <h2>TASK DETAILS</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                      <div class="project_detail">
						            <p class="title">Note</p>
                        <p><?= ($task['note']) ?  $task['note'] :'<strong style="text-transform:uppercase;color:red;font-size:10px">not set</strong>'; ?></p>

                        <p class="title">Deal Group</p>
                        <p><a href="dealgroup_view.php?deal_group_id=<?=$task['dealgroup_id'];?>"><?= $task['group_name'] . ' <small>(' . $task['code_name'] . ')</small>' ?></a></p>
                        
                        <p class="title">Document</p>
                        <p><a href="https://<?= $task['document_link'] ?>"><?=$task['document_name'] . ' (' .$task['reference'].')';?></a> </p>

                    	<p class="title">Link To Support</p>
                        <p><?= ($task['link_to_support']) ?  '<a href="' . $task['link_to_support'] .'">' .$task['link_to_support'] . '</a>'  :'<strong style="text-transform:uppercase;color:red;font-size:10px">not set</strong>'; ?></p>

                        <p class="title">Type</p>
                        <p><?= ($task['type']) ?  $task['type'] :'<strong style="text-transform:uppercase;color:red;font-size:10px">not set</strong>'; ?></p>

                       <p class="title">Status</p>
                        <p><input type="checkbox" onchange="setToComplete(<?=$task['task_id'];?>,this)" name="checkbox-taskcomplete" <?= $task['status'] === 'FINISHED' ? 'checked' : ''?> value="<?= $task['status'] === 'FINISHED' ? 'FINISHED' : ''?>"></p>
                      </div>

                      <br />
                    </div>

                    <div class="mtop20">
                        <a href="tasks_update.php?task_id=<?= $task['task_id']; ?>" class="btn btn-xs btn-primary">Edit Task</a>                        
                        <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteTask(<?= $task['task_id'] ?>,'<?= $task['title']?>');"><i class="fa fa-trash-o"></i> Delete </a>
                 	</div>
                  </section>
                </div>
                <!-- end project-detail sidebar -->

              </div>
            </div>
          </div>
        </div>
	</div>
	<?php else : ?>
		<div class="row">
			 <h5 style="text-align: center">
          <strong style="color: #cc1b1b">ERROR:</strong> Sorry, task is either deleted or does not exists in the system. 
      </h5>
    </div>
	<?php endif; ?>
</div>

<?php
  include('includes/footer.php');



} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
