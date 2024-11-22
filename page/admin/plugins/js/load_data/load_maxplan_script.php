<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_maxplan();

    });

    let page = 1;
    const rowsPerPage = 100;
    let isLoading = false; // Flag to track if data is being loaded
    let hasMoreData = true; // Flag to track if there's more data to load
    let debounceTimeout = null; // Timeout for debounce

    const load_maxplan = (isPagination = false) => {
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

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_maxplan.php",
            data: {
                method: 'load_maxplan',
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                // $('#maxplan_table').html(response);
                const responseData = JSON.parse(response);
                Swal.close();
                maxplan_counts();

                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('maxplan_table').innerHTML += responseData.html;
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
                    document.getElementById('maxplan_table').innerHTML = responseData.html;
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

    document.getElementById('load_more').addEventListener('click', () => load_maxplan(true));

    $('#maxplan_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_maxplan(true);
            }
        }, 100);
    });

    const maxplan_counts = () => {

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_maxplan.php",
            data: {
                method: 'maxplan_counts',
            },
            success: function(response) {
                const new_count =parseInt(response).toLocaleString();
                $('#maxplan_count_per_load').html(new_count);
            }
        });
    }
</script>