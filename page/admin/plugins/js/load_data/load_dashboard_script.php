<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_dashboard();
    });

    const load_dashboard = () => {
        var line_no = document.getElementById('line_no').value;
        var getDate = document.getElementById('getDate').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'load_dashboard',
                line_no: line_no,
                search_date: getDate
            },
            success: function(response) {
                // $('#table_dashboard').html(response);
                document.getElementById('table_dashboard').innerHTML = response;
            }
        });
    }

    const export_dashboard = () => {
        var line_no = document.getElementById('line_no').value;
        var date = document.getElementById('getDate').value;

        window.open('../../process/export/export_dash.php?line_no='+line_no+'&date='+date, '_blank');
    }
</script>