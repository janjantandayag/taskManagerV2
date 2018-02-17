<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
?>

<div class="right_col" role="main">
	<div class="">		
		<div class="row">
			<h5 style="text-align: center">
				<strong style="color: #cc1b1b">ERROR:</strong> There was an error processing your request!
			</h5>
		</div>	
	</div>
</div>

<?php
  include('includes/footer.php');



} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
