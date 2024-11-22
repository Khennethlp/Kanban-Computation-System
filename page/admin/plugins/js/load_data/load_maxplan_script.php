<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_maxplan();

    });

    const load_maxplan = () => {
        $.ajax({
            type: "POST",
            url:  "../../process/admin/masterlist/load_maxplan.php",
            data: {
                method: 'load_maxplan'
            },
            success: function (response) {
                $('#maxplan_table').html(response);
            }
        });
    }
</script>