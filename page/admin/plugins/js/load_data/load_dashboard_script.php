<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_dashboard();
    });

    let page = 1;
    const rowsPerPage = 10;
    let isLoading = false; // Flag to track if data is being loaded
    let hasMoreData = true; // Flag to track if there's more data to load
    let debounceTimeout = null; // Timeout for debounce

    const load_dashboard = (isPagination = false) => {
        if (isLoading || !hasMoreData) return; // Avoid loading if already loading or no more data

        isLoading = true; // Set loading flag

        const line_no = document.getElementById('line_no').value;
        const getDate = document.getElementById('getDate').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'load_dashboard',
                line_no: line_no,
                search_date: getDate,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);

                if (responseData.html.trim() !== '') {
                    // Append new data to the table
                    document.getElementById('table_dashboard').innerHTML += responseData.html;
                    page++; // Increment page number for the next request

                    // Check if there is more data to load
                    hasMoreData = responseData.has_more;
                    count_dash();
                    // Hide the "Load More" button if no more data
                    document.getElementById('load_more').style.display = hasMoreData ? 'block' : 'none';
                } else {
                    // No more data to load
                    hasMoreData = false;
                    document.getElementById('load_more').style.display = 'none';
                }

                isLoading = false; // Reset loading flag
            },
            error: function() {
                isLoading = false; // Reset loading flag on error
            }
        });
    }

    // Manual 'Load More' button
    document.getElementById('load_more').addEventListener('click', () => load_dashboard(true));

    // Scroll event with debounce to trigger load more when scrolling near the bottom of container
    $('#tbl_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_dashboard(true); // Load more data when scrolling near bottom
            }
        }, 200); // Debounce delay in milliseconds
    });


    const count_dash = () => {
        var line_no = document.getElementById('line_no').value;
        var getDate = document.getElementById('getDate').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/dash_computation.php",
            data: {
                method: 'count_dash',
                line_no: line_no,
                search_date: getDate
            },
            success: function(response) {
                $('#dash_count').html(response);
            }
        });
    }

    const export_dashboard = () => {
        var line_no = document.getElementById('line_no').value;
        var date = document.getElementById('getDate').value;

        window.open('../../process/export/export_dash.php?line_no=' + line_no + '&date=' + date, '_blank');
    }
</script>