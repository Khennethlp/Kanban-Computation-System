<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-header">
              <h3 class="card-title text-uppercase"> reports dashboard</h3>
            </div>
            <div class="card-body">
              <table class="table table-striped " style="width:100%">
                <thead>
                  <tr>
                    <th>Serial No</th>
                    <th>Batch No</th>
                    <th>Group No</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Training Group</th>
                  </tr>
                </thead>
               
              </table>
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