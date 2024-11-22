<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="row mt-3">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger" style=" border-radius: 15px;">
                <div class="inner">
                  <h3>Min Lot</h3>
                  <p>&nbsp;</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-csv"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#import_minlot" class="small-box-footer" style="border-radius: 15px;">
                  Click to Import
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card mt-2" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold">Min Lot Master Record</h3>
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
                    <!-- <div class="col-md-3">
                      <label for="">Date</label>
                      <input type="date" name="" id="search_date" class="form-control" placeholder="" >
                    </div> -->
                    <div class="col-md-2">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn activeBtn" ><i class="fas fa-search"></i> Search</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mt-2">
                  <div class="mt-3" style="height: 650px; overflow:auto;">
                    <table class="table table-hover ">
                      <thead class="thead-bg sticky-top">
                        <th>No.</th>
                        <th>Part Code</th>
                        <th>Part Name</th>
                        <th>Min Lot</th>
                        </tr>
                      </thead>
                      <tbody id="minlot_table"></tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <p class="mt-3" >Results: <span id="count_per_load"></span><span id="counts"></span></p>
                    <div id="load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline px-3 py-2 mt-3 " style="cursor: pointer; font-size: 15px; padding: 20px 0;">Load More...</p>
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

<?php
include 'plugins/footer.php';
// include 'plugins/js/load_data/load_combine_script.php';
include 'plugins/js/import/minlot_script.php';
?>