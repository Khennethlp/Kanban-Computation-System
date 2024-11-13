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

            // Append data to formData
            formData.append('userName', userName);
            formData.append('csvFile_bom', fileInputBom);
            formData.append('csvFile_bomAid', fileInputBomAid);

            $.ajax({
                url: '../../process/import/import_combine.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {

                        if (response.includes === 'success') {
                            Swal.fire({
                                icon: "success",
                                title: "Imported Successfully!",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $('#import_master_combine').modal('hide');
                        } else if (response.includes === 'error') {
                            Swal.fire({
                                icon: "error",
                                title: "There were errors during the upload.",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (response.includes === 'no_matches') {
                            Swal.fire({
                                icon: "warning",
                                title: "No matching rows found!",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
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
