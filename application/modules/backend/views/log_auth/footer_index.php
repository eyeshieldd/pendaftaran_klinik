<script>
    $(document).ready(function () {
        /* Set DataTable */
        dtgroup = $("#thistori").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/log_auth/get_list_auth_log'); ?>",
                "type": "POST"
            },
            "bFilter": true,
            "paging": false,
            "columns": [
                {"data": "no"},
                {"data": "user"},
                {"data": "waktu_login"},
                {"data": "ip"},
                {"data": "user_agent"},
                {"data": "keterangan"},
                {"data": "status"}
            ]
        });
    })
</script>