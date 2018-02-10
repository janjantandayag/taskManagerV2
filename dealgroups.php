<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
?>

  <div class="right_col" role="main">
  <div class="row" style="margin-top:70px">
    <div class="page-title">
          <div class="title_left">
            <h3>All Deal Groups</h3>
          </div>
        </div>
    <div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
      <div class="x_content">   
        <div style="float:right;margin-bottom: 30px">
        </div>       
            <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
             <thead>
                  <tr>
                    <th  style="cursor: pointer;">Group Name</th>
                    <th  style="cursor: pointer;">Code Name</th>
                    <th  style="cursor: pointer;">Sector</th>
                    <th  style="cursor: pointer;">Business Description</th>
                    <th  style="cursor: pointer;">Deal Type</th>
                    <th  style="cursor: pointer;">Club Syndicate</th>
                    <th  style="cursor: pointer;">Source</th>
                    <th  style="cursor: pointer;">Action</th>
                  </tr>
                </thead>
              <tbody>
                <?php
                  $dealgroup_query = getDealGroups(); 
                  if($dealgroup_query->num_rows != 0) {
                    while($deal_group = mysqli_fetch_assoc($dealgroup_query)){
                ?>
                <tr id="dealgroup_row<?=$deal_group['dealgroup_id']?>">
                  <td><a href="dealgroups_view.php?dealgroup_id=<?= $deal_group['dealgroup_id']; ?>" class="dashboard_table_link_hover" ><?= empty($deal_group['group_name']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['group_name']); ?></a></td>
                  <td><a href="dealgroups_view.php?dealgroup_id=<?= $deal_group['dealgroup_id']; ?>" class="dashboard_table_link_hover" ><?= empty($deal_group['code_name']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['code_name']); ?></a></td>
                  <td><?= empty($deal_group['sector']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['sector']); ?></td>
                  <td><?= empty($deal_group['business_description']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['business_description']); ?></td>
                  <td><?= empty($deal_group['deal_type']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['deal_type']); ?></td>
                  <td><?= empty($deal_group['club_syndicate']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['club_syndicate']); ?></td>
                  <td><?= empty($deal_group['source']) ? '<span class="label label-default">NOT SET</span>' : ucwords($deal_group['source']); ?></td>
                  <td>
                    <a href="dealgroups_update.php?dealgroup_id=<?=$deal_group['dealgroup_id']?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteDealGroup(<?= $deal_group['dealgroup_id'] ?>, '<?= ucwords($deal_group['group_name']) . " (" . ucwords($deal_group['code_name']) .")" ?>');"><i class="fa fa-trash-o" ></i> Delete </a>
                  </td>
                </tr>
                <?php } } ?>
              </tbody>
            </table>
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
       
