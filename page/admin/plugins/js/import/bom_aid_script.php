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
                html: 'Please wait while we process your file.<br><strong>0%</strong>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Start polling progress
            const progressInterval = setInterval(function() {
                $.getJSON('../../process/import/progress.json', function(data) {
                    if (data && data.progress) {
                        let progress = Math.min(100, Math.round(data.progress));
                        Swal.update({
                            html: `Please wait while we process your file.<br><strong>${progress}%</strong>`
                        });

                        if (progress >= 100) {
                            clearInterval(progressInterval);
                        }
                    }
                });
            }, 1000); // Poll every 1 second

            $.ajax({
                url: '../../process/import/import_bomAid.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    clearInterval(progressInterval);
                    if (response.includes('success')) {
                        Swal.fire({
                            icon: "success",
                            title: "Imported Successfully!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#import_master_combine').modal('hide');
                    } else if (response.includes('error')) {
                        Swal.fire({
                            icon: "error",
                            title: "There were errors during the upload.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else if (response.includes('no_matches')) {
                        Swal.fire({
                            icon: "warning",
                            title: "No matching rows found!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    clearInterval(progressInterval);
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
