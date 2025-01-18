<?php
include('plugins/header.php');
include('plugins/preloader.php');
include('plugins/navbar/index_navbar.php');
?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      <div class="col-md-12 mt-3">
          <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f8;">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
            <li class="breadcrumb-item" style="font-weight: 600; color: #275DAD;">Others</li>
          </ol>
        </div>
        <div class="col-sm-12">
          <div class="card mt-2" style="border-radius: 15px;">
          <div class="card-header border-0">
              <h3 class="card-title text-uppercase text-bold">Add Car Maker</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="row mt-1">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="col-md-12 mb-2">
                        <label for="">Maker Code:</label>
                        <input type="text" id="maker_code" class="form-control" placeholder="e.g. A, B, C, D, E, F">
                      </div>
                      <div class="col-md-12 mb-2">
                        <label for="">Car Maker:</label>
                        <input type="text" id="car_maker" class="form-control" placeholder="e.g. Mazda, Daihatsu, Honda, Toyota, Suzuki, Subaru">
                      </div>
                      <div class="col-md-12 mt-3">
                        <div class="col-md-3 mb-2 ml-auto">
                          <button type="submit" class="form-control activeBtn" onclick="add_maker_code();">Submit</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="">Preview List</label>
                      <textarea name="" id="" class="form-control" rows="7" cols="0" readonly><?php
                        require '../../process/conn.php';
                        $sql = "SELECT DISTINCT car_maker, maker_code FROM m_maker_code ORDER BY maker_code";
                        $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($rows as $row) {
                            echo trim(htmlspecialchars($row['maker_code'], ENT_QUOTES, 'UTF-8')) . " - " . trim(htmlspecialchars($row['car_maker'], ENT_QUOTES, 'UTF-8')). "\n" ;
                          }
                        }
                        ?></textarea>
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