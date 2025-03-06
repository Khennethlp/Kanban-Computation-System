<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_accounts();
    });

    document.querySelectorAll('#search_acc').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_accounts();
            }
        });
    });

    const load_accounts = () => {
        var acc_search = document.getElementById('search_acc').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/account/load_accounts.php",
            data: {
                method: 'load_accounts',
                search: acc_search
            },
            success: function(response) {
                // $('#accounts_table').html(response);
                document.getElementById('accounts_table').innerHTML = response;
            }
        });
    }

    const add_account = () => {
        var emp_id = $('#add_employeeID').val();
        var fullname = $('#add_fullname').val();
        var username = $('#add_username').val();
        var password = $('#add_password').val();
        var section = $('#add_section').val();
        var role = $('#add_role').val();

        if(!emp_id || !fullname || !username || !password || !section || !role){
            Swal.fire("Please fill up all fields.", "", "warning");
            return;
        }
        
        $.ajax({
            type: "POST",
            url: "../../process/admin/account/load_accounts.php",
            data: {
                method: 'add_account',
                emp_id: emp_id,
                fullname: fullname,
                username: username,
                password: password,
                section: section,
                role: role
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire("Success!", "", "success");
                    load_accounts();
                    $('#add_account').modal('hide');
                } else if (response == 'failed') {
                    Swal.fire("Failed to add account.", "", "warning");
                } else {
                    Swal.fire("Something went wrong.", "", "error");
                }
            }
        });
    }

    const editUser = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var emp_id = data[1];
        var fullname = data[2];
        var username = data[3];
        var password = data[4];
        var dept = data[5];
        var role = data[6];

        console.log(id);
        console.log(emp_id);
        console.log(fullname);
        console.log(username);
        console.log(password);
        console.log(dept);
        console.log(role);

        $('#id').val(id);
        $('#edit_employeeID').val(emp_id);
        $('#edit_fullname').val(fullname);
        $('#edit_username').val(username);
        $('#edit_password').val(password);
        $('#edit_section').val(dept);
        $('#edit_role').val(role);

    }

    const updateuser = () => {
        var id = $('#id').val();
        var emp_id = $('#edit_employeeID').val();
        var fullname = $('#edit_fullname').val();
        var username = $('#edit_username').val();
        var password = $('#edit_password').val();
        var section = $('#edit_section').val();
        var role = $('#edit_role').val();


        $.ajax({
            type: "POST",
            url: "../../process/admin/account/load_accounts.php",
            data: {
                method: 'edit_user',
                id: id,
                emp_id: emp_id,
                fullname: fullname,
                username: username,
                password: password,
                section: section,
                role: role
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire("Updated Successfully!", "", "success");
                    load_accounts();
                    $('#edit_account').modal('hide');
                } else if (response == 'failed') {
                    Swal.fire("Failed to update user.", "", "warning");
                } else {
                    Swal.fire("Something went wrong.", "", "error");
                }
            }
        });
    }

    const delUser = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var emp_id = data[1];

        console.log(id);
        console.log(emp_id);

        Swal.fire({
            title: "Do you want to remove this user?",
            showCancelButton: true,
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "../../process/admin/account/load_accounts.php",
                    data: {
                        method: 'remove_user',
                        id: id,
                        emp_id: emp_id
                    },
                    success: function(response) {
                        if (response == 'success') {
                            Swal.fire("Deleted Successfully!", "", "success");
                            load_accounts();
                        } else if (response == 'failed') {
                            Swal.fire("Failed to remove this user.", "", "warning");
                        } else {
                            Swal.fire("Something went wrong.", "", "error");
                        }
                    }
                });
            }
        });

    }
</script>