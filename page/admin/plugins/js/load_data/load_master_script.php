<script>
    document.addEventListener("DOMContentLoaded", function() {
        // load_master();

    });

    document.querySelectorAll('#search_key', '#search_date').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_master();
            }
        });
    });

    const rowsPerPage = 100;
    let page = 1;
    let debounceTimeout = null;
    let isLoading = false;
    let hasMoreData = true;

    const load_master = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
            hasMoreData = true;
        }

        if (isLoading || !hasMoreData) return;

        isLoading = true;

        var user_name = $('#user_name').val();
        var search_key = $('#search_key').val();
        var search_date = $('#search_date').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_master.php",
            cache: false, // Prevent caching
            data: {
                method: 'load_master',
                user_name: user_name,
                search_key: search_key,
                search_date: search_date,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                count_master();

                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('import_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('load_more').style.display = 'block';
                        } else {
                            document.getElementById('load_more').style.display = 'none';
                            hasMoreData = false;
                        }
                    } else {
                        document.getElementById('load_more').style.display = 'none';
                        hasMoreData = false;
                    }
                } else {
                    document.getElementById('import_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('load_more').style.display = 'block';
                    } else {
                        document.getElementById('load_more').style.display = 'none';
                        hasMoreData = false;
                    }
                }

                isLoading = false;
            }
        });
    }

    document.getElementById('load_more').addEventListener('click', () => load_master(true));

    $('#tbl_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_master(true);
            }
        }, 200);
    });


    const count_master = () => {
        var search_key = document.getElementById('search_key').value;
        var getDate = document.getElementById('search_date').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_master.php",
            data: {
                method: 'count_master',
                search_key: search_key,
                search_date: getDate
            },
            success: function(response) {
                $('#count_master').html(response);
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