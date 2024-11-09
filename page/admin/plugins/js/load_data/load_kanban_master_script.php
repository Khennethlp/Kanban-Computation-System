<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_kanban();

    });

    const load_kanban = () => {
        $.ajax({
            type: "POST",
            url:  "../../process/admin/masterlist/load_kanban.php",
            data: {
                method: 'load_kanban'
            },
            success: function (response) {
                $('#import_kanban_table').html(response);
            }
        });
    }
</script>