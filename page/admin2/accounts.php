<?php
include('plugins/header.php');
include('plugins/preloader.php');
include('plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f8;">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
            <li class="breadcrumb-item" style="font-weight: 600; color: #275DAD;">Accounts Management</li>
          </ol>
        </div>
        <div class="col-md-12">
          <div class="col-lg-3 col-6 mt-1">
            <div class="small-box bg-danger" style="border-radius: 15px;">
              <div class="inner">
                <h4>Add Account</h4>
                <p>&nbsp;</p>
              </div>
              <div class="icon">
                <i class="fas fa-user"></i>
              </div>
              <a href="#" data-toggle="modal" data-target="#add_account" class="small-box-footer" style="border-radius: 15px;">
                Click to add account
              </a>
            </div>
          </div>
          <div class="card mt-2" style="border-radius: 15px;">
            <div class="card-header border-0">
              <h3 class="card-title text-uppercase text-bold"> Accounts Management</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Search</label>
                        <input type="search" name="" id="search_acc" class="form-control" placeholder="Keywords...">
                      </div>

                      <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn activeBtn" onclick="load_accounts();"><i class="fas fa-search"></i> Search</button>
                      </div>
                      <!-- <div class="col-md-0 ml-auto">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn addBtn" data-toggle="modal" data-target="#add_account" title="Add New User"><i class="fas fa-plus mx-2"></i></button>
                      </div> -->
                    </div>
                    <div class="col-md-12 mt-5" style="max-height: 600px; overflow: auto;">
                      <table class="table table-condensed table-hover">
                        <thead class="thead-bg">
                          <tr>
                            <th>No.</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Section</th>
                            <th>Role</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="accounts_table"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- ADD Button -->
<!-- <button class="add-btn" data-toggle="modal" data-target="#add_account" title="Add New User"><i class="fas fa-plus mx-2"></i>Add New User</button> -->
<?php
include 'plugins/footer.php';
include 'plugins/js/account/account_script.php';
?>