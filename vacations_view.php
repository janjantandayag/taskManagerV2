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

  $vacation_id = $_GET['vacation_id'];
  $vacation = getVacationDetails($vacation_id);
?>

<div class="right_col" role="main">
      <?php
        if(isset($_SESSION['action_success'])) {
          $alert['class_type'] = 'success';
          $alert['text'] = 'SUCCESS';
          $message = $_SESSION['action_success'];
      ?>
      <div class="row">
        <div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <strong><?= $alert['text'] ?></strong> <?= $message; ?>
            </div>  
      </div>
      <?php } unset($_SESSION['action_success']); ?>
 <div class="row">
        <?php if(count($vacation) > 0) { ?> 
      <div class="">        
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-md-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><?= strtoupper($vacation['title']); ?> </h2>
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
                        <li>
                          <span class="name"> Start Date </span>
                          <span class="value text-<?= $vacation_status['class']?>"> <?= date('F d, Y | l',strtotime($vacation['start_date'])); ?> </span>
                        </li>
                        <li>
                          <span class="name"> End Date </span>
                          <span class="value text-<?= $vacation_status['class']?>"> <?= date('F d, Y | l',strtotime($vacation['end_date'])); ?> </span>
                        </li>
                        <li>
                          <span class="name"> Status </span>
                          <span class="value text-<?= $vacation_status['class']?>"> <?= $vacation['status']; ?> </span>
                        </li>
                      </ul>
                      <br />
                  <div id="mainb">               
                    <h4 style="font-weight: bold;">Title</h4>    
                    <p>
                      <?= ucwords($vacation['title']); ?>
                    </p>
  
                    <hr/>
                    <h4 style="font-weight: bold;">Description</h4>    
                    <p>
                      <?= ucwords($vacation['description']); ?>
                    </p>
                  </div>
                </div>
                <!-- start project-detail sidebar -->
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <section class="panel">
                    <div class="x_title">
                      <h2>VACATION DETAILS</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                      <div class="project_detail">
                        <div>
                          <p class="title">Requested By</p>
                          <p>
                            <a href="users_view.php?user_id=<?=$vacation['requester_id'];?>" target="_blank">             
                                <?= ucwords(mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['first_name'] . ' ' . mysqli_fetch_assoc(getUserDetails($vacation['requester_id']))['last_name']); ?>
                            </a>
                          </p>
                        </div>
                        
                        <div class="mtop20">
                        <?php if($vacation['status'] !== 'PENDING') : ?>
                          <p class="title"><?= ucfirst(strtolower($vacation['status'])) ?> By</p>
                          <p>
                            <a href="users_view.php?user_id=<?=$vacation['approved_by'];?>" target="_blank">             
                                <?= ucwords(mysqli_fetch_assoc(getUserDetails($vacation['approved_by']))['first_name'] . ' ' . mysqli_fetch_assoc(getUserDetails($vacation['approved_by']))['last_name']); ?>
                            </a>
                          </p>
                        <?php endif; ?>
                        </div>

                        <div class="mtop20">
                        <?php if($vacation['status'] !== 'PENDING') : ?>
                          <p class="title">Date <?= ucfirst(strtolower($vacation['status'])) ?></p>
                          <p>
                            <?= date('F d, Y | l',strtotime($vacation['approved_date'])); ?>
                          </p>
                        <?php endif; ?>
                        </div>  
                        

                       
                      <br />
                    </div>

                    <div class="mtop20">
                        <?php if($vacation['status'] === 'PENDING')  : ?>
                        <a href="vacations_update.php?vacation_id=<?= $vacation['vacation_id'] ?>" class="btn btn-xs btn-primary">Edit Vacation</a>                        
                        <a href="#" class="btn btn-danger btn-xs" onclick="deleteVacation(<?= $vacation['vacation_id'] ?>,'<?= $vacation['title']?>');"><i class="fa fa-trash-o"></i> Delete </a>
                        <?php endif; ?>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
  </div>
        <?php } else { ?>
     <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top:150px;font-size:20px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>ERROR:</strong> Sorry, vacation does not exist in the system. 
          </div>
    <?php } ?>
  </div>
</div>
   

<?php
  include('includes/footer.php');

} else {
  $_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
  header("Location: index.php");
}
       
