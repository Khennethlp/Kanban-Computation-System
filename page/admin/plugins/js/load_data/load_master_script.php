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


        console.log("id: " +$id);
        console.log("line_no: " +$line_no);
        console.log("partcode: " +$partcode);
        console.log("partname: " +$partname);
        console.log("min_lot: " +$min_lot);
        console.log("max_usage: " +$max_usage);
        console.log("max_plan: " +$max_plan);
        console.log("no_teams: " +$no_teams);
        console.log("issued_to_pd: " +$issued_to_pd);
        console.log("added_by: " +$added_by);

        $('#id_master').val($id);
        $('#edit_lotNo').val($line_no);
        $('#edit_partname').val($partcode);
        $('#edit_partcode').val($partname);
        $('#edit_minLot').val($min_lot);
        $('#edit_maxUsage').val($max_usage);
        $('#edit_maxPlan').val($max_plan);
        $('#edit_issued').val($issued_to_pd);
        $('#edit_noTeams').val($no_teams);

    }
</script>
