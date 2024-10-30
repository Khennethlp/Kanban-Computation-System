<script>
    document.addEventListener("DOMContentLoaded", function () {
        load_master();
    });

    const load_master = () => {
        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_master.php",
            data: {
                method: 'load_master'
            },
            success: function(response) {
                document.getElementById('import_table').innerHTML = response;
            }
        });
    }
</script>
