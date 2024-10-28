<script>
    $(document).ready(function() {
        $('#csvFileForm').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            var fileInput = $('#csvFileInput')[0].files[0];

            if (!fileInput) {
                alert("Please select a CSV file to upload.");
                return;
            }

            formData.append('csvFile', fileInput);

            $.ajax({
                url: '../../process/import/file_upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#import_masterlist').modal('hide');
                    $('#import_table').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error uploading file.');
                }
            });
        });
    });
</script>