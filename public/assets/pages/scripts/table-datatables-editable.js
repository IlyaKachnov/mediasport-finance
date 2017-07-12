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

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            dataId = (dataId === undefined) ? '' : dataId;
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Сохранить</a>';
            var addTeamBtn = '<a class="add-team" href="/leagues/'+ dataId + '/teams">Добавить</a>';
            var addGymBtn = '<a class="add-gyms" href="/leagues/'+ dataId + '/add-gyms">Добавить</a>';
            if (!dataId)
            {
                var addTeamBtn = 'Сначала сохраните';
                var addGymBtn = 'Сначала сохраните';
            }
            jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[3] + '">';
            jqTds[4].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[4] + '">';
            jqTds[5].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[5] + '">';
            jqTds[6].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[6] + '">';
            jqTds[7].innerHTML = updateEditBtn;
            jqTds[8].innerHTML = '<a class="cancel" href="">Отмена</a>';
            jqTds[9].innerHTML = addTeamBtn;
            jqTds[10].innerHTML = addGymBtn;
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            var updateDeleteBtn = '<a class="delete" data-id="' + dataId + '" href="">Удалить</a>';
            var addTeamBtn = '<a class="add-team" href="/leagues/'+ dataId + '/teams">Добавить</a>';
            var addGymBtn = '<a class="add-gyms" href="/leagues/'+ dataId + '/add-gyms">Добавить</a>';
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
            oTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
            oTable.fnUpdate(jqInputs[6].value, nRow, 6, false);
            oTable.fnUpdate(updateEditBtn, nRow, 7, false);
            oTable.fnUpdate(updateDeleteBtn, nRow, 8, false);
            oTable.fnUpdate(addTeamBtn, nRow, 9, false);
            oTable.fnUpdate(addGymBtn, nRow, 10, false);
            oTable.fnDraw();
         
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            var updateDeleteBtn = '<a class="delete" data-id="' + dataId + '" href="">Удалить</a>';
            //var addTeamBtn = '<a href="/leagues/'+ dataId + '/teams">Добавить</a>';
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
            oTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
            oTable.fnUpdate(jqInputs[6].value, nRow, 6, false);
            oTable.fnUpdate(updateEditBtn, nRow, 7, false);
            oTable.fnUpdate(updateDeleteBtn, nRow, 8, false);
           //oTable.fnUpdate(addTeamBtn, nRow, 9, false);
            oTable.fnDraw();
        }
        function saveAjax(formData, dataId, url)
        {
            var method = 'POST';
            var id;
            if (dataId)
            {
                method = 'PATCH';
                url += '/' + dataId;
            }
            $.ajax({
                data: formData,
                dataType: 'json',
                type: method,
                url: url,
                    success: function (response) {    
                      if(!response.error && method == 'POST')
                  {
                      //adding ids in new row                      
                      $('tr').find('td [data-id =""]').attr('data-id',response.id);
                      $('tr').find('td [data-id ="'+response.id+'"]').closest('tr').find('.add-team').attr('href','/leagues/'+response.id+'/teams');
                      $('tr').find('td [data-id ="'+response.id+'"]').closest('tr').find('.add-gyms').attr('href','/leagues/'+response.id+'/add-gyms');
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
                  //console.log(data);
                },
                error: function (data) {
                    if(data.error)console.log('Error:', data);
                }*/
            });
        }
        function validateData(oTable, nRow)
        {
           var check = $('input:text',nRow).filter(function() { return $(this).val() == ""; });
           var error = false;
            check.each(function(){
                $(this).css('border-color','#e73d4a');
                error = true;
            });
       return error;
        }
        var table = $('#sample_editable_1');

        var oTable = table.dataTable({
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

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

            var aiNew = oTable.fnAddData(['', '', '', '', '', '', '', '', '', '','']);
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
            nNew = false;
  
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
           
            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */

                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == "Сохранить") {
                table.on('click', '.cancel', function (e) {
                    e.preventDefault();
                    oTable.fnDeleteRow(nRow);
                });
                  if (!validateData(oTable, nEditing))
                  {
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    var dataId = $(this).attr('data-id');
                    var aData = oTable.fnGetData(nRow);
                    var formData = {
                        name: aData[0],
                        total_fees: aData[1],
                        referee_cost: aData[2],
                        photo_cost: aData[3],
                        video_cost: aData[4],
                        edit_cost: aData[5],
                        doctor_cost: aData[6]
                    };
                   saveAjax(formData, dataId, url);
                      //return;
                  }
               else{
                   $('.alert-danger').css('display','block');
             

               }
               
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