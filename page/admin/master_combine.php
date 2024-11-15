<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="row mt-3">
            <div class="col-lg-3 col-6">
              <div class="small-box" style="background-color: #275DAD; color:#ffffff; border-radius: 15px;">
                <div class="inner">
                  <h3>Auto Combine</h3>
                  <p>BOM & BOM AID</p>
                </div>
                <div class="icon">
                  <i class="fas fa-object-group"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#import_master_combine" class="small-box-footer" style="border-radius: 15px;">
                  Click to Upload Files
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success" style=" border-radius: 15px;">
                <div class="inner">
                  <h3>Import</h3>
                  <p>Combined Master</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-excel"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#bom_aid" class="small-box-footer" style="border-radius: 15px;">
                  Click to Import
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card mt-2" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold">Master Table</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <input type="hidden" name="" id="user_name" class="form-control" value="<?= $_SESSION['name'] ?>">
                    <div class="col-md-3">
                      <label for="">Search</label>
                      <input type="search" name="" id="search_key" class="form-control" placeholder="Keyword...">
                    </div>
                    <div class="col-md-3">
                      <label for="">Date</label>
                      <input type="date" name="" id="search_date" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-2">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn activeBtn" onclick="load_master();"><i class="fas fa-search"></i> Search</button>
                    </div>
                  </div>
                </div>
                <!-- <button id="view_all_btn" class="ml-auto mt-2 btn exportBtn" onclick="load_all_master()">Show All</button>
                <button id="hide_btn" class="ml-auto mt-2 btn exportBtn" style="display:none;" onclick="load_master()">Show Less</button> -->
                <div class="col-md-12 mt-2">
                  <div class="mt-3" style="height: 450px; overflow:auto;">
                    <table class="table table-hover ">
                      <thead class="thead-bg sticky-top">
                        <th>No.</th>
                        <th>Car Model</th>
                        <th>Product No</th>
                        <th>Part Code</th>
                        <th>Part Name</th>
                        <th>Need Qty</th>
                        <!-- <th>Action</th> -->
                        </tr>
                      </thead>
                      <tbody id="combine_table"></tbody>
                    </table>
                  </div>
                  <!-- <p class="mt-3" id="count_master">Total: 0</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<?php
include 'plugins/footer.php';
include 'plugins/js/load_data/load_combine_script.php';
include 'plugins/js/import/master_combine_script.php';
include 'plugins/js/import/bom_aid_script.php';
?>