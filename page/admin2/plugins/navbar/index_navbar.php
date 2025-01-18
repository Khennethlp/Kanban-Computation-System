<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light text-dark border-bottom-0 elevation-2">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <a href="" class="navbar-brand ml-2">
          <img src="../../dist/img/kcs-bg.webp" alt="Web Template Logo" class="brand-image elevation-3 bg-light p-1 rounded" style="opacity: .8">
          <span class="brand-text font-weight-bold text-dark">KCS</span>
        </a>
      </div>
    


    <div class="col-md-7">
      <div class="row">
        <div class="col-md-12">
          <!-- Left navbar links -->
          <ul class="navbar-nav ">
            <li class="nav-item">
              <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin2/index.php") { ?>
                <a href="index.php" class="nav-link active">
                <?php } else { ?>
                  <a href="index.php" class="nav-link">
                  <?php } ?>
                  <i class="nav-icon fa fa-home"></i>
                  Overview
                  </a>
            </li>
            <li class="nav-item">
              <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin2/masterlist.php") { ?>
                <a href="masterlist.php" class="nav-link active">
                <?php } else { ?>
                  <a href="masterlist.php" class="nav-link">
                  <?php } ?>
                  <i class="nav-icon fa fa-table"></i>
                  Masterlist
                  </a>
            </li>

            <li class="nav-item">
              <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin2/master_combine.php") { ?>
                <a href="master_combine.php" class="nav-link active">
                <?php } else { ?>
                  <a href="master_combine.php" class="nav-link">
                  <?php } ?>
                  <i class="nav-icon fa fa-file-alt"></i>
                  Bom Combine
                  </a>
            </li>
            <li class="nav-item">
              <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin2/accounts.php") { ?>
                <a href="accounts.php" class="nav-link active">
                <?php } else { ?>
                  <a href="accounts.php" class="nav-link">
                  <?php } ?>
                  <i class="nav-icon fa fa-user-cog"></i>
                  Accounts
                  </a>
            </li>
            <li class="nav-item">
              <?php if ($_SERVER['REQUEST_URI'] == "/kcs/page/admin2/others.php") { ?>
                <a href="others.php" class="nav-link active">
                <?php } else { ?>
                  <a href="others.php" class="nav-link">
                  <?php } ?>
                  <i class="nav-icon fa fa-cog"></i>
                  Others
                  </a>
            </li>
            <li class="nav-item">
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-2 ml-auto">
      <div class="col-md-6 ml-auto">
        <a href="#" class="text-dark border rounded btn btn-md " data-toggle="modal" data-target="#logout_modal"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
    </div>
  </div>
</nav>
<!-- /.navbar -->