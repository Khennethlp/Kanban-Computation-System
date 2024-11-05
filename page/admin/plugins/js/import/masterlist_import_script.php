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
</script>