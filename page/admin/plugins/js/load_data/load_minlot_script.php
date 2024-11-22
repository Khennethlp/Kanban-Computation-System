<script>
    document.addEventListener("DOMContentLoaded", function() {
        // load_minlot();
    });

    const load_minlot = () => {
        Swal.fire({
                icon: 'info',
                title: 'Processing...',
                html: 'Please wait while we process your file.<br><span style="font-size: 16px;"><em>Please, do not refresh the page.</em></span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

        $.ajax({
            type: "POST",
            url:  "../../process/admin/masterlist/load_minlot.php",
            data: {
                method: 'load_minlot'
            },
            success: function (response) {
                $('#minlot_table').html(response);
                Swal.close();
            }
        });
    }
</script>