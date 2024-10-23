<div class="modal fade bd-example-modal-xl" id="edit_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Edit User Account</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <span><b>user ID:</b></span> -->
                        <input type="hidden" id="user_id" class="form-control mb-2" value="<?= $_SESSION['id']; ?>" autocomplete="off">
                        <input type="hidden" id="id" class="form-control mb-2" autocomplete="off">
                    </div>
                    <div class="col-sm-12">
                        <span><b>Employee ID:</b></span>
                        <input type="text" id="edit_employeeID" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Fullname:</b></span>
                        <input type="text" id="edit_fullname" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Username:</b></span>
                        <input type="text" id="edit_username" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Password:</b></span>
                        <input type="password" id="edit_password" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12 ">
                        <span><b>Section:</b></span>
                        <input type="text" name="" id="edit_section" class="form-control mb-2">
                    </div>
                    <div class="col-sm-12 ">
                        <span><b>Role:</b></span>
                        <input type="text" name="" id="edit_role" class="form-control mb-2">
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer ">
                <div class="col-md-4">
                    <button class="btn  btn-block submitBtn" onclick="updateuser();">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>