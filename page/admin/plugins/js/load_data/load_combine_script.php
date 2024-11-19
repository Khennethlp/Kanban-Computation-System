<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_combined();
    });

    const countDisplayedRows = () => {
        // Count the rows in the table body (or entire table depending on your structure)
        const rowCount = document.querySelectorAll('#combine_table tr').length;
        console.log(`Rows currently in the table: ${rowCount}`);
        return rowCount;
    };

    let page = 1;
    const rowsPerPage = 100;
    const load_combined = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
        }

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
        var search_date = $('#search_date').val();
        var car_model = $('#search_by_carModel').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_combine.php",
            data: {
                method: 'load_combine',
                user_name: user_name,
                search_key: search_key,
                search_date: search_date,
                car_model: car_model,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                // $('#combine_table').html(response);
                const responseData = JSON.parse(response);
                counter();
                Swal.close();
                
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('combine_table').innerHTML += responseData.html;
                        document.getElementById('count_per_load').innerHTML = countDisplayedRows() + ' out of ';
                        page++;
                        
                        if (responseData.has_more) {
                            document.getElementById('load_more').style.display = 'block';
                        } else {
                            document.getElementById('load_more').style.display = 'none';

                        }
                    } else {
                        document.getElementById('load_more').style.display = 'none';

                    }
                } else {
                    document.getElementById('combine_table').innerHTML = responseData.html;
                    page++;
                   
                    if (responseData.has_more) {
                        document.getElementById('load_more').style.display = 'block';
                    } else {
                        document.getElementById('load_more').style.display = 'none';

                    }
                }
            }
        });
    }
    document.getElementById('load_more').addEventListener('click', () => load_combined(true), countDisplayedRows());
  
    const counter = () => {
        var search_key = $('#search_key').val();
        var search_date = $('#search_date').val();
        var car_model = $('#search_by_carModel').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/masterlist/load_combine.php",
            data: {
                method: 'count_combine',
                search_key: search_key,
                search_date: search_date,
                car_model: car_model,
            },
            success: function(response) {
                const formattedResponse = parseInt(response).toLocaleString();
                $('#counts').html(formattedResponse);
               
            }
        });
    }
</script>