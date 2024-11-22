<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_maxplan();

    });

    const load_maxplan = () => {
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
            url: "../../process/admin/masterlist/load_maxplan.php",
            data: {
                method: 'load_maxplan'
            },
            success: function(response) {
                $('#maxplan_table').html(response);
                Swal.close();
            }
        });
    }
</script>