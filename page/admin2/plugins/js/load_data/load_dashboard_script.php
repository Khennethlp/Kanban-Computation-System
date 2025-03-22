<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_dashboard();
    });

    document.querySelectorAll('#search_key, #search_by_month, .line_no').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_dashboard();
            }
        });
        // input.addEventListener("input", () => {
        //     load_dashboard();
        // });
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
        var search_by_month = document.getElementById('search_by_month').value;
        var search_by_year = document.getElementById('search_by_year').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'load_dashboard',
                line_no: line_no,
                search_key: search_key,
                search_by_month: search_by_month,
                search_by_year: search_by_year,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                Swal.close();
                const responseData = JSON.parse(response);
                const new_count = parseInt(responseData.total).toLocaleString();
                const err_msg = responseData.err_msg;

                if (err_msg) {
                    document.getElementById('err_msg').innerHTML = err_msg;
                    document.querySelector('.err_msg_container').style.display = 'block';
                } else {
                    document.querySelector('.err_msg_container').style.display = 'none';
                }

                document.getElementById('dash_count').innerHTML = 'Results: ' + new_count;

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

    const export_dashboard = () => {
        var line_no = document.getElementsByClassName('line_no')[0].value;
        var search_key = document.getElementById('search_key').value;
        var month = document.getElementById('search_by_month').value;

        window.open('../../process/export/export_dash.php?search_key=' + search_key + '&line_no=' + line_no + '&month=' + month, '_blank');
    }
</script>