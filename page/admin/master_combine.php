<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>
<?php
require '../../process/conn.php';
try {
  // Query to get the latest created_at date
  $sql = "SELECT created_at FROM m_combine ORDER BY created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  // Fetch the result as an associative array
  $latest_date = $stmt->fetch(PDO::FETCH_ASSOC);

  $latest_date_display = date('Y/m/d', strtotime($latest_date['created_at'])) ?? "No date available";
} catch (PDOException $e) {
  // Handle errors
  $latest_date_display = "Error: " . $e->getMessage();
}

?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f8;">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
            <li class="breadcrumb-item" style="font-weight: 600; color: #275DAD;">Bom Combine</li>
          </ol>
        </div>
        <div class="col-md-12">
          <div class="row mt-0">
            <div class="col-lg-3 col-6">
              <div class="small-box" style="background-color: #275DAD; color:#ffffff; border-radius: 14px;">
                <div class="inner">
                  <h3>Auto Combine</h3>
                  <p>BOM & BOM AID</p>
                </div>
                <div class="icon">
                  <i class="fas fa-object-group"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#import_master_combine" class="small-box-footer" style="border-radius: 14px;">
                  Click to Upload Files
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success" style=" border-radius: 14px;">
                <div class="inner">
                  <h3>Import</h3>
                  <p>Combined Master</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-excel"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#bom_aid" class="small-box-footer" style="border-radius: 14px;">
                  Click to Import
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="col-md-12">
            <div class="row">
              <p class="mr-2" style="margin-left:auto; font-size: 14px; color:#858585;">Last combined: <span><?php echo $latest_date_display; ?></span></p>
            </div>
          </div>
          <div class="card mt-1" style="border-radius: 14px;">
            <div class="card-header border-0">
              <h3 class="card-title text-uppercase text-bold">Combined Bom Records</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <input type="hidden" name="" id="user_name" class="form-control" value="<?= $_SESSION['name'] ?>">
                    <div class="col-md-3 mb-2">
                      <div class="input-group" style="border: 1px solid #ccc; border-radius: 10px; overflow: hidden;">
                        <span class="input-group-text" style="background-color: #fff; border: none;">
                          <i class="fas fa-list text-secondary"></i>
                        </span>
                        <select name="search_by_carModel" id="search_by_carModel" class="form-control" style="border-radius: 10px; border: none; box-shadow: none;" onchange="load_combined();">
                          <option value="">All</option>
                          <?php
                          require '../../process/conn.php';
                          $sql = "SELECT DISTINCT car_maker, maker_code FROM m_maker_code ORDER BY maker_code";
                          $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                          $stmt->execute();

                          if ($stmt->rowCount() > 0) {
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($rows as $row) {
                              echo '<option value="' . $row["maker_code"] . '">' . $row["car_maker"] . '</option>';
                            }
                          } else {
                            echo '<option value="">No data available</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 mb-2">
                      <div class="input-group" style="border: 1px solid #ccc; border-radius: 10px;overflow: hidden;">
                        <span class="input-group-text" style="background-color: #fff; border: none;">
                          <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="search" class="form-control" id="search_key" placeholder="Search" style="border: none;">
                      </div>
                    </div>
                    <div class="col-md-3 mb-2">
                      <div class="input-group" style="border: 1px solid #ccc; border-radius: 10px;overflow: hidden;">
                        <span class="input-group-text" style="background-color: #fff; border: none;">
                          <i class="fas fa-calendar text-secondary"></i>
                        </span>
                        <input type="date" name="" id="search_date" class="form-control text-secondary" placeholder="" style="border: none;" onchange="load_combined();">
                      </div>
                    </div>
                    <!-- <div class="col-md-2">
                      <label for="">&nbsp;</label>
                      <button class="form-control btn activeBtn" onclick="load_combined();"><i class="fas fa-search"></i> Search</button>
                    </div> -->
                  </div>
                </div>
                <!-- <button id="view_all_btn" class="ml-auto mt-2 btn exportBtn" onclick="load_all_master()">Show All</button>
                <button id="hide_btn" class="ml-auto mt-2 btn exportBtn" style="display:none;" onclick="load_master()">Show Less</button> -->
                <div class="col-md-12 mt-2">
                  <div class="mt-3" id="combine_container" style="height: 650px; overflow:auto;">
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
                  <div class="col-md-12">
                    <p class="mt-3">Results: <span id="count_per_load"></span><span id="counts"></span></p>
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
include 'plugins/js/load_data/load_combine_script.php';
include 'plugins/js/import/master_combine_script.php';
include 'plugins/js/import/bom_aid_script.php';
?>