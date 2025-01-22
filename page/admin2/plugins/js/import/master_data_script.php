<script>
    $(document).ready(function() {
        $('#csvFileForms').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var user_name = $('#userName').val();
            var maxplan = $('#csvFileInput_maxplan')[0].files[0];
            var minlot = $('#csvFileInput_minlot')[0].files[0];
            var teams = $('#csvFileInput_teams')[0].files[0];
            var kanban = $('#csvFileInput_kanban')[0].files[0];

            // Check if all files are provided
            if (!maxplan || !minlot || !teams || !kanban) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing File',
                    text: 'Please upload all required CSV files.',
                });
                return;
            }

            Swal.fire({
                icon: 'info',
                title: 'Processing...',
                html: 'Please wait while we process your file.<br><span style="font-size: 16px;"><em>Do not refresh the page.</em></span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            formData.append('userName', user_name);
            formData.append('csvFile_maxplan', maxplan);
            formData.append('csvFile_minlot', minlot);
            formData.append('csvFile_teams', teams);
            formData.append('csvFile_kanban', kanban);

            $.ajax({
                url: '../../process/import/import_masters.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Split the response into status and message
                    var status = response.split(":")[0];
                    var message = response.split(":")[1];

                    if (status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: message,
                            showConfirmButton: false,
                            timer: 3000,
                        });
                        $('#import_minlot').modal('hide');
                        $('#csvFileForms')[0].reset(); // Reset form
                    } else if (status === 'error') {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: message,
                        });
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Unexpected Response",
                            text: "The server returned an unrecognized response.",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Upload Failed",
                        text: "An error occurred while uploading your file. Please try again later.",
                    });
                }
            });
        });
    });
</script>