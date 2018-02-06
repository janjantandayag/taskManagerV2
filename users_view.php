<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
	include('includes/header.php');
	include('includes/sidebar.php');
	include('includes/top_navigation.php');
	include('database/user_functions.php');

	$query = getUserDetails($_GET['user_id']);


?>

<div class="right_col" role="main">
	<div class="">        
      <div class="row">
    	<?php 
			if(mysqli_num_rows($query) > 0){
				$user_details =  mysqli_fetch_assoc($query);
			?>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>View Profile - <small><?= strtoupper($user_details['first_name']) . ' ' . strtoupper($user_details['last_name']) ?></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                  <div class="profile_img">
                    <div id="crop-avatar">
                      <img class="img-responsive avatar-view" width="100%" src="database/attachment/images/profile/<?= $user_details['profile_image'] ?>" alt="Avatar" title="Change the avatar">
                    </div>
                  </div>
                  <h3><?=  ucfirst($user_details['first_name']) . ' ' . ucfirst($user_details['last_name']) ?></h3>

                  <ul class="list-unstyled user_data">
                    <li><i class="fa fa-envelope user-profile-icon"></i> <?= $user_details['email']; ?></li>

                    <li><i class="fa fa-phone user-profile-icon"></i> <?= $user_details['office_phone']; ?></li>

                  	<li><i class="fa fa-phone user-profile-icon"></i> <?= $user_details['cell_phone']; ?></li>

                    <li class="m-top-xs">                    	
  						        <?php if($user_details['status'] == 'ACTIVE') : ?>
                      <span style="padding: 3px;border-radius: 2px;background: #26B99A; color: #fff;font-weight: bold;font-size: 12px">ACTIVE</span>
                      <?php else : ?>                   
                      <span style="padding: 3px;border-radius: 2px;background: #b3b3b3; color: #fff;font-weight: bold;font-size: 12px">DEACTIVATED</span>
                      <?php endif; ?>
                    </li>
                  </ul>
                  <hr>
                  <a href="users_update.php?id=<?= $user_details['user_id'] ?>&&form=update" class="btn btn-primary btn-sm"><i class="fa fa-edit m-right-xs"></i> Edit Profile</a>
                  <br />                  
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="profile_title">
                    <div class="col-md-6">
                      <h2>INSERT ADDITIONAL CONTENT HERE</h2>
                    </div>
                  </div>
                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Position History</a></li>
                      <li role="presentation"><a href="#tab_content2" id="vacation-tab" role="tab" data-toggle="tab" aria-expanded="true">Vacation</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <?php
                        $position_query = getPositionHistory($_GET['user_id']);
                        if(mysqli_num_rows($position_query) > 0 ) {
                        ?>
                      	<table class="data table table-striped no-margin tablesorter" id="positionTable" >
                          <thead>
                            <tr>
                              <th  style="cursor: pointer;">#</th>
                              <th  style="cursor: pointer;">Position Name</th>
                              <th  style="cursor: pointer;">Position Description</th>
                              <th  style="cursor: pointer;">Entity</th>
                              <th  style="cursor: pointer;">Start Date</th>
                              <th  style="cursor: pointer;">End Date</th>
                              <th  style="cursor: pointer;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            while($position = mysqli_fetch_assoc($position_query)){
                            $i++;
                            ?>                            
                            <tr>
                              <td><?= $i ?></td>
                              <td><?= $position['position_title'] ?></td>
                              <td><?= $position['position_description'] ?></td>
                              <td><?= $position['group_name'] . ' ('. ucwords($position['entity_legal_name']) .')' ?></td>
                              <td><?= date('F d, Y | D', strtotime($position['start_date']))  ?></td>
                               <td><?= ($position['status'] == 'ACTIVE' && empty(strtotime($position['end_date']))) ? 
                                '<b>____</b>' : 
                                date('F d, Y | D', strtotime($position['end_date'])) ?>
                              </td>
                              <td>                                
                                <button style="width:100%;cursor: none" class="btn btn-<?= $position['status']=='ACTIVE' ? 'success' : 'default' ?> btn-xs" type="button"><?= $position['status'] ?></button>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                        <?php
                        } else { ?>
                          <h4 style="text-align: center;padding:20px">No Records</h4>
                        <?php }  ?>
                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="vacation-tab">
                        <h2>INSERT VACATION LOGS HERE</h2>
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


</div>
</div>
</div>
<?php
  	include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
