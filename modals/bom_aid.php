<div class="modal fade bd-example-modal-xl" id="bom_aid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Import Combined Master</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="csvFileForm_bomAid" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="user_name" class="form-control mb-2 mt-2" value="<?= $_SESSION['name']; ?>">
                            <label for="">BOM Master</label>
                            <input type="file" class="form-control p-1" name="csvFile_bomAid" accept=".csv, .xls, .xlsx" id="csvFileInput_bomAid">
                            <input type="submit" name="upload" class="form-control mt-5  btn-success" value="Upload">
                        </form>
                        <hr>
                        <div class="mt-0">
                            <a href="../../template/Master Record.csv" class="form-control btn text-blue"><i class="fas fa-download"></i> Download Template</a>
                        </div>
                        
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