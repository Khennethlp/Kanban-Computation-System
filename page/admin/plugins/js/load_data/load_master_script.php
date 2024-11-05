<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_master();
    });
    document.querySelectorAll('#search_key', '#search_date').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_master();
            }
        });
    });
    const load_master = () => {
        var user_name = $('#user_name').val();
        var search_key = $('#search_key').val();
        var search_date = $('#search_date').val();
        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_master.php",
            data: {
                method: 'load_master',
                user_name: user_name,
                search_key: search_key,
                search_date: search_date
            },
            success: function(response) {
                document.getElementById('import_table').innerHTML = response;
            }
        });
    }

    const getMaster = (param) => {
        $data = param.split('~!~');

        $id = $data[0];
        $line_no = $data[1];
        $partcode = $data[2];
        $partname = $data[3];
        $min_lot = $data[4];
        $max_usage = $data[5];
        $max_plan = $data[6];
        $no_teams = $data[7];
        $issued_to_pd = $data[8];
        $added_by = $data[9];

        console.log("id: " + $id);
        console.log("line_no: " + $line_no);
        console.log("partcode: " + $partcode);
        console.log("partname: " + $partname);
        console.log("min_lot: " + $min_lot);
        console.log("max_usage: " + $max_usage);
        console.log("max_plan: " + $max_plan);
        console.log("no_teams: " + $no_teams);
        console.log("issued_to_pd: " + $issued_to_pd);
        console.log("added_by: " + $added_by);

        $('#id_master').val($id);
        $('#edit_lineNo').val($line_no);
        $('#edit_partname').val($partcode);
        $('#edit_partcode').val($partname);
        $('#edit_minLot').val($min_lot);
        $('#edit_maxUsage').val($max_usage);
        $('#edit_maxPlan').val($max_plan);
        $('#edit_issued').val($issued_to_pd);
        $('#edit_noTeams').val($no_teams);

    }

    const update_master = () => {
        var id = $('#id_master').val();
        var partname = $('#edit_partname').val();
        var partcode = $('#edit_partcode').val();
        var minLot = $('#edit_minLot').val();
        var maxPlan = $('#edit_maxPlan').val();
        var noTeams = $('#edit_noTeams').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/functions.php",
            data: {
                method: 'update_master',
                id: id,
                partname: partname,
                partcode: partcode,
                minLot: minLot,
                maxPlan: maxPlan,
                noTeams: noTeams

            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire("Updated Successfully!", "", "success");
                    load_master();
                    $('#edit_masterlist').modal('hide');
                } else if (response == 'failed') {
                    Swal.fire("Update failed. Try again.", "", "error");
                } else {
                    Swal.fire("Something went wrong.", "", "error");
                }
            }
        });
    }
</script>