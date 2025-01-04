<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- <div class="col-md-12 mt-3 ">
          <div class="alert text-right text-uppercase" style="background-color: #f4f6f8;">
            <h3 style="font-weight: 600;">Hello, <?= htmlspecialchars($_SESSION['name']); ?>👋🏽</h3>
          </div>
        </div> -->
        <!-- <div class="col-md-6 mt-3">
          <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f8;">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
            <li class="breadcrumb-item" style="font-weight: 600; color: #275DAD;">Overview Dashboard</li>
          </ol>
        </div> -->
        <div class="col-md-12">
          <div class="card mt-5" style="border-radius: 14px;">
            <div class="card-header border-0">
              <h3 class="card-title text-uppercase text-bold">overview dashboard</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-2 mb-2">
                        <!-- <label for="line_no">&nbsp;</label> -->
                        <div class="input-group" style="border: 1px solid #ccc; border-radius: 10px; overflow: hidden;">
                          <span class="input-group-text" style="background-color: #fff; border: none;">
                            <i class="fas fa-list text-secondary"></i>
                          </span>
                          <input
                            type="search"
                            class="line_no form-control"
                            list="line_no"
                            placeholder="Line no."
                            onchange="load_dashboard();"
                            style="border: none; box-shadow: none;">
                          <datalist id="line_no">
                            <?php
                            require '../../process/conn.php';
                            $sql = "SELECT DISTINCT line_no FROM m_master";
                            $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                              $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                              foreach ($rows as $row) {
                                echo '<option value="' . htmlspecialchars($row["line_no"]) . '">' . htmlspecialchars($row["line_no"]) . '</option>';
                              }
                            } else {
                              echo '<option value="">No data available</option>';
                            }
                            ?>
                          </datalist>
                        </div>
                      </div>
                      <div class="col-md-2 mb-2">
                        <!-- <label for="">&nbsp;</label> -->
                        <div class="d-flex align-items-center" style="border:1px solid #ccc;border-radius: 10px; padding: 0 10px;">
                          <span class="fas fa-calendar-alt mx-2 text-secondary"></span>
                          <select name="search_by_month" id="search_by_month" class="form-control ms-2" onchange="load_dashboard();" style="border:none; background-color:transparent; padding: 10px;">
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
                          <select name="search_by_year" id="search_by_year" class="form-control ms-2" onchange="load_dashboard();" style="border:none; background-color:transparent; padding: 10px;">
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
                        <!-- <label for="search_key">&nbsp;</label> -->
                        <div class="input-group" style="border: 1px solid #ccc; border-radius: 10px;overflow: hidden;">
                          <span class="input-group-text" style="background-color: #fff; border: none;">
                            <i class="fas fa-search text-secondary"></i>
                          </span>
                          <input type="search" class="form-control" id="search_key" placeholder="Search" style="border: none;" />
                        </div>
                      </div>
                      <!-- <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn activeBtn" onclick="load_dashboard();"><i class="fas fa-search"></i> Search</button>
                      </div> -->
                      <div class="col-md-2 ml-auto mb-2">
                        <!-- <label for="">&nbsp;</label> -->
                        <button class="form-control exportBtn" onclick="export_dashboard();"><i class="fas fa-cloud-download-alt"></i> Export Data</button>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mt-4" id="tbl_container" style="width:100%; height:650px; overflow:auto;">
                    <table class="table table-condensed table-hover text-center">
                      <thead class="thead-bg sticky-top">
                        <tr>
                          <th class="part-code">No.</th>
                          <th class="part-code">Line No.</th>
                          <th class="part-code">Part Code</th>
                          <th class="part-name">Part Name</th>
                          <th class="min-lot">Min Lot</th>
                          <th class="max-usage">Max Usage/Harness</th>
                          <th class="max-plan">Max Plan/Day(pcs)</th>
                          <th class="teams">No. of Teams</th>
                          <th class="takt-time">Takt Time (secs)</th>
                          <th class="conveyor-speed">Conveyor Speed (secs)</th>
                          <th class="usage-hour">Usage/Hour</th>
                          <th class="lead-time">5 hrs Lead Time</th>
                          <th class="safety-inv">1 Safety Inventory</th>
                          <th class="req-kanban">6 Req. Kanban Qty.</th>
                          <th class="issued-pd">Issued to PD</th>
                          <th class="add-kanban">(+ Add / - Reduce Kanban)</th>
                          <th class="delete-kanban">Delete Kanban No.</th>
                        </tr>
                      </thead>
                      <tbody class="text-center text-middle" id="table_dashboard"> </tbody>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="mt-3" id="dash_count"></p>
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
    </div>
  </section>
</div>


<?php
include 'plugins/footer.php';
include 'plugins/js/load_data/load_dashboard_script.php';
?>