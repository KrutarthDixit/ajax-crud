$(document).ready(function () {

    // Header Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // user show
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "user",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'gender', name: 'gender' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // User create modal
    $(document).on('click', '#add-user', function (event) {
        event.preventDefault();
        $('#modal-title').text('Create new user');
        $('#store-form').show();
        $('#destroy-form').hide();
        $('#user-modal').modal('show');
    });

});
