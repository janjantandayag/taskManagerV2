<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Task Manager Application</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="database/attachment/images/profile/<?= $_SESSION['profile_image'] ?>" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2><?= ucfirst($_SESSION['first_name']) . ' ' . ucfirst($_SESSION['last_name']) ?></h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-user-plus"></i> Users <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="users.php">All Users</a></li>
              <li><a href="users_form.php">Add New User</a></li>
              <li><a href="users_password_reset.php">Password Reset</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-list-alt"></i> Tasks <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="tasks.php">All Tasks</a></li>
              <li><a href="tasks_upcoming.php">Upcoming</a></li>
              <li><a href="tasks_inprogress.php">In Progress</a></li>
              <li><a href="tasks_completed.php">Completed</a></li>
              <li><a href="tasks_pastdue.php">Past Due</a></li>
              <li><a href="tasks_add.php">Add New Task</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-building-o"></i> Entities <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="entities.php">All Entities</a></li>
              <li><a href="entities_add.php">Add New Entity</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-users"></i> Deal Groups <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="dealgroups.php">All Deal Groups</a></li>
              <li><a href="dealgroups_add.php">Add New Deal Group</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-file"></i> Documents <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="documents.php">All Documents</a></li>
              <li><a href="documents_add.php">Add New Document</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-edit"></i> Assignment <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="assignment_position.php">Positions</a></li>
              <li><a href="assignment_dealgroup.php">Deal Groups</a></li>
              <li><a href="assignment_document.php">Documents</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-share-square"></i> Vacation <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="vacations.php">All Requests</a></li>
              <li><a href="my_vacations.php">My Requests</a></li>
              <li><a href="vacations_request.php">Send A Request</a></li>
              <li><a href="vacations_pending.php">Pending For Confirmation</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout" href="database/user_functions.php?action=logout">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>