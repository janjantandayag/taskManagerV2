<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/document_functions.php');

  $document_id = $_GET['document_id'];
  $document = getDocumentDetails($document_id);
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
    	<?php if($document) : ?>      
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2><?= ucwords($document['document_name']) ?></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="col-md-2 col-sm-2 col-xs-12 profile_left">
                  <ul class="list-unstyled user_data">
                    <li style="padding:5px 0;border-bottom: 1px solid #d3d5d8">
                      <i class="fa fa-calendar user-profile-icon"></i> <h5 style="display: inline-block;">Obscelence Date</h5>
                      <p style="margin-left:15px;"><?= strtotime($document['obscelence_date']) ? date('F d, Y | l', strtotime($document['obscelence_date'])) : 'NOT SET' ?></p>
                    </li>    
                    <li style="padding:5px 0;border-bottom: 1px solid #d3d5d8">
                      <i class="fa fa-calendar user-profile-icon"></i> <h5 style="display: inline-block;">Effective Date</h5>
                      <p style="margin-left:15px;"><?= strtotime($document['effective_date']) ? date('F d, Y | l', strtotime($document['effective_date'])) : 'NOT SET' ?></p>
                    </li>    
                     <li style="padding:5px 0;border-bottom: 1px solid #d3d5d8">
                      <i class="fa fa-calendar user-profile-icon"></i> <h5 style="display: inline-block;">Uploaded Date</h5>
                      <p style="margin-left:15px;"><?= strtotime($document['date_created']) ? date('F d, Y | l', strtotime($document['date_created'])) : 'NOT SET' ?></p>
                    </li>                             
                  </ul>
              </div>                
              <div class="col-md-10 col-sm-10 col-xs-12">
                <div class="profile_title">
                  <div class="col-md-6">
                    <h4>DESCRIPTION</h4>
                    <p><?= ucfirst(trim($document['document_description'])) ?></p>
                  </div>
                </div>
              	<div class="" role="tabpanel" data-example-id="togglable-tabs" style="margin-top:50px">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class=""><a href="#tab_content1" role="tab" id="people-tab" data-toggle="tab" aria-expanded="false"> People</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content2" role="tab" id="entities-tab" data-toggle="tab" aria-expanded="false"> Entities</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content3" role="tab" id="tasks-tab" data-toggle="tab" aria-expanded="false"> Tasks</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content4" role="tab" id="dealgroups-tab" data-toggle="tab" aria-expanded="false"> Deal Groups</a>
                      </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="people-tab">
                        people
                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="entities-tab">
                        entities
                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="tasks-tab">
                        tasks
                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="dealgroups-tab">
                        dealgrouops
                      </div>
                    </div>
                </div>
                <div style="border-top: 1px solid #d2d2d2;padding-top:20px;margin-top: 20px;text-align: right">
                  <a href="entities_update.php?entity_id=<?=$entity['entity_id']?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> Edit </a>
                  <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteEntity(<?= $entity['entity_id'] ?>, '<?= ucwords($entity['entity_legal_name']) . " (" . ucwords($entity['entity_nickname']) .")" ?>');"><i class="fa fa-trash-o" ></i> Delete </a>
                  <a href="//<?= $document['document_link'] ?>" class="btn btn-default btn-sm" ><i class="fa fa-send" ></i> View Document </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php else : ?>
      <div class="row">
        <h5 style="text-align: center">
          <strong style="color: #cc1b1b">ERROR:</strong> Sorry, document is either deleted or does not exists in the system. 
        </h5>
      </div>
		  <?php endif; ?>
	</div>
</div>   

<?php
  include('includes/footer.php');

} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
