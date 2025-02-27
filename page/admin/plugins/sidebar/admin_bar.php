<aside class="main-sidebar sidebar-light-primary  elevation-0" id="sidebar" style="background-color: #fff;">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link" style="background-color: #fff; color: #1D2935; border:none;">
    <img src="../../dist/img/kcs-bg.webp" alt="Logo" class="brand-image img-circle elevation-1" style="opacity: .8">
    <span class="brand-text font-weight-bold text-uppercase" style="font-size: 15px;">KANBAN COMPUTATION </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../dist/img/user.png" class=" elevation-0" alt="User Image">
      </div>
      <div class="info">
        <a href="index.php" class="d-block"><?= htmlspecialchars($_SESSION['name']); ?></a>
      </div>
    </div> -->
  
    <!-- Sidebar Menu -->
    <nav class="mt-5">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/index.php") { ?>
            <a href="index.php" class="nav-link active">
            <?php } else { ?>
              <a href="index.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-home"></i>
              <!-- <img src="../../dist/img/dashboard.png" class="icon-image" height="25" width="25">&nbsp;&nbsp;&nbsp; -->
              <p>
                Overview
              </p>
              </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/masterlist.php") { ?>
            <a href="masterlist.php" class="nav-link active">
            <?php } else { ?>
              <a href="masterlist.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-table"></i>
              <!-- <img src="../../dist/img/list.png" class="icon-image" height="25" width="25" >&nbsp;&nbsp;&nbsp; -->
              <p>
                Masterlist
              </p>
              </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/master_combine.php") { ?>
            <a href="master_combine.php" class="nav-link active">
            <?php } else { ?>
              <a href="master_combine.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-file-alt"></i>
              <!-- <img src="../../dist/img/list.png" class="icon-image" height="25" width="25" >&nbsp;&nbsp;&nbsp; -->
              <p>
               Bom Combine
              </p>
              </a>
        </li>
        <!-- <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/kanban_master.php") { ?>
            <a href="kanban_master.php" class="nav-link active">
            <?php } else { ?>
              <a href="kanban_master.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-list"></i>
              <p>
                Kanban Master
              </p>
              </a>
        </li> -->
        <!-- <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/max_plan.php") { ?>
            <a href="max_plan.php" class="nav-link active">
            <?php } else { ?>
              <a href="max_plan.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-chart-bar"></i>
              <p>
                Max Plan
              </p>
              </a>
        </li> -->
        <!-- <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/min_lot.php") { ?>
            <a href="min_lot.php" class="nav-link active">
            <?php } else { ?>
              <a href="min_lot.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-list"></i>
              <p>
                Min Lot
              </p>
              </a>
        </li> -->
        <!-- <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/teams.php") { ?>
            <a href="teams.php" class="nav-link active">
            <?php } else { ?>
              <a href="teams.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-users"></i>
              <p>
               No. of Teams
              </p>
              </a>
        </li> -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/accounts.php") { ?>
            <a href="accounts.php" class="nav-link active">
            <?php } else { ?>
              <a href="accounts.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-user-cog"></i>
              <!-- <img src="../../dist/img/account_manager.png" class="icon-image" height="30" width="30" >&nbsp;&nbsp;&nbsp; -->
              <p>
                Accounts
              </p>
              </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin/others.php") { ?>
            <a href="others.php" class="nav-link active">
            <?php } else { ?>
              <a href="others.php" class="nav-link">
              <?php } ?>
              <i class="nav-icon fa fa-cog"></i>
              <!-- <img src="../../dist/img/account_manager.png" class="icon-image" height="30" width="30" >&nbsp;&nbsp;&nbsp; -->
              <p>
                Others
              </p>
              </a>
        </li>

        <?php include 'logout.php'; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
    <!-- <p class="text-muted text-center" style="font-size: 11px;">Beta Version 1.0</p> -->
  </div>
  <div class="sidebar-bottom">
    <p class="text-muted text-center" style="font-size: 11px; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);">Version 1.0.0</p>
  </div>
  <!-- /.sidebar -->
</aside>