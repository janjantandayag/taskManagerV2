<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/entity_functions.php');
  include('database/dealgroup_functions.php');

  $isUpdate = true;
  $entity_id = $_GET['entity_id'];
  $entity_details = getEntityDetails($entity_id);
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
  <form class="form-horizontal form-label-left input_mask" method="POST" action="database/entity_functions.php" />
    <?php include('includes/entities/entity_form_details.php') ?>
    <?php include('includes/entities/entity_form_dealgroups.php') ?>

    <div class="row">
      <div class="col-xs-12" style="text-align: center">              
        <input type="hidden" name="entity_id" value="<?= $entity_details['entity_id'] ?>" />
        <button class="btn btn-primary btn-xs" name="update_entity" type="submit"> Update Entity </button> 
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
       
