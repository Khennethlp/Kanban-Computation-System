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
            console.log(userName);

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

            $('#import_master_combine').modal('hide');

            $.ajax({
                url: '../../process/import/import_new_combine.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let icon, title, text;

                    try {
                        if (response === 'success') {
                            icon = "success";
                            title = "Combined Successfully!";
                            text = "The files have been combined successfully.";
                        } else if (response === 'error') {
                            icon = "error";
                            title = "Error!";
                            text = "There was an error processing the files.";
                        } else if (response === 'no_matches') {
                            icon = "warning";
                            title = "No Matches Found!";
                            text = "No matching rows found in the files.";

                        } else if (response === 'file1 error') {
                            icon = "warning";
                            title = "Could not read file 1";
                            text = "Please check the file format and try again.";

                        } else if (response === 'file2 error') {
                            icon = "warning";
                            title = "Could not read file 2";
                            text = "Please check the file format and try again.";
                        } else if (response === 'file upload') {
                            icon = "warning";
                            title = "File Upload Error!";
                            text = "Please check the file format and try again.";
                        }

                        Swal.close();

                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: text,
                            showConfirmButton: false,
                            timer: 3000
                        });

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