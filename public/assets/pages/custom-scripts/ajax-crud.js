/**
 * 
 * deleting from index tables
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('.delete-item').click(function () {
    $(this).on('confirmed.bs.confirmation', function ()
    {
        var id = $(this).data('id');
        var token = $(this).data('token');
        var url = $(this).data('href');
        $.ajax({
            type: "DELETE",
            url: url,
            data: {_token: token},
            success: function (data) {
                console.log(data);

                $("a[data-id='" + id + "']").closest('tr').remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

    });
    $(this).on('canceled.bs.confirmation', function () {
        return;
    });

});
