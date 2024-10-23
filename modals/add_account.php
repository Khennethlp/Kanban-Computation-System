<div class="modal fade bd-example-modal-xl" id="add_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Add New User Account</b>
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
                    </div>
                    <div class="col-sm-12">
                        <span><b>Employee ID:</b></span>
                        <input type="text" id="add_employeeID" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Fullname:</b></span>
                        <input type="text" id="add_fullname" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Username:</b></span>
                        <input type="text" id="add_username" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12">
                        <span><b>Password:</b></span>
                        <input type="password" id="add_password" class="form-control mb-2" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="col-sm-12 ">
                        <span><b>Section:</b></span>
                        <input type="text" name="" id="add_section" class="form-control mb-2">
                    </div>
                    <div class="col-sm-12 ">
                        <span><b>Role:</b></span>
                        <input type="text" name="" id="add_role" class="form-control mb-2">
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer ">
                <div class="col-md-4">
                    <button class="btn  btn-block submitBtn" onclick="add_account();" >Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>