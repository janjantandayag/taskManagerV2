<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');

  $dealgroup_id = $_GET['dealgroup_id'];
  $query = getDealGroupDetails($dealgroup_id,'view');
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
      <?php 
      if(mysqli_num_rows($query) > 0){
        $deal_group =  mysqli_fetch_assoc($query);
      ?>
        <div class="row">  
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><?= ucwords($deal_group['group_name']) . ' ( ' . ucwords($deal_group['code_name']) . ' )' ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                  <div class="profile_img">
                    <div id="crop-avatar">
                    </div>
                  </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class=""><a href="#tab_content1" role="tab" id="deal-overview-tab" data-toggle="tab" aria-expanded="true"> Deal Overview</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="investment-tab" data-toggle="tab" aria-expanded="false"> Investment</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content3" role="tab" id="tasks-tab" data-toggle="tab" aria-expanded="false"> Tasks</a>
                          </li>
                           <li role="presentation" class=""><a href="#tab_content4" role="tab" id="people-tab" data-toggle="tab" aria-expanded="false"> People</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content5" role="tab" id="valuation-tab" data-toggle="tab" aria-expanded="false"> Valuation</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content6" role="tab" id="entity-overview-tab" data-toggle="tab" aria-expanded="false"> Entity Overview</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content7" role="tab" id="portfolio-review-tab" data-toggle="tab" aria-expanded="false"> Portfolio Review</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="deal-overview-tab">
                            DEAL OVERVIEW
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="investment-tab">
                            INVESTMENT 
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="tasks-tab">
                            TASKS 
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="people-tab">
                            PEOPLE 
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="valuation-tab">
                            VALUATION 
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="entity-overview-tab">
                            ENTITY OVERVIEW 
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content7" aria-labelledby="portfolio-review-tab">
                            PORTFOLIO REVIEW 
                          </div>
                        </div>
                  </div>
                  <div style="border-top: 1px solid #d2d2d2;padding-top:20px;margin-top: 20px;text-align: right">
                    <a href="dealgroups_update.php?dealgroup_id=<?=$deal_group['dealgroup_id']?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteDealGroup(<?= $deal_group['dealgroup_id'] ?>, '<?= ucwords($deal_group['group_name']) . " (" . ucwords($deal_group['code_name']) .")" ?>');"><i class="fa fa-trash-o" ></i> Delete </a>
                  </div>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } else { ?>
        <div class="row">
          <h5 style="text-align: center">
            <strong style="color: #cc1b1b">ERROR:</strong> Sorry, deal group is either deleted or does not exists in the system. 
          </h5>
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
       
