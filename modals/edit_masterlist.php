<div class="modal fade bd-example-modal-xl" id="edit_masterlist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Update Masterlist</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <span><b>user ID:</b></span> -->
                        <input type="hidden" id="user_name" class="form-control mb-2" value="<?= $_SESSION['name']; ?>" autocomplete="off">
                        <input type="hidden" id="id_master" class="form-control mb-2" autocomplete="off">
                        <input type="text" id="product_no" class="form-control mb-2" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        <span><b>Line No:</b></span>
                        <input type="text" id="edit_lineNo" class="form-control mb-2" placeholder="" autocomplete="off" required readonly>
                    </div>
                    <div class="col-sm-6">
                        <span><b>Partname:</b></span>
                        <input type="text" id="edit_partname" class="form-control mb-2" placeholder="" autocomplete="off" required readonly>
                    </div>
                    <div class="col-sm-6">
                        <span><b>Partcode:</b></span>
                        <input type="text" id="edit_partcode" class="form-control mb-2" placeholder="" autocomplete="off" required readonly>
                    </div>
                    <div class="col-sm-6 ">
                        <span><b>Max Plan / Day (pcs):</b></span>
                        <input type="text" name="" id="edit_maxPlan" class="form-control mb-2" readonly>
                    </div>
                    <div class="col-sm-6">
                        <span><b>Min. Lot:</b></span>
                        <input type="text" id="edit_minLot" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-6 ">
                        <span><b>Max Usage / Harness:</b></span>
                        <input type="text" name="" id="edit_maxUsage" class="form-control mb-2">
                    </div>
                    <div class="col-sm-6 ">
                        <span><b>Issued to PD:</b></span>
                        <input type="text" name="" id="edit_issued" class="form-control mb-2">
                    </div>
                    <div class="col-sm-6 ">
                        <span><b>No. of Teams:</b></span>
                        <input type="text" name="" id="edit_noTeams" class="form-control mb-2">
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer ">
                <div class="col-md-3">
                    <button class="btn  btn-block submitBtn" onclick="update_master();">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>