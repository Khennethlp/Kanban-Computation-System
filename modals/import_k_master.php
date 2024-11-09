<div class="modal fade bd-example-modal-xl" id="import_k_master" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Import Kanban Masterlist</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="csvFileForm_kanban" method="post" enctype="multipart/form-data">
                            <input type="file" class="form-control p-1" name="file" accept=".csv, .xls, .xlsx" id="csvFileInput_kanban">
                            <input type="hidden" id="user_name" class="form-control mb-2 mt-2" value="<?= $_SESSION['name']; ?>">
                            <input type="submit" name="upload" class="form-control mt-5  btn-success" value="Upload">
                        </form>
                    </div>
                </div>
                <br>
            </div>
            <!-- <div class="modal-footer ">
                <div class="col-md-4">
                    <button class="btn btn-block btn-success" onclick="">Submit</button>
                </div>
            </div> -->
        </div>
    </div>
</div>