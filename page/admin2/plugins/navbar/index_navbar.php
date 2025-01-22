<!-- Main Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light bg-white text-dark border-0 pt-2 pb-2">
  <div class="d-flex justify-content-between align-items-center w-100">
    <!-- Brand -->
    <a href="" class="navbar-brand ml-2">
      <img src="../../dist/img/kcs-bg.webp" alt="Web Template Logo" class="brand-image elevation-1 bg-light p-0 rounded" style="opacity: .8">
      <span class="brand-text font-weight-bold text-dark nav-title">Kanban Computation System</span>
    </a>
    <!-- Logout Button -->
    <a href="#" class="text-white border rounded btn btn-md btn-dark" data-toggle="modal" data-target="#logout_modal">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</nav>

<nav id="sticky-navbar" class="main-header navbar navbar-expand-md navbar-light border-bottom-1 elevation-0 pt-2 pb-2 sticky-top">
  <div class="container">
    <ul class="navbar-nav w-100 d-flex justify-content-around">
      <li class="nav-item">
        <a href="index.php" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/kcs/page/admin2/index.php') ? 'active' : '' ?>">
          <i class="nav-icon fa fa-home"></i> Overview
        </a>
      </li>
      <li class="nav-item">
        <a href="masterlist.php" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/kcs/page/admin2/masterlist.php') ? 'active' : '' ?>">
          <i class="nav-icon fa fa-table"></i> Masterlist
        </a>
      </li>
      <li class="nav-item">
        <a href="master_combine.php" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/kcs/page/admin2/master_combine.php') ? 'active' : '' ?>">
          <i class="nav-icon fa fa-file-alt"></i> Bom Combine
        </a>
      </li>
      <li class="nav-item">
        <a href="accounts.php" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/kcs/page/admin2/accounts.php') ? 'active' : '' ?>">
          <i class="nav-icon fa fa-user-cog"></i> Accounts
        </a>
      </li>
      <li class="nav-item">
        <a href="others.php" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/kcs/page/admin2/others.php') ? 'active' : '' ?>">
          <i class="nav-icon fa fa-cog"></i> Car Maker
        </a>
      </li>
    </ul>
  </div>
</nav>
