<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold"> Accounts Management</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="col-md-12">
                    <div class="row">
                     <div class="col-md-3">
                      <label for="">Search</label>
                      <input type="text" name="" id="" class="form-control" placeholder="Keywords...">
                     </div>
                     <div class="col-md-2">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn activeBtn"><i class="fas fa-search"></i> Search</button>
                     </div>
                    </div>
                    <div class="col-md-12 mt-5" style="max-height: 600px; overflow: auto;">
                      <table class="table table-condensed table-hover">
                        <thead>
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
<button class="add-btn" data-toggle="modal" data-target="#add_account" title="Add New User"><i class="fas fa-plus mx-2"></i>Add New User</button>
<?php
include 'plugins/footer.php';
include 'plugins/js/account/account_script.php';
?>