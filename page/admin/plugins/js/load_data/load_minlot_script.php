<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_minlot();
    });

    let page = 1;
    const rowsPerPage = 100;
    let isLoading = false;
    let hasMoreData = true;
    let debounceTimeout = null;

    const load_minlot = (isPagination = false) => {
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

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_minlot.php",
            data: {
                method: 'load_minlot',
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                // $('#minlot_table').html(response);
                const responseData = JSON.parse(response);
                Swal.close();
                minlot_counts();

                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('minlot_table').innerHTML += responseData.html;
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
                    document.getElementById('minlot_table').innerHTML = responseData.html;
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

    document.getElementById('load_more').addEventListener('click', () => load_minlot(true));

    $('#minlot_container').on('scroll', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const $this = $(this);
            if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight - 100) {
                load_minlot(true);
            }
        }, 100);
    });

    const minlot_counts = () => {

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_minlot.php",
            data: {
                method: 'minlot_counts',
            },
            success: function(response) {
                const new_count = parseInt(response).toLocaleString();
                $('#minlot_count_per_load').html(new_count);
            }
        });
    }
</script>