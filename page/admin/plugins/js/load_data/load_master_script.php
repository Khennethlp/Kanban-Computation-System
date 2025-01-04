<script>
    document.addEventListener("DOMContentLoaded", function() {
        // load_master();

    });

    document.querySelectorAll('#search_key, #search_by_month').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_master();
            }
        });
        // input.addEventListener("input", () => {
        //     load_dashboard();
        // });
    });

    const countDisplayedRows = () => {
        const rowCount = document.querySelectorAll('#import_table tbody tr').length;
        console.log(`Rows currently in the table: ${rowCount}`);
        // const formattedResponse = parseInt(rowCount).toLocaleString();
        return rowCount;
        console.log(rowCount);
    };
    // document.getElementById('count_masters').innerHTML = countDisplayedRows();

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

        var user_name = $('#user_name').val();
        var search_key = $('#search_key').val();
        // var search_date = $('#search_date').val();
        var search_by_month = $('#search_by_month').val();
        var search_by_year = $('#search_by_year').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_master.php",
            cache: false, // Prevent caching
            data: {
                method: 'load_master',
                user_name: user_name,
                search_key: search_key,
                // search_date: search_date,
                search_by_month: search_by_month,
                search_by_year: search_by_year,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                const new_count = parseInt(responseData.total).toLocaleString();
                
                document.getElementById('count_master').innerHTML = 'Results: ' + new_count;
                Swal.close();
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
        }, 100);
    });


    // const count_master = () => {
    //     var search_key = document.getElementById('search_key').value;
    //     var getDate = document.getElementById('search_date').value;

    //     $.ajax({
    //         type: "POST",
    //         url: "../../process/admin/masterlist/load_master.php",
    //         data: {
    //             method: 'count_master',
    //             search_key: search_key,
    //             search_date: getDate
    //         },
    //         success: function(response) {
    //             $('#count_master').html(response);
    //         }
    //     });
    // }

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
        $product_no = $data[9];
        $added_by = $data[10];

        console.log("id: " + $id);
        console.log("line_no: " + $line_no);
        console.log("partcode: " + $partcode);
        console.log("partname: " + $partname);
        console.log("min_lot: " + $min_lot);
        console.log("max_usage: " + $max_usage);
        console.log("max_plan: " + $max_plan);
        console.log("no_teams: " + $no_teams);
        console.log("issued_to_pd: " + $issued_to_pd);
        console.log("product_no: " + $product_no);
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
        $('#product_no').val($product_no);

    }

    const update_master = () => {
        var id = $('#id_master').val();
        var line_no = $('#edit_lineNo').val();
        var partname = $('#edit_partname').val();
        var partcode = $('#edit_partcode').val();
        var minLot = $('#edit_minLot').val();
        var maxPlan = $('#edit_maxPlan').val();
        var maxUsage = $('#edit_maxUsage').val();
        var noTeams = $('#edit_noTeams').val();
        var product_no = $('#product_no').val();
        var issued_pd = $('#edit_issued').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/functions.php",
            data: {
                method: 'update_master',
                id: id,
                line_no: line_no,
                partname: partname,
                partcode: partcode,
                minLot: minLot,
                maxPlan: maxPlan,
                maxUsage: maxUsage,
                noTeams: noTeams,
                issued_pd: issued_pd,
                product_no: product_no

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

    const export_master = () => {
        var search_key = $('#search_key').val();
        var search_by_month = $('#search_by_month').val();

        window.open('../../process/export/export_master.php?search_key=' + search_key + '&month=' + search_by_month, '_blank');
    }
</script>