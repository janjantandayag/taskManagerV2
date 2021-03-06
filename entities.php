<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/entity_functions.php');
?>

  <div class="right_col" role="main">
  <div class="row" style="margin-top:70px">
    <div class="page-title">
          <div class="title_left">
            <h3>All Entities</h3>
          </div>
        </div>
    <div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
      <div class="x_content">   
        <div style="float:right;margin-bottom: 30px">
        </div>       
            <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
             <thead>
                  <tr>
                    <th  style="cursor: pointer;">Legal Name</th>
                    <th  style="cursor: pointer;">Address</th>
                    <th  style="cursor: pointer;">Incorporation State</th>
                  </tr>
                </thead>
              <tbody>
                <?php
                  $entity_query = getEntities(); 
                  if($entity_query->num_rows != 0) {
                    while($entity = mysqli_fetch_assoc($entity_query)){
                      $street_add = strlen($entity['street_address']) == 10 ? substr($entity['street_address'],0,10) : substr($entity['street_address'],0,10) . '.., ';
                      $city = strlen($entity['city']) == 8 ? substr($entity['city'],0,8) : substr($entity['city'],0,8) . '.. ';
                      $address = ucwords($street_add) . '<br/>' . ucwords($city);
                ?>
                <tr id="entityrow_<?=$entity['entity_id']?>">
                  <td><a href="entities_view.php?entity_id=<?= $entity['entity_id']; ?>" class="dashboard_table_link_hover" ><?= empty($entity['entity_legal_name']) ? '<span class="label label-default">NOT SET</span>' : ucwords($entity['entity_legal_name']); ?></a></td>
                  <td title="<?= empty($address) ? '' : ucwords($entity['street_address'] .', '. $entity['city'])?>"><?= empty($address) ? '<span class="label label-default">NOT SET</span>' : $address ?></td>
                  <td><?= empty($entity['incorporation_state']) ? '<span class="label label-default">NOT SET</span>' : ucwords($entity['incorporation_state']); ?></td>
                  <!-- <td>
                    <a href="entities_update.php?entity_id=<?=$entity['entity_id']?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteEntity(<?= $entity['entity_id'] ?>, '<?= ucwords($entity['entity_legal_name']) . " (" . ucwords($entity['entity_nickname']) .")" ?>');"><i class="fa fa-trash-o" ></i> Delete </a>
                  </td> -->
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
       
