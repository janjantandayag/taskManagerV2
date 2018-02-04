<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
  include('database/position_functions.php');
  include('database/user_functions.php');
?>

<div class="right_col" role="main">
  <?php if(isset($_SESSION['action_success']) || isset($_SESSION['action_error'])) {
    $class = isset($_SESSION['action_success']) ? 'success' : 'danger';
    $text_bold = isset($_SESSION['action_success']) ? 'SUCCESS' : 'ERROR';
    $message = isset($_SESSION['action_success']) ? $_SESSION['action_success'] : $_SESSION['action_error'];
  ?>
  <div class="row">
    <div class="alert alert-<?= $class ?> ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong><?= $text_bold ?>!</strong> <?= $message ?>
        </div>  
  </div>
  <?php } unset($_SESSION['action_success'],$_SESSION['action_error']); ?>
  <div class="col-md-12 col-sm-6 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><i class="fa fa-users"></i> Assign Positions</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="col-xs-2">
          <!-- required for floating -->
          <!-- Nav tabs -->
          <ul class="nav nav-tabs tabs-left">
            <?php
              $dealgroup_query = getDealGroups();
              $i = 0;
              while($deal_group = mysqli_fetch_assoc($dealgroup_query)) {
            ?>
            <li class="<?= $i===0 ? 'active' : '' ?>"><a href="#<?= str_replace(' ','',strtolower($deal_group['group_name']))  ?>" data-toggle="tab"><?= $deal_group['group_name'] ?></a>
            </li>
            <?php 
              $i++;
            }
            ?>
          </ul>
        </div>

        <div class="col-xs-10">
          <!-- Tab panes -->
          <div class="tab-content">
            <?php 
              $dealgroup_query = getDealGroups();
              $i = 0;
              while($deal_group = mysqli_fetch_assoc($dealgroup_query)) {
            ?>
            <div class="tab-pane <?= $i===0 ? 'active' : '' ?>" id="<?= str_replace(' ','',strtolower($deal_group['group_name']))  ?>">
              <div class="x_content">
                  <!-- start accordion -->
                  <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php
                      $deal_group_name = preg_replace("/[^a-zA-Z]/", "", $deal_group['group_name']);
                      $dealgroup_id = $deal_group['dealgroup_id'];
                      $entity_query = getAssocEntities($dealgroup_id);
                      $i=0;
                      if($entity_query->num_rows != 0) {
                      while($entity = mysqli_fetch_assoc($entity_query)) {
                        $id = $deal_group_name . preg_replace("/[^a-zA-Z]/", "", $entity['entity_legal_name']) . $i;
                        $entity_id = $entity['entity_id'];
                        $id = strtolower($id);
                    ?>
                    <div class="panel">
                      <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#<?= $id ?>" aria-expanded="false" aria-controls="collapseOne">
                        <h4 class="panel-title"><?= strtoupper($entity['entity_legal_name']);  ?> <small><?= '( ' .strtoupper($entity['entity_nickname']).' )' ?></small></h4>
                      </a>
                      <div id="<?= $id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body">                          
                          <div style="text-align: right">
                            <a href="javascript:void(0)" data-value="<?= $id ?>" class="show_form_pos_assign"  >hide form<span class="fa fa-chevron-up"></span></a>
                          </div>
                          <div class="set_position_container container<?=$id?> backgroundWhite displayed" >
                          <form class="form-horizontal form-label-left " action="#" >
                             <!-- <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                              <label>Position Title</label>
                              <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                              <select class="form-control has-feedback-right position_title" name="position_id" required >         
                                <option value=""></option>
                                <?php
                                $position_query = getPositions();
                                while($position = mysqli_fetch_assoc($position_query)) {
                                ?>
                                <option value="<?= $position['position_id']; ?>"><?= $position['position_title'] ?></option>
                                <?php } ?>
                              </select>
                            </div>    
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                              <label>Assigned To</label>
                              <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                              <select class="form-control has-feedback-right position_title" name="user_id" required >         
                                <option value=""></option>
                                <?php
                                $users_query = getActiveUsers();
                                while($user = mysqli_fetch_assoc($users_query)) {
                                ?>
                                <option value="<?= $user['user_id']; ?>"><?= ucwords($user['first_name']) . ' ' . ucwords($user['last_name']) ?></option>
                                <?php } ?>
                              </select>
                            </div>    
                            <div class="col-md-3 col-sm-3 col-xs-12">
                              <label>Start Date</label>
                              <div class="form-group">
                                <div class="input-group date" id="myDatepicker1">
                                    <input type="text" class="form-control"  name="start_date" required>
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                              <label>End Date</label>               
                              <div class="form-group">
                                <div class="input-group date" id="myDatepicker2">
                                    <input type="text" class="form-control" name="end_date">
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                              </div>
                            </div>    -->
                            <div class="col-xs-12" style="text-align: right">             
                              <button class="btn btn-primary btn-xs dealstaff_add_position" type="button" data-entityId = "<?= $entity_id ?>" data-dealgroupId = "<?= $dealgroup_id ?>" ><span class="fa fa-plus"></span> Add Position</button> 
                            </div>  
                          </form>                                  
                          </div>                                         
                          <div class="col-md-12 backgroundWhite" style="padding-top: 50px; margin-top: 20px; border-top: 1px solid #efefef">
                          <?php 
                            $positionassignment_query = getEntityDealGroupStaff($dealgroup_id,$entity_id);
                          ?>
                          <div class="assigned_position" style="padding:0 10px">
                            <?php if($positionassignment_query->num_rows != 0 ): ?>
                              <table class="table table-bordered table-striped table-hover" id="table_<?= $dealgroup_id . '_' .$entity_id ?>">
                                <thead>
                                  <tr>
                                    <th>Position Title</th>
                                    <th>Assigned To</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php while($positionassignment = mysqli_fetch_assoc($positionassignment_query)) : ?>
                                  <tr id="rowdealgroup_<?= $positionassignment['dealgroup_staffing_id'] ?>">
                                    <td id="position_title"><?= $positionassignment['position_title'] ?></td>
                                    <td id="assigned_to"><?= ucwords($positionassignment['first_name']) . ' ' . ucwords($positionassignment['last_name']) ?></td>
                                    <td id="start_date"><?= date('F d, Y | l', strtotime($positionassignment['start_date'])) ?></td>
                                    <td id="end_date"><?= $positionassignment['end_date'] == 0 ? 'ACTIVE' : date('F d, Y | l', strtotime($positionassignment['end_date'])) ?></td>
                                    <td>                                    
                                      <a href="javascript:void(0);" class="update_assigned_position_btn btn-xs btn-warning" data-id="<?= $positionassignment['dealgroup_staffing_id']; ?>"> UPDATE </a>&nbsp;
                                      <a href="javascript:void(0);" class="delete_assigned_position_btn btn-xs btn-danger" data-id="<?= $positionassignment['dealgroup_staffing_id']; ?>" data-user="<?= ucwords($positionassignment['first_name']) . ' ' . ucwords($positionassignment['last_name']) ?>" data-position="<?= ucwords($positionassignment['position_title']) ?>" data-tr="#rowdealgroup_<?= $positionassignment['dealgroup_staffing_id'] ?>"> REMOVE </a> 
                                    </td>
                                  </tr>
                                  <?php endwhile; ?>
                                </tbody>
                              </table>
                              <?php else : ?>
                                <p style="text-align: center;font-weight: bold">NOT SET</p>
                              <?php endif; ?>
                            </div>
                          </div>
                          <?php ?>
                        </div>
                      </div>
                    </div>
                    <?php $i++; } } else { ?>
                      <p style="text-align: center;font-weight: bold">NO ENTITIES ASSIGNED</p>
                    <?php }  ?>
                  </div>
                  <!-- end of accordion -->
                </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <div class="clearfix"></div>
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
       
