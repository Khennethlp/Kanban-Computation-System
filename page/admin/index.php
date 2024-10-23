<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold"> overview dashboard</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Line</label>
                        <input type="text" name="" id="" class="form-control">
                      </div>
                      <div class="col-md-3">
                        <label for="">Date</label>
                        <input type="date" name="" id="" class="form-control">
                      </div>
                      <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn activeBtn"><i class="fas fa-search"></i> Search</button>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-12 mt-5" style="width:100%; height:600px; overflow:auto;">
                    <table class="table table-condensed table-hover">
                      <thead>
                        <tr>
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
                      <tbody> </tbody>
                    </table>
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
?>