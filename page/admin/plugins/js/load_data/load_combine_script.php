<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_combined();
    });

    const load_combined = () => {

        Swal.fire({
                icon: 'info',
                title: 'In Progress...',
                html: 'Just a moment, we\'re loading your data...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_combine.php",
            data: {
                method: 'load_combine'
            },
            success: function(response) {
                $('#combine_table').html(response);
                Swal.close();
            }
        });
    }
</script>