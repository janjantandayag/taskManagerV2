<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/entity_functions.php');

  $entity_id = $_GET['entity_id'];
  $query = getEntityDetails($entity_id,'view');
?>

<div class="right_col" role="main">
	<div class="">        
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
    	<?php 
			if(mysqli_num_rows($query) > 0){
				$entity =  mysqli_fetch_assoc($query);
			?>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><?= ucwords($entity['entity_legal_name']) . ' ( ' . ucwords($entity['entity_nickname']) . ' )' ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                  <div class="profile_img">
                    <div id="crop-avatar">
                    	<div id="map-container" style="width: 100%;height: 250px;">  
                    		<input type="hidden" id="input_address" value="<?= $entity['street_address'] . ' ' . $entity['city'] . ' ' . $entity['state'] . ' ' . $entity['zipcode'] . ' ' . $entity['country']?>" />
							<script>
							    initializeMap();
							</script>      		
                    	</div>
                    </div>
                  </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="profile_title">
                    <div class="col-md-6">
                      <h2>Entity Description Here</h2>
                    </div>
                  </div>
                	<div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class=""><a href="#tab_content1" role="tab" id="general-info-tab" data-toggle="tab" aria-expanded="false"> General Info</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="deal-association-tab" data-toggle="tab" aria-expanded="false"> Deal Associations</a>
                        </li>
                         <li role="presentation" class=""><a href="#tab_content3" role="tab" id="people-tab" data-toggle="tab" aria-expanded="false"> People</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="general-info-tab">
                          GENERAL INFO
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="deal-association">
                        	DEAL ASSOCIATIONS
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="people-tab">
                          PEOPLE
                        </div>
                      </div>
                  </div>
                  <div style="border-top: 1px solid #d2d2d2;padding-top:20px;margin-top: 20px;text-align: right">
                     <a href="entities_update.php?entity_id=<?=$entity['entity_id']?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteEntity(<?= $entity['entity_id'] ?>, '<?= ucwords($entity['entity_legal_name']) . " (" . ucwords($entity['entity_nickname']) .")" ?>');"><i class="fa fa-trash-o" ></i> Delete </a>
                  </div>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } else { ?>
		 <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top:150px;font-size:20px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>ERROR:</strong> Sorry, the user does not exist in the system. 
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
       
