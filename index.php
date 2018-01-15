<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  include('includes/header.php');
  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
    header("Location: dashboard.php");
  } else {

?>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section id="error"> 
            <?php if(isset($_SESSION['login_error'])) : ?>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <strong>ERROR!</strong> <?= $_SESSION['login_error']; ?>
              </div>  
          <?php session_destroy(); endif; ?>
          </section>
          <section class="login_content">
            <form method="POST" action="database/user_functions.php">
              <h1>Login</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" name="username" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" required="" />
              </div>
              <div>
                <input type="submit" style="float:none;margin:0" name="login" class="btn btn-default submit" value="Log in" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Task Manager Application</h1>
                  <p>© <?= date('Y'); ?> All Rights Reserved</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
<?php

  include('includes/footer.php');
}
?>
