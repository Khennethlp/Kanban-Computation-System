<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="row mt-3">
            <div class="col-lg-3 col-6">
              <div class="small-box " style="background-color: #275DAD; color:#ffffff; border-radius: 15px;">
                <div class="inner">
                  <h3>Master</h3>
                  <p>Template</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-excel"></i>
                </div>
                <a href="../../template/Master.xlsx" class="small-box-footer" style="border-radius: 15px;">
                  Click to download
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success" style=" border-radius: 15px;">
                <div class="inner">
                  <h3>Import</h3>
                  <p>Masterlist</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-excel"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#import_masterlist" class="small-box-footer" style="border-radius: 15px;">
                  Click to Import
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card mt-2" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold">Masterlist</h3>
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
                <div class="col-md-12 mt-2">
                  <div class="mt-3" id="tbl_container" style="height: 450px; overflow:auto;">
                    <table class="table table-hover ">
                      <thead class="thead-bg sticky-top">
                        <th>No.</th>
                        <th>Line No.</th>
                        <th>Part Code</th>
                        <th>Part Name</th>
                        <th>Min. Lot</th>
                        <th>Max Usage / Harness</th>
                        <th>Max Plan / Day (pcs)</th>
                        <th>No. of Teams</th>
                        <th>Issued to PD</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="import_table"></tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="mt-3" id="count_master"></p>
                      </div>
                      <div class="col-md-12">
                        <div id="load_more" class="text-center" style="display: none;">
                          <p class="badge badge-dark border border-outline px-3 py-2 mt-3 " style="cursor: pointer; font-size: 15px; padding: 20px 0;">Load More...</p>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3" id="count_master">Total: 100</p> -->
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
include 'plugins/js/import/masterlist_import_script.php';
include 'plugins/js/load_data/load_master_script.php';
?>