<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light text-dark border-bottom-0 elevation-2">
  <a href="" class="navbar-brand ml-2">
    <img src="../../dist/img/kcs-bg.webp" alt="Web Template Logo" class="brand-image elevation-3 bg-light p-1 rounded" style="opacity: .8">
    <span class="brand-text font-weight-bold text-dark">KCS</span>
  </a>

  <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse order-3" id="navbarCollapse">
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
    </ul>

    <!-- Right navbar links -->
    <ul class="order-md-3 navbar-nav navbar-no-expand ml-auto">
      <li class="nav-item">
        </a>
        <div class="user-panel d-flex">
          <!-- <div class="image">
        <img src="../../dist/img/user.png" class="elevation-0 img-circle" alt="User Image">
      </div> -->
          <div class="info mx-3">
            <a href="#" class="d-block text-dark"><?= htmlspecialchars($_SESSION['name']); ?></a>
          </div>
        </div>
      </li>

      <li class="nav-item ">
        <a href="#" class="nav-link text-dark border rounded" data-toggle="modal" data-target="#logout_modal"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>

  </div>

</nav>
<!-- /.navbar -->