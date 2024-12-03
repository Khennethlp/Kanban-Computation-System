<script>
    const add_maker_code = () => {
        var maker_code = $('#maker_code').val();
        var car_maker = $('#car_maker').val();

        Swal.fire({
            icon: 'info',
            title: 'Inserting...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: '../../process/admin/masterlist/functions.php',
            data: {
                method: "add_car_maker_code",
                maker_code: maker_code,
                car_maker: car_maker
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Inserted Successfully!",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#maker_code').val("");
                    $('#car_maker').val("");
                } else if (response == 'error') {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>