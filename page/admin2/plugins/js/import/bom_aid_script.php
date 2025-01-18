<script>
    $(document).ready(function() {
        $('#csvFileForm_bomAid').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var userName = $('#user_name').val();
            var fileInputBomAid = $('#csvFileInput_bomAid')[0].files[0];

            if (!fileInputBomAid) {
                alert("Please select a file to upload.");
                return;
            }

            formData.append('userName', userName);
            formData.append('csvFile_bomAid', fileInputBomAid);

            Swal.fire({
                icon: 'info',
                title: 'Processing...',
                html: 'Please wait while we process your file.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '../../process/import/import_bomAid.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var result = JSON.parse(response);

                    if (result.status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: `Imported Successfully! ${result.rowsInserted} rows inserted.`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#bom_aid').modal('hide');
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error during the import.",
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error uploading file.",
                        showConfirmButton: true
                    });
                }
            });
        });
    });
</script>
