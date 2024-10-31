<script>
    document.addEventListener("DOMContentLoaded", function(){
        load_dashboard();
    });

    const load_dashboard = () => {
        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'load_dashboard',
            },
            success: function (response) {
                // $('#table_dashboard').html(response);
                document.getElementById('table_dashboard').innerHTML=response;
            }
        });
    }
</script>