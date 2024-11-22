<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_minlot();
    });

    const load_minlot = () => {
        Swal.fire({
                icon: 'info',
                title: 'In Progress...',
                html: 'Just a moment, we\'re loading your data...',
                allowOutsideClick: true,
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