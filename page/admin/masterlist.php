<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase text-bold"> Masterlist dashboard</h3>
            </div>
            <div class="card-body">
              <div class="row ">
                <div class="col-md-2 ">
                  <button class="btn btn-success " data-toggle="modal" data-target="#import_masterlist">Import Masterlist</button>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class=" mt-3" style="height: 500px; overflow:auto;">
                    <table id="import_table" class="table table-hover ">
                      <!-- <thead>
                        <th>
                          <tr>
                            <th>#</th>
                            <th>Part Name</th>
                            <th>Part Code</th>
                            <th>Part Code</th>
                          </tr>
                        </th>
                      </thead> -->
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
include 'plugins/js/import/masterlist_import_script.php';
?>