<script>
    $(document).ready(function() {
        load_master();

        $('#csvFileForm').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var user_name = $('#user_name').val();
            var fileInput = $('#csvFileInput')[0].files[0];

            if (!fileInput) {
                alert("Please select a CSV file to upload.");
                return;
            }

            formData.append('userName', user_name);
            formData.append('csvFile', fileInput);

            $.ajax({
                url: '../../process/import/file_upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.includes('success')) {
                        // alert('Upload successful!');
                        // Optionally reload the master list or update the UI
                        Swal.fire({
                            icon: "success",
                            title: "Imported Successfully!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        load_master();
                    } else if (response.includes('error')) {
                        // alert('There were errors during the upload. Details: ' + response);
                        Swal.fire({
                            icon: "error",
                            title: "There were errors during the upload.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // alert('Upload completed with some existing records. Details: ' + response);
                        Swal.fire({
                            icon: "warning",
                            title: "Some data already exist!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                    $('#import_masterlist').modal('hide');
                    // $('#import_table').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error uploading file.');
                }
            });
        });
    });

    const generateRecords = () => {
        var user_name = document.getElementById('user_name').value;

        Swal.fire({
            icon: 'info',
            title: 'Generating records...',
            html: 'Please wait while we process your file.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: '../../process/import/generate_record.php',
            data: {
                method: "generate_records",
                userName: user_name
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Generated Successfully!",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#import_table').html(response);
                    // Swal.close();
                } else if (response == 'failed') {
                    Swal.fire({
                        icon: "info",
                        title: "Failed to Generate Records.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (response == 'No matching records found.') {
                    Swal.fire({
                        icon: "info",
                        title: "No matching records found.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>