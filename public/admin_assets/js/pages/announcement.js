
$(function() {
    $('#announcement-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": 'admin/announcements/get',
        "columns": [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
        ]
    });
});
