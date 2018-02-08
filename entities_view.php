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
                      <h2>INSERT ADDITIONAL CONTENT HERE</h2>
                    </div>
                  </div>
                	<div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class=""><a href="#tab_content1" role="tab" id="deal-group-tab" data-toggle="tab" aria-expanded="false"> Deal Groups Assigned</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="position-tab" data-toggle="tab" aria-expanded="false"> Users and Positions Assigned</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="deal-group-tab">
                          	display dealgroups associated with this entity
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="position-tab">
                          	display positions 
                          </div>
                        </div>
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
       
