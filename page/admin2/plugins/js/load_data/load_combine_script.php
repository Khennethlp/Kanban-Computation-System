<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_combined();
    });

    document.querySelectorAll('#search_key, #search_date, #search_by_carModel').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                load_combined();
            }
        });
        // input.addEventListener("input", () => {
        //     load_combined();
        // });
    });

    const countDisplayedRows = () => {
        // Count the rows in the table body (or entire table depending on your structure)
        const rowCount = document.querySelectorAll('#combine_table tr').length;
        console.log(`Rows currently in the table: ${rowCount}`);
        const formattedResponse = parseInt(rowCount).toLocaleString();
        return formattedResponse;
    };

    const rowsPerPage = 100;
    let page = 1;
    let debounceTimeout = null;
    let isLoading = false;
    let hasMoreData = true;

    const load_combined = (isPagination = false) => {
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
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        var user_name = $('#user_name').val();
        var search_key = $('#search_key').val();
        var car_model = $('#search_by_carModel').val();
        var search_by_month = $('#search_by_month').val();
        var search_by_year = $('#search_by_year').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_combine.php",
            data: {
                method: 'load_combine',
                user_name: user_name,
                search_key: search_key,
                search_by_month: search_by_month,
                search_by_year: search_by_year,
                car_model: car_model,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                // $('#combine_table').html(response);
                const responseData = JSON.parse(response);
                const new_count = parseInt(responseData.total).toLocaleString();
                // counter();
                Swal.close();
                document.getElementById('counts').innerHTML =  new_count;
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('combine_table').innerHTML += responseData.html;
                        document.getElementById('count_per_load').innerHTML = countDisplayedRows() + ' out of ';
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
                    document.getElementById('combine_table').innerHTML = responseData.html;
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
    
    document.getElementById('load_more').addEventListener('click', () => load_combined(true), countDisplayedRows());
    
    $('#combine_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_combined(true);
            }
        }, 100);
    });

</script>