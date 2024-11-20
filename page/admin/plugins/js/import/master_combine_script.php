<script>
    $(document).ready(function() {
        $('#csvFileForm_combine').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var userName = $('#user_name').val();
            var fileInputBom = $('#csvFileInput_bom')[0].files[0];
            var fileInputBomAid = $('#csvFileInput_bomAid')[0].files[0];

            // Check if files are selected
            if (!fileInputBom || !fileInputBomAid) {
                alert("Please select both BOM and BOM Aid files to upload.");
                return;
            }

            // Check if files are CSV
            if (fileInputBom.type !== 'text/csv' || fileInputBomAid.type !== 'text/csv') {
                alert("Please select CSV files for both BOM and BOM Aid.");
                return;
            }

            // Append data to formData
            formData.append('userName', userName);
            formData.append('csvFile_bom', fileInputBom);
            formData.append('csvFile_bomAid', fileInputBomAid);

            Swal.fire({
                icon: 'info',
                title: 'Processing...',
                html: 'Please wait while we process your file.',
                text: "Please, do not refresh the page.",
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();

                    // Update after 30 seconds
                    setTimeout(() => {
                        Swal.update({
                            html: 'Still processing... Please be patient.',
                        });
                        Swal.showLoading();
                    }, 30000);

                    setTimeout(() => {
                        Swal.update({
                            html: 'You can do other task while waiting.',
                        });
                        Swal.showLoading();
                    }, 60000); //1 min

                    setTimeout(() => {
                        Swal.update({
                            html: 'Still processing... This might take a while.',
                        });
                        Swal.showLoading();
                    }, 120000); // 2 minutes

                    setTimeout(() => {
                        Swal.update({
                            html: 'Still processing...',
                        });
                        Swal.showLoading();
                    }, 160000); //  minutes
                }
            });
            $('#import_master_combine').modal('hide');
            $.ajax({
                url: '../../process/import/import_combine.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        if (response === 'success') {
                            Swal.fire({
                                icon: "success",
                                title: "Imported Successfully!",
                                showConfirmButton: true,
                                timer: 2000
                            });
                            Swal.close();
                            $('#import_master_combine').modal('hide');
                        } else if (response === 'error') {
                            Swal.fire({
                                icon: "error",
                                title: "There were errors during the upload.",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (response === 'no_matches') {
                            Swal.fire({
                                icon: "warning",
                                title: "No matching rows found!",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Swal.close();
                        } else if (response === 'file1 error') {
                            Swal.fire({
                                icon: "warning",
                                title: "Could not read file 1",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Swal.close();
                        } else if (response === 'file2 error') {
                            Swal.fire({
                                icon: "warning",
                                title: "Could not read file 2",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Swal.close();
                        } else if (response === 'file upload') {
                            Swal.fire({
                                icon: "warning",
                                title: "Failed uploaded files.",
                                text: "Please, check both files.",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Swal.close();
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Unexpected server response.');
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