<script>
    $(document).ready(function() {
        // Handle form submission
        $('#csvFileForm').on('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting traditionally

            var formData = new FormData();
            var fileInput = $('#csvFileInput')[0].files[0]; // Get the uploaded file

            if (!fileInput) {
                alert("Please select a CSV file to upload.");
                return;
            }

            formData.append('csvFile', fileInput); // Append file to FormData object

            $.ajax({
                url: '../../process/import/file_upload.php', // URL of PHP file to handle upload
                type: 'POST',
                data: formData,
                processData: false, // Do not process data
                contentType: false, // Do not set content-type
                success: function(response) {
                    $('#import_table').html(response); // Load the returned table into the div
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error uploading file.');
                }
            });
        });
    });
</script>