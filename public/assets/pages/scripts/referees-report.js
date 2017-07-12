function sendData(formData, url)
{
    $.ajax({
        data: formData,
        dataType: 'json',
        type: 'POST',
        url: url,
        success: function (response) {
            if (!response.error)
            {
                for (var key in response)
                {
                    $.each(response[key], function (i, e) {
                        $('td[data-id="' + i + '"]')
                                .next('td').text(e.number)
                                .next('td').text(e.amount);
               
                    });

                }
            }
        }
        /*   error: function (data) {
         console.log('Error:', data);
         }*/
    });
}
var TableDatatablesButtons = function () {

 var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    };
    var initTable3 = function () {
        var table = $('#sample_3');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                },
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                }
            },
            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            buttons: [
                {extend: 'print', className: 'btn dark btn-outline'},
                {extend: 'copy', className: 'btn red btn-outline'},
                {extend: 'pdf', className: 'btn green btn-outline'},
                {extend: 'excel', className: 'btn yellow btn-outline '},
                {extend: 'csv', className: 'btn purple btn-outline '},
                {extend: 'colvis', className: 'btn dark btn-outline', text: 'Columns'}
            ],
            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: true,
            //"ordering": false, disable column ordering 
            //"paging": false, disable pagination

            "order": [
                [0, 'asc']
            ],
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            //"dom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });

        // handle datatable custom tools
        $('#sample_3_tools > li > a.tool-action').on('click', function () {
            var action = $(this).attr('data-action');
            oTable.DataTable().button(action).trigger();
        });
        $('#send_btn').on('click', function (e) {
            e.preventDefault();
            var dateFrom = $('#date_from').val();
            var dateUntil = $('#date_until').val();
             if ((dateUntil !=='' && dateFrom !=='') ||(dateUntil == '' && dateFrom == '') )
            {
                var url = $(this).data('href');
                var formData = {
                    date_from: dateFrom,
                    date_until: dateUntil
                };
                sendData(formData, url);
            } else {


                $('.alert-danger').css('display', 'block');

            }
         
        });
        $('#reload_btn').on('click', function (e)
        {
            e.preventDefault();
            $('#date_from').val('');
            $('#date_until').val('');
            var url = $(this).data('href');
            sendData(formData = {}, url);

        });
    };



    return {
        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }
            initPickers();
            initTable3();


        }

    };

}();

jQuery(document).ready(function () {
    TableDatatablesButtons.init();
});