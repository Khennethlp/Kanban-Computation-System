<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_dashboard();
    });

    let page = 1;
    const rowsPerPage = 100;
    let isLoading = false;
    let hasMoreData = true;
    let debounceTimeout = null;

    const load_dashboard = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
            hasMoreData = true;
        }

        if (isLoading || !hasMoreData) return; // Avoid loading if already loading or no more data
        isLoading = true; // Set loading flag

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

        var line_no = document.getElementsByClassName('line_no')[0].value;
        var search_key = document.getElementById('search_key').value;
        // var getDate = document.getElementById('getDate').value;
        var search_by_month = document.getElementById('search_by_month').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'load_dashboard',
                line_no: line_no,
                // search_date: getDate,
                search_key: search_key,
                search_by_month: search_by_month,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                const new_count = parseInt(responseData.total).toLocaleString();
                
                document.getElementById('dash_count').innerHTML = 'Results: ' + new_count;
                Swal.close();
                
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('table_dashboard').innerHTML += responseData.html;

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
                    document.getElementById('table_dashboard').innerHTML = responseData.html;
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

    document.getElementById('load_more').addEventListener('click', () => load_dashboard(true));

    $('#tbl_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_dashboard(true);
            }
        }, 200);
    });


    // const count_dash = () => {
    //     var line_no = document.getElementById('line_no').value;
    //     var getDate = document.getElementById('getDate').value;

    //     $.ajax({
    //         type: "POST",
    //         url: "../../process/admin/dash_computation.php",
    //         data: {
    //             method: 'count_dash',
    //             line_no: line_no,
    //             search_date: getDate
    //         },
    //         success: function(response) {
    //             $('#dash_count').html(response);
    //         }
    //     });
    // }

    const export_dashboard = () => {
        var line_no = document.getElementsByClassName('line_no')[0].value;
        var search_key = document.getElementById('search_key').value;
        var month = document.getElementById('search_by_month').value;

        window.open('../../process/export/export_dash.php?search_key='+search_key+'&line_no=' + line_no + '&month=' + month, '_blank');
    }
</script>