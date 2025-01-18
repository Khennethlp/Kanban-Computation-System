<script>
    const add_maker_code = () => {
        var maker_code = $('#maker_code').val().trim().toUpperCase();
        var car_maker = $('#car_maker').val().trim();



        if (maker_code === '' || car_maker === '') {
            Swal.fire({
                icon: "info",
                title: "Field should not be empty.",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

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
                } else if (response == 'exist') {
                    Swal.fire({
                        icon: "info",
                        title: "Car Maker or Maker Code already exist.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>