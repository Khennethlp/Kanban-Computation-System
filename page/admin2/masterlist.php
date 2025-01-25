<?php
include('plugins/header.php');
include('plugins/preloader.php');
include('plugins/navbar/index_navbar.php');
?>


<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-2">
          <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f8;">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
            <li class="breadcrumb-item" style="font-weight: 600; color: #19323C;">Masterlist Overview</li>
          </ol>
        </div>
        <div class="col-md-12">
          <div class="card mt-1" style="border-radius: 14px;">
            <div class="card-header border-0">
              <h3 class="card-title text-uppercase text-bold">Masterlist Overview</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <input type="hidden" name="" id="user_name" class="form-control" value="<?= $_SESSION['name'] ?>">
                    <div class="col-md-12">
                      <div class="row mb-4">
                        <div class="col-md-1 ml-auto">
                          <button class="form-control btn btn-sm delBtn mb-2" id="del_masterlist" onclick="delete_master();"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                        <div class="col-md-2 ">
                          <button class="form-control btn btn-sm exportBtn mb-2" data-toggle="modal" data-target="#import_masters"><i class="fas fa-cloud-upload-alt"></i> Import Master</button>
                        </div>
                        <div class="col-md-2 ">
                          <button class="form-control btn btn-sm exportBtn mb-2" onclick="export_master();"><i class="fas fa-cloud-download-alt"></i>&nbsp;Export Master</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 mb-2">
                      <div class="d-flex align-items-center" style="border:1px solid #ccc;border-radius: 10px; padding: 0 10px;">
                        <span class="fas fa-calendar-alt mx-2 text-secondary"></span>
                        <select name="search_by_month" id="search_by_month" class="form-control ms-2" style="border:none; background-color:transparent; padding: 10px;" onchange="load_master();">
                          <option value="" selected>This Month</option>
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
                    </div>
                    <div class="col-md-2 mb-2">
                      <div class="d-flex align-items-center" style="border:1px solid #ccc;border-radius: 10px; padding: 0 10px;">
                        <span class="fas fa-calendar-alt mx-2 text-secondary"></span>
                        <select name="search_by_year" id="search_by_year" class="form-control ms-2" onchange="load_master();" style="border:none; background-color:transparent; padding: 10px;">
                          <option value="" selected>This Year</option>
                          <?php
                          $current_year = date('Y');
                          for ($year = $current_year; $year >= 2024; $year--) {
                            echo '<option value="' . $year . '" >' . $year . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 mb-2">
                      <div class="input-group search_key" style="border: 1px solid #ccc; border-radius: 10px;overflow: hidden; transition: width 0.3s ease;">
                        <span class="input-group-text" style="background-color: #fff; border: none;">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="search" class="form-control" id="search_key" placeholder="Search by Line, Product, Partcode, Partname" style="border: none;" onchange="" />
                      </div>
                    </div>
                    <div class="col-md-2 mb-2 ml-auto">
                      <button class="form-control btn generateBtn" id="generateRecords" onclick="generateRecords();"><i class="fas fa-sync-alt"></i> Generate record</button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 mt-4" id="tbl_container" style="height: 650px; overflow:auto;">
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
include 'plugins/js/import/master_data_script.php';
?>