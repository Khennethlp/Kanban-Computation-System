<script>
    const export_master_combine = () => {
        var search_key = $('#search_key').val();
        var search_by_month = $('#search_by_month').val();
        var search_by_year = $('#search_by_year').val();
        var search_by_carModel = $('#search_by_carModel').val();

        window.open('../../process/export/export_master_combine.php?search_key=' + search_key + '&month=' + search_by_month + '&year=' + search_by_year + '&carModel=' + search_by_carModel);
    }
</script>