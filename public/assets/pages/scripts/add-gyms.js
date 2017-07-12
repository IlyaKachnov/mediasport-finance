var TableDatatablesEditable = function () {

    var handleTable = function () {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }
        function loadAjax(url)
        {
             $.get(url + '/load-gyms', function (data) {
                var gyms = data;
                 $('#gym_list').empty();
                            $.each(gyms, function (index, type) {
                            $('#gym_list').append($("<option></option>").attr('value', type.id).text(type.name));
                            });
                         
                    });
        }
        function editAjax(id, url)
        {
             $.get(url + '/' + id + '/edit-gyms', function (data) {
                var gyms = data.gyms;
                $('#gym_list').empty();
                            $.each(gyms, function (index, type) {
                            $('#gym_list').append($("<option></option>").attr('value', type.id).text(type.name));
                            });
                $('#gym_list').val(data.gym.id);
                         
                    });
        }
        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            dataId = (dataId === undefined) ? '' : dataId;
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Сохранить</a>';
            jqTds[0].innerHTML = '<select id="gym_list" class="form-control input-small"></select>';
            jqTds[1].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[1] + '">';
            jqTds[2].innerHTML = updateEditBtn;
            jqTds[3].innerHTML = '<a class="cancel" href="">Отмена</a>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var gym = $('#gym_list :selected').text();
            var keys = {};
            $('select', nRow).each(function () {
                value = $(this).find(':selected').val();
                index = $(this).attr('id');
                keys[index] = value;
            });
            var dataId = $('.edit', nRow).attr('data-id');
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            var updateDeleteBtn = '<a class="delete" data-id="' + dataId + '" href="">Удалить</a>';
            oTable.fnUpdate(gym, nRow, 0, false);
            oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
            oTable.fnUpdate(updateEditBtn, nRow, 2, false);
            oTable.fnUpdate(updateDeleteBtn, nRow, 3, false);
            oTable.fnDraw();
            return keys;
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            var gym = $('#gym_list :selected').text();
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            oTable.fnUpdate(gym, nRow, 0, false);
            oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
            oTable.fnUpdate(updateEditBtn, nRow, 2, false);
            oTable.fnDraw();
        }

        function saveAjax(formData, dataId, url)
        {
            var method;
            if (dataId)
            {
                method = 'PATCH';
                url += '/' + dataId;
            } else {
                method = 'POST';
                url += '/save-gyms';
            }

            $.ajax({
                data: formData,
                dataType: 'json',
                type: method,
                url: url,
                success: function (response) {
                    if (!response.error && method == 'POST')
                    {
                        $('tr').find('td [data-id =""]').attr('data-id', response.gym);
                    }

                },
                /*  error: function (data) {
                 console.log('Error:', data);
                 }*/
            });
        }
        function deleteAjax(dataId, url)
        {
            $.ajax({
                dataType: 'json',
                type: 'DELETE',
                url: url + '/' + dataId,
                /* success: function (data) {
                 console.log(data);
                 },
                 error: function (data) {
                 console.log('Error:', data);
                 }*/
            });
        }

        var table = $('#sample_editable_1');

        var oTable = table.dataTable({
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Все"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
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
            "columnDefs": [{// set default column settings
                    'orderable': true,
                    'targets': [0]
                }, {
                    "searchable": true,
                    "targets": [0]
                }],
            "order": [
                [0, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = $("#sample_editable_1_wrapper");
        var url = $('#sample_editable_1_new').attr('href');

        var nEditing = null;
        var nNew = false;
        $('#sample_editable_1_new').click(function (e) {
            e.preventDefault();
            loadAjax($(this).attr('href'));
            if (nNew && nEditing) {
                if (confirm("Previose row not saved. Do you want to save it ?")) {
                    saveRow(oTable, nEditing); // save
                    $(nEditing).find("td:first").html("Untitled");
                    nEditing = null;
                    nNew = false;

                } else {
                    oTable.fnDeleteRow(nEditing); // cancel
                    nEditing = null;
                    nNew = false;

                    return;
                }
            }

            var aiNew = oTable.fnAddData(['', '', '', '']);
            var nRow = oTable.fnGetNodes(aiNew[0]);
            editRow(oTable, nRow);
            nEditing = nRow;
            nNew = true;

        });

        table.on('click', '.delete', function (e) {
            e.preventDefault();
            var dataId = $(this).attr('data-id');
            if (confirm("Удалить строку?") == false) {
                return;
            }

            var nRow = $(this).parents('tr')[0];
            oTable.fnDeleteRow(nRow);
            deleteAjax(dataId, url);
        });

        table.on('click', '.cancel', function (e) {
            e.preventDefault();

            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {


            e.preventDefault();
            var id = $(this).attr('data-id');
            var url = $('#sample_editable_1_new').attr('href');
            if (id)
            {
                editAjax(id, url);
            }

            nNew = false;

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */

                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == "Сохранить") {

                /* Editing this row and want to save it */
                var keys = saveRow(oTable, nEditing);
                nEditing = null;
                var dataId = $(this).attr('data-id');


                var aData = oTable.fnGetData(nRow);

                var formData = {
                    gym: keys['gym_list'],
                    rent_price: aData[1]
                };

                saveAjax(formData, dataId, url);

            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }

        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();

jQuery(document).ready(function () {
    TableDatatablesEditable.init();
});