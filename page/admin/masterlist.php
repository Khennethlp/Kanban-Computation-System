<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card mt-2" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold">Masterlist</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
              </div>
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
                    <div class="col-md-2 ">
                        <label for="">Month</label>
                        <select name="search_by_month" id="search_by_month" class="form-control" style="border-radius: 15px;">
                          <option value=""></option>
                          <option value="1">JANUARY</option>
                          <option value="2">FEBRUARY</option>
                          <option value="3">MARCH</option>
                          <option value="4">APRIL</option>
                          <option value="5">MAY</option>
                          <option value="6">JUNE</option>
                          <option value="7">JULY</option>
                          <option value="8">AUGUST</option>
                          <option value="9">SEPTEMBER</option>
                          <option value="10">OCTOBER</option>
                          <option value="11">NOVEMBER</option>
                          <option value="12">DECEMBER</option>
                        </select>
                      </div>
                    <!-- <div class="col-md-3">
                      <label for="">Date</label>
                      <input type="date" name="" id="search_date" class="form-control" placeholder="">
                    </div> -->
                    <div class="col-md-2">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn activeBtn" onclick="load_master();"><i class="fas fa-search"></i> Search</button>
                    </div>
                    <div class="col-md-2 ml-auto">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn exportBtn" data-toggle="modal" data-target="#import_masters"><i class="fas fa-upload"></i> Import Master</button>
                    </div>
                    <div class="col-md-2 ">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn exportBtn" onclick="export_master();"><i class="fas fa-download"></i> Export Master</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mt-2">
                  <div class="mt-3" id="tbl_container" style="height: 650px; overflow:auto;">
                    <table class="table table-hover ">
                      <thead class="thead-bg sticky-top">
                        <th>No.</th>
                        <th>Line No.</th>
                        <th>Product No.</th>
                        <th>Part Code</th>
                        <th>Part Name</th>
                        <th>Min. Lot</th>
                        <th>Max Usage / Harness</th>
                        <th>Max Plan / Day (pcs)</th>
                        <th>No. of Teams</th>
                        <th>Issued to PD</th>
                        <th>Parts Group</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="import_table"></tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="mt-3" id="count_masters"></p>
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
include 'plugins/js/load_data/load_master_script.php';
include 'plugins/js/import/masterlist_import_script.php';
include 'plugins/js/import/maxplan_script.php';
include 'plugins/js/import/minlot_script.php';
include 'plugins/js/import/teams_script.php';
?>