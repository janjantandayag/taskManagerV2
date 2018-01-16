<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
	include('includes/header.php');
	include('includes/sidebar.php');
	include('includes/top_navigation.php');
	include('database/user_functions.php');

  $users = getUsers();
?>

<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Password Reset</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">          
        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Name</th>
              <th>Reset</th>
            </tr>
          </thead>
          <tbody>
            <?php while($user = mysqli_fetch_assoc($users)) : ?>
            <tr>
              <td class="row-<?= $user['user_id']; ?>"><?= ucfirst($user['first_name'])  . ' ' . ucfirst($user['last_name'])  ?></td>
              <td>
                <button class="btn btn-warning btn-xs" type="button" value="row-<?=$user['user_id']?>" onclick="resetPassword(this);" style="font-size: 10px">RESET</button>
                <input type="hidden" id="row-<?=$user['user_id']; ?>" value="<?=$user['user_id']; ?>" />
              </td>
            </tr>
            <?php endwhile; ?>
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
       
