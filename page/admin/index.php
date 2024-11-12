<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold"> computation dashboard</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Line</label>
                        <!-- <input type="text" name="" id="" class="form-control"> -->
                        <select name="line_no" id="line_no" class="form-control" style="border-radius: 15px;" onchange="load_dashboard();">
                          <option value="">All Line</option>
                          <?php
                          require '../../process/conn.php';

                          $getLine = "SELECT DISTINCT line_no FROM m_master";
                          $stmt = $conn->prepare($getLine, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                          $stmt->execute();

                          if ($stmt->rowCount() > 0) {
                            // Output data of each row
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Output data of each row
                            foreach ($rows as $row) {

                              echo '<option value="' . $row["line_no"] . '">' . $row["line_no"] . '</option>';
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label for="">Date</label>
                        <input type="date" name="" id="getDate" class="form-control" onchange="load_dashboard();">
                      </div>
                      <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn activeBtn" onclick="load_dashboard();"><i class="fas fa-search"></i> Search</button>
                      </div>
                      <div class="col-md-2 ml-auto">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn exportBtn" onclick="export_dashboard();"><i class="fas fa-file-export"></i> Export</button>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mt-5" id="tbl_container" style="width:100%; height:500px; overflow:auto;">
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