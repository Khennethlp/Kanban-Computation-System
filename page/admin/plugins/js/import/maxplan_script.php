<script>
    $(document).ready(function() {

        $('#csvFileForm_maxplan').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var user_name = $('#user_name').val();
            var fileInput = $('#csvFileInput_maxplan')[0].files[0];

            if (!fileInput) {
                alert("Please select a CSV file to upload.");
                return;
            }

            Swal.fire({
                icon: 'info',
                title: 'Processing...',
                html: 'Please wait while we process your file.<br><span style="font-size: 16px;"><em>Please, do not refresh the page.</em></span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            formData.append('userName', user_name);
            formData.append('csvFile_maxplan', fileInput);
            console.log(user_name);

            $('#import_masters').modal('hide');
            $('#import_maxplan').modal('hide');
            
            $.ajax({
                url: '../../process/import/import_maxplan.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.includes('success')) {
                        // alert('Upload successful!');
                        Swal.fire({
                            icon: "success",
                            title: "Uploaded Successfully!",
                            showConfirmButton: true,
                            // timer: 2000
                        });
                     
                    } else if (response.includes('error')) {
                        Swal.fire({
                            icon: "error",
                            title: "There were errors during the upload.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Oops! Something went wrong.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                    
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error uploading file.');
                }
            });
        });
    });
</script>