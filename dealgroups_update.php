<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
  include('database/user_functions.php');
  include('database/entity_functions.php');

  $isUpdate = true;
  $dealgroup_id = $_GET['dealgroup_id'];
  $dealgroup = getDealGroupDetails($dealgroup_id);
?>

<div class="right_col" role="main">
  <?php
  if(isset($_SESSION['action_error']) || isset($_SESSION['action_success'])) {
    $alert['class_type'] = isset($_SESSION['action_error']) ? 'danger':'success';
    $alert['text'] = isset($_SESSION['action_error']) ? 'ERROR!' : 'SUCCESS!';
    $message = isset($_SESSION['action_error']) ? $_SESSION['action_error'] : $_SESSION['action_success'];
  ?>
  <div class="row">
    <div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong><?= $alert['text'] ?></strong> <?= $message; ?>
        </div>  
  </div>
  <?php } unset($_SESSION['action_success'],$_SESSION['action_error']); ?>
  <form class="form-horizontal form-label-left input_mask" method="POST" action="database/dealgroup_functions.php" />
    <?php include('includes/dealgroups/dealgroup_form.php') ?>
    <?php include('includes/dealgroups/dealgroup_form_entity.php') ?>

    <div class="row">
      <div class="col-xs-12" style="text-align: center">              
        <input type="hidden" name="dealgroup_id" value="<?= $dealgroup['dealgroup_id'] ?>" />
        <button class="btn btn-primary btn-xs" name="update_dealgroup" type="submit"> Update Entity </button> 
      </div>              
    </div>
  </form>
</div>

<?php
  include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
