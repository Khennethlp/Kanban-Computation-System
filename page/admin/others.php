<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mt-4" style="border-radius: 15px;">
            <div class="card-body">
              <h4>Add Car Maker</h4>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="col-md-6 mb-2">
                      <label for="">Maker Code:</label>
                      <input type="text" id="maker_code" class="form-control" placeholder="e.g. A, B, C, D, E, F">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label for="">Car Maker:</label>
                      <input type="text" id="car_maker" class="form-control" placeholder="e.g. Mazda, Daihatsu, Honda, Toyota, Suzuki, Subaru">
                    </div>
                    <div class="col-md-6 mt-3">
                      <div class="col-md-3 mb-2 ml-auto">
                        <button type="submit" class="form-control activeBtn" onclick="add_maker_code();">Submit</button> 
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
include 'plugins/js/functions/add_maker_code_script.php';

?>