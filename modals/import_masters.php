<div class="modal fade bd-example-modal-xl" id="import_masters" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5>Import Master</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form id="csvFileForms" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" id="userName" class="form-control mb-2 mt-2" value="<?= $_SESSION['name']; ?>">
                            <div class="col-md-8 mb-2">
                                <label for="">Max Plan:</label>
                                <input type="file" class="form-control p-1" name="file" accept=".csv, .xls, .xlsx" id="csvFileInput_maxplan">
                            </div>
                            <div class="col-md-4 ">
                                <label for="">&nbsp;</label>
                                <a href="../../template/Max Plan.csv" class="form-control text-dark text-center mb-2"><i class="fas fa-download"></i> Template</a>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label for="">Min Lot:</label>
                                <input type="file" class="form-control p-1" name="file" accept=".csv, .xls, .xlsx" id="csvFileInput_minlot">
                            </div>
                            <div class="col-md-4">
                                <label for="">&nbsp;</label>
                                <a href="../../template/Minlot.csv" class="form-control text-dark text-center mb-2"><i class="fas fa-download"></i> Template</a>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label for="">No. of teams:</label>
                                <input type="file" class="form-control p-1" name="file" accept=".csv, .xls, .xlsx" id="csvFileInput_teams">
                            </div>
                            <div class="col-md-4">
                                <label for="">&nbsp;</label>
                                <a href="../../template/No of Teams.csv" class="form-control text-dark text-center mb-2"><i class="fas fa-download"></i> Template</a>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label for="">Kanban Masterlist:</label>
                                <input type="file" class="form-control p-1" name="file" accept=".csv, .xls, .xlsx" id="csvFileInput_kanban">
                            </div>
                            <input type="submit" name="upload" class="form-control mt-3  btn-success" value="Upload">
                        </div>
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>