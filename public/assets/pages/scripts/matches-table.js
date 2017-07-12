var TableDatatablesEditable = function () {
    $('#main-date').datepicker({
        rtl: App.isRTL(),
        autoclose: true
    });
    var handleTable = function () {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function loadAjax(gymId)
        {
             $.get('/gyms/' + gymId + '/matches/create', function (data) {
                var leagues = data.leagues;
                var methods = data.allMethods;
                var referees = data.referees;
                var rent = data.rent;
                $('.is_number').attr('placeholder', rent);
                $('.payments').empty();
                $('#referees').empty();
                $('#fields').empty();
                            $.each(leagues, function (index, item) {
                            $('#leagues').append($("<option></option>").attr('value', item.id).text(item.name));
                            });
                $.each(methods, function (index, method) {
                            $('.payments').append($("<option></option>").attr('value', method.id).text(method.name));
                    $('.payments-o').append($("<option></option>").attr('value', method.id).text(method.name));
                            });
                $.each(referees, function (index, referee) {
                            $('#referees').append($("<option></option>").attr('value', referee.id).text(referee.lastname));
                            });

                    });


                   
        }
        function editAjax(url, id)
        {
             $.get('/gyms/matches/' + id + '/edit', function (data) {

                var homeTeams = data.homeTeams;
                var guestTeams = data.guestTeams;
                var methods = data.allMethods;
                var referees = data.referees;
                var leagues = data.leagues;
                var match = data.match;
                $('.payments').empty();
                $('#leagues').empty();
                $.each(leagues, function (index, league) {
                            $('#leagues').append($("<option></option>").attr('value', league.id).text(league.name));
                            });
                $('#referees').empty();
                $.each(referees, function (index, referee) {
                            $('#referees').append($("<option></option>").attr('value', referee.id).text(referee.lastname));
                            });
                            $.each(homeTeams, function (index, team) {
                            $('#home_teams').append($("<option></option>").attr('value', team.id).text(team.name));
                            });
                                $.each(guestTeams, function (index, team) {
                            $('#guest_teams').append($("<option></option>").attr('value', team.id).text(team.name));
                            });
                $.each(methods, function (index, method) {
                            $('.payments').append($("<option></option>").attr('value', method.id).text(method.name));
                    $('.payments-o').append($("<option></option>").attr('value', method.id).text(method.name));
                            });
                $('#home_teams').val(match.home_id);
                $('#guest_teams').val(match.guest_id);
                $('#referees').val(match.referee_id);
                $('#leagues').val(match.league_id);
                $('#payment_types_home').val(match.home_method_id);
                $('#payment_types_guest').val(match.guest_method_id);

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
        function changeOnLeague(leagueId)
        {
            $.ajax({
                type: 'POST',
                url: '/home-select',
                data: {league_id: leagueId},
                success: function (data) {
                    var teams = data;   
                    $('#home_teams').empty();
                    $.each(teams, function (index, team) {
                                $('#home_teams').append($("<option></option>").attr('value', team.id).text(team.name));
                                });
                    $('#home_teams').prepend($('<option disabled selected value="0">Выбрать</option>'));
                },
            });
        }
        function changeAjax(leagueId, homeId) {
            $.ajax({
                type: 'POST',
                url: '/ajax-select',
                data: {home_id: homeId, league_id: leagueId},
                success: function (data) {
                    var teams = data;   
                    $('#guest_teams').empty();
                    $.each(teams, function (index, team) {
                                $('#guest_teams').append($("<option></option>").attr('value', team.id).text(team.name));
                                });
                },
            });
        }
//        function  changeEditAjax(url, homeId, guestId) {
//            $.ajax({
//                type: 'POST',
//                url: url + '/ajax-edit',
//                data: {home_id: homeId},
//                success: function (data) {
//                    var teams = data;   
//                    $('#guest_teams').empty();
//                    $.each(teams, function (index, team) {
//                                $('#guest_teams').append($("<option></option>").attr('value', team.id).text(team.name));
//                                });
//                },
//            });
//        }
        function checkBalance(teamId)
        {
            var balance = null;
             $.ajax({
                type: 'GET',
                async: false,
                url: '/teams/' + teamId + '/check-balance',
                success: function (response)
                {
                    balance = response.balance;
                },
            });
            return balance;
        }
        function validateData(nRow)
        {
            var check = $('input.is_required', nRow).filter(function () {
                return $(this).val() == "";
            });
            var errors = false;
            $('.help-block', nRow).remove();
            $('input:text', nRow).removeClass('error-input');
            check.each(function () {
                $(this).addClass('error-input');
                errors = true;
            });
            if (!errors)
            {
                var homeFee = $('#home_fee').val();
                var homeId = $('#home_teams').val();
                var guestFee = $('#guest_fee').val();
                var guestId = $('#guest_teams').val();
                if ($('#payment_types_home').val() == 4)
                {
                    if (checkBalance(homeId) - homeFee < 0)
                    {
                        $('#home_fee').after('<span class="help-block" style="color:#e73d4a;">На счету не хватает средств!</span>');
                        errors = true;
                    }
                }
                if ($('#payment_types_guest').val() == 4)
                {
                    if (checkBalance(guestId) - guestFee < 0)
                    {
                        $('#guest_fee').after('<span class="help-block" style="color:#e73d4a;">На счету не хватает средств!</span>');
                        errors = true;
                    }
                }
            }


            return errors;
        }
        function editRow(oTable, nRow) {
            //class payments for required selects 
            //class payments-o for optional 
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            dataId = (dataId === undefined) ? '' : dataId;
            jqTds[0].innerHTML = '<select id="leagues" class="form-control input-small"><option disabled selected value="0">Выбрать</option></select>';
            jqTds[1].innerHTML = '<select id="home_teams" class="form-control input-small teams"><option disabled selected value="0">Выбрать</option></select>';
            jqTds[2].innerHTML = '<input id="home_fee" type="text" class="form-control input-small is_required is_number" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<select id="payment_types_home" class="payments form-control input-small"></select>';
            jqTds[4].innerHTML = '<select id="guest_teams"  class="form-control input-small teams"><option disabled selected value="0">Выбрать</option></select>';
            jqTds[5].innerHTML = '<input id="guest_fee" type="text" class="form-control input-small is_required is_number" value="' + aData[5] + '">';
            jqTds[6].innerHTML = '<select id="payment_types_guest" class="payments form-control input-small"></select>';
            jqTds[7].innerHTML = '<select id="referees" class="form-control input-small"></select>';
            jqTds[8].innerHTML = '<a class="edit" data-id="' + dataId + '" href="">Сохранить</a>';
            jqTds[9].innerHTML = '<a class="cancel" href="">Отменить</a>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            var league = $('#leagues :selected').text();
            var home_team = $("#home_teams :selected").text();
            var guest_team = $("#guest_teams :selected").text();
            var payment_type_home = $("#payment_types_home :selected").text();
            var payment_type_guest = $("#payment_types_guest :selected").text();
            var referee = $("#referees :selected").text();
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            var updateDeleteBtn = '<a class="delete" data-id="' + dataId + '" href="">Удалить</a>';
            var keys = {};
            $('select', nRow).each(function () {
                value = $(this).find(':selected').val();
                index = $(this).attr('id');
                keys[index] = value;
            });
            oTable.fnUpdate(league, nRow, 0, false);
            oTable.fnUpdate(home_team, nRow, 1, false);
            oTable.fnUpdate(jqInputs[0].value, nRow, 2, false);
            oTable.fnUpdate(payment_type_home, nRow, 3, false);
            oTable.fnUpdate(guest_team, nRow, 4, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 5, false);
            oTable.fnUpdate(payment_type_guest, nRow, 6, false);
            oTable.fnUpdate(referee, nRow, 7, false);
            oTable.fnUpdate(updateEditBtn, nRow, 8, false);
            oTable.fnUpdate(updateDeleteBtn, nRow, 9, false);
            oTable.fnDraw();
            return keys;
        }

        function saveAjax(formData, dataId, url)
        {
            var method = 'POST';
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
                    if (!response.error && method == 'POST')
                    {
                        // console.log(response);
                        //adding ids in new row                      
                        $('tr').find('td [data-id =""]').attr('data-id', response.match.id);


                    }
                    $('#incomes').text(response.response.incomes);
                    $('#expenses').text(response.response.expenses);
                    $('#debt').text(response.response.debt);
                    $('#total').text(response.response.total);
                },
                /*error: function (data) {
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
                success: function (response) {
                    $('#incomes').text(response.incomes);
                    $('#expenses').text(response.expenses);
                    $('#debt').text(response.debt);
                    $('#total').text(response.total);
                },
                /*error: function (data) {
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
        var isValid = true;
        $('#sample_editable_1_new').click(function (e) {

            e.preventDefault();
            $(this).addClass('disabled');
            var gymId = $(this).attr('data-gym');
            loadAjax(gymId);
            if (nNew && nEditing) {
                if (confirm("Previose row not saved. Do you want to save it ?")) {
                    saveRow(oTable, nEditing); // save
                    $(nEditing).find("td:first").html("Untitled"); //validation part
                    nEditing = null;
                    nNew = false;

                } else {
                    oTable.fnDeleteRow(nEditing); // cancel
                    nEditing = null;
                    nNew = false;

                    return;
                }
            }

            var aiNew = oTable.fnAddData(['', '', '', '', '', '', '', '', '', '']);
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
            if ($('#sample_editable_1_new').hasClass('disabled'))
            {
                $('#sample_editable_1_new').removeClass('disabled');
            }
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
            $('#sample_editable_1_new').addClass('disabled');
            var id = $(this).attr('data-id');
            if (id) {
                editAjax(url, id);

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
                if ($('#sample_editable_1_new').hasClass('disabled'))
                {
                    $('#sample_editable_1_new').removeClass('disabled');
                }
                /* Editing this row and want to save it */
                isValid = false;
                if (!validateData(oTable, nEditing))
                {
                    var keys = saveRow(oTable, nEditing);
                    nEditing = null;
                    var dataId = $(this).attr('data-id');
                    var aData = oTable.fnGetData(nRow);
                    var matchDate = $('#global-date').val();
                    var formData = {
                        home_fee: aData[2],
                        match_date: matchDate,
                        guest_fee: aData[5],
                        league_list: keys['leagues'],
                        home_list: keys['home_teams'],
                        guest_list: keys['guest_teams'],
                        referee_list: keys['referees'],
                        home_method_list: keys['payment_types_home'],
                        guest_method_list: keys['payment_types_guest'],
                    };

                    saveAjax(formData, dataId, url);
                } else {
                    table.on('click', '.cancel', function (e) {
                        e.preventDefault();
                        if (!isValid)
                        {
                            oTable.fnDeleteRow(nRow);
                        }
                        isValid = true;
                        nEditing = null;
                        $('#match-error').css('display', 'none');
                    });
                    $('#match-error').css('display', 'block');
                }
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }

        });
        $(document).on('change', '#leagues', function (e)
        {
            e.preventDefault();
            var leagueId = $(this).find(':selected').val();
            changeOnLeague(leagueId);

        });
        $(document).on('change', '#home_teams', function (e) {
            e.preventDefault();
            // console.log(window.guestTeam);

            var homeId = $(this).find(':selected').val();
            var leagueId = $("#leagues").find(':selected').val();
            //var guestId = $('#guest_teams').find(':selected').val();
            /*  if (window.isEdit)
             {
             changeEditAjax(url, homeId, guestId);
             
             } else {*/
            changeAjax(leagueId, homeId);
            // }
        });

    };
    /**
     * 
     * Second Table
     */
    var handleTable2 = function () {

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
             $.get('/gyms/day-expenses/create', function (data) {
                var methods = data.methods;
                $('.payments').empty();
                $.each(methods, function (index, method) {
                            $('.payments').append($("<option></option>").attr('value', method.id).text(method.name));
                    $('.payments-o').append($("<option></option>").attr('value', method.id).text(method.name));
                            });

                    });

                   
        }
        function editAjax(id)
        {
             $.get('/gyms/day-expenses/' + id + '/edit', function (data) {

                var dayExp = data.dExp;
                var methods = data.methods;
                $('.payments').empty();
                $.each(methods, function (index, method) {
                            $('.payments').append($("<option></option>").attr('value', method.id).text(method.name));
                    $('.payments-o').append($("<option></option>").attr('value', method.id).text(method.name));
                            });
                $('#rent').val(dayExp.has_rent);
                $('#curator').val(dayExp.has_curator);
                $('#payment_types_other').val(dayExp.other_method_id);
                $('#photo').val(dayExp.has_photo);
                $('#video').val(dayExp.has_video);
                $('#doctor').val(dayExp.has_doctor);
                $('#video_edit').val(dayExp.video_edit);
                    });     
        }

        function validateData(nRow)
        {
            var check = $('input.is_required', nRow).filter(function () {
                return $(this).val() == "";
            });
            var errors = false;
            $('.help-block', nRow).remove();
            $('input:text', nRow).removeClass('error-input');
            check.each(function () {
                $(this).addClass('error-input');
                errors = true;
            });
            return errors;
        }
        function editRow(oTable, nRow) {
            //class payments for required selects 
            //class payments-o for optional 
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            dataId = (dataId === undefined) ? '' : dataId;
            jqTds[0].innerHTML = '<input type="text" class="form-control input-small is_number" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<select id="photo" class="payments-o form-control input-small"><option value=""></option></select>';
            jqTds[2].innerHTML = '<input type="text" class="form-control input-small is_number" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<select id="video" class="payments-o form-control input-small"><option value=""></option></select>';
            jqTds[4].innerHTML = '<input type="text" class="form-control input-small is_number" value="' + aData[4] + '">';
            jqTds[5].innerHTML = '<select id="video_edit"  class="payments-o form-control input-small"><option value=""></option></select>';
            jqTds[6].innerHTML = '<input type="text" class="form-control input-small is_number is_required" value="' + aData[6] + '">';
            jqTds[7].innerHTML = '<select id="rent"  class="payments form-control input-small"><option value=""></option></select>';
            jqTds[8].innerHTML = '<input type="text" class="form-control input-small is_number" value="' + aData[8] + '">';
            jqTds[9].innerHTML = '<select id="doctor" class="payments-o form-control input-small"><option value=""></option></select>';
            jqTds[10].innerHTML = '<input type="text" class="form-control input-small is_number is_required" value="' + aData[10] + '">';
            jqTds[11].innerHTML = '<select id="curator" class="payments form-control input-small"><option value=""></option></select>';
            jqTds[12].innerHTML = '<input type="text" class="form-control input-small is_number" value="' + aData[12] + '">';
            jqTds[13].innerHTML = '<select id="payment_types_other" class=" payments-o form-control input-small"><option value=""></option></select>';
            jqTds[14].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[14] + '">';
            jqTds[15].innerHTML = '<a class="edit" data-id="' + dataId + '" href="">Сохранить</a>';
            jqTds[16].innerHTML = '<a class="cancel" href="">Отменить</a>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var dataId = $('.edit', nRow).attr('data-id');
            var payment_type_other = $("#payment_types_other :selected").text();
            var photo = $("#photo :selected").text();
            var video = $("#video :selected").text();
            var video_edit = $("#video_edit :selected").text();
            var rent = $("#rent :selected").text();
            var doctor = $("#doctor :selected").text();
            var curator = $("#curator :selected").text();
            var updateEditBtn = '<a class="edit" data-id="' + dataId + '" href="">Редактировать</a>';
            var updateDeleteBtn = '<a class="delete" data-id="' + dataId + '" href="">Удалить</a>';
            var keys = {};
            $('select', nRow).each(function () {
                value = $(this).find(':selected').val();
                index = $(this).attr('id');
                keys[index] = value;
            });
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(photo, nRow, 1, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
            oTable.fnUpdate(video, nRow, 3, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 4, false);
            oTable.fnUpdate(video_edit, nRow, 5, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 6, false);
            oTable.fnUpdate(rent, nRow, 7, false);
            oTable.fnUpdate(jqInputs[4].value, nRow, 8, false);
            oTable.fnUpdate(doctor, nRow, 9, false);
            oTable.fnUpdate(jqInputs[5].value, nRow, 10, false);
            oTable.fnUpdate(curator, nRow, 11, false);
            oTable.fnUpdate(jqInputs[6].value, nRow, 12, false);
            oTable.fnUpdate(payment_type_other, nRow, 13, false);
            oTable.fnUpdate(jqInputs[7].value, nRow, 14, false);
            oTable.fnUpdate(updateEditBtn, nRow, 15, false);
            oTable.fnUpdate(updateDeleteBtn, nRow, 16, false);
            oTable.fnDraw();
            return keys;
        }

//        function cancelEditRow(oTable, nRow) {
//            var jqInputs = $('input', nRow);
//            var league = $('#leagues :selected').text();
//            var home_team = $("#home_teams :selected").text();
//            var guest_team = $("#guest_teams :selected").text();
//            var payment_type_home = $("#payment_types_home :selected").text();
//            var payment_type_guest = $("#payment_types_guest :selected").text();
//            var referee = $("#referees :selected").text();
//            var payment_type_referee = $("#payment_types_referee :selected").text();
//             oTable.fnUpdate(league, nRow, 0, false);
//            oTable.fnUpdate(home_team, nRow, 1, false);
//            oTable.fnUpdate(jqInputs[0].value, nRow, 2, false);
//            oTable.fnUpdate(payment_type_home, nRow, 3, false);
//            oTable.fnUpdate(jqInputs[1].value, nRow, 4, false);
//            oTable.fnUpdate(guest_team, nRow, 5, false);
//            oTable.fnUpdate(jqInputs[2].value, nRow, 6, false);
//            oTable.fnUpdate(payment_type_guest, nRow, 7, false);
//            oTable.fnUpdate(referee, nRow, 8, false);
//            oTable.fnUpdate(payment_type_referee, nRow, 9, false);
//            oTable.fnUpdate('<a class="edit" href="">Редактировать</a>', nRow, 10, false);
//            oTable.fnDraw();
//        }

        function saveAjax(formData, dataId, url)
        {
            var method = 'POST';
            var url = url + '/day-expenses';
            if (dataId)
            {
                method = 'PATCH';
                url = '/gyms/day-expenses/' + dataId;
            }
            $.ajax({
                data: formData,
                dataType: 'json',
                type: method,
                url: url,
                success: function (response) {
                    if (!response.error && method == 'POST')
                    {

                        $('tr').find('td [data-id =""]').attr('data-id', response.dExp.id);
                        $('#sample_editable_2_new').css('display', 'none');
                    }
                    $('#incomes').text(response.response.incomes);
                    $('#expenses').text(response.response.expenses);
                    $('#debt').text(response.response.debt);
                    $('#total').text(response.response.total);
                },
                /*error: function (data) {
                 console.log('Error:', data);
                 }*/
            });
        }
        function deleteAjax(dataId, url)
        {
            $.ajax({
                dataType: 'json',
                type: 'DELETE',
                url: '/gyms/day-expenses/' + dataId,
                success: function (response) {
                    $('#incomes').text(response.incomes);
                    $('#expenses').text(response.expenses);
                    $('#debt').text(response.debt);
                    $('#total').text(response.total);
                    $('#sample_editable_2_new').css('display', 'block');
                },
//                error: function (data) {
//                    console.log('Error:', data);
//                }
            });
        }

        var table = $('#sample_editable_2');

        var oTable = table.dataTable({
            "paging": false,
            "info": false,
            "searching": false,
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

        var tableWrapper = $("#sample_editable_2_wrapper");
        var url = $('#sample_editable_2_new').attr('href');
        var nEditing = null;
        var nNew = false;
        if (table.fnSettings().aoData.length !== 0)
        {
            $('#sample_editable_2_new').css('display', 'none');
        }
        $('#sample_editable_2_new').click(function (e) {
            // window.isEdit = false;
            e.preventDefault();
            $(this).addClass('disabled');
            loadAjax(url);
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

            var aiNew = oTable.fnAddData(['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
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
            if ($('#sample_editable_2_new').hasClass('disabled'))
            {
                $('#sample_editable_2_new').removeClass('disabled');
            }
            $(this).removeClass('disabled');
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
            //  window.isEdit = true;
            e.preventDefault();
            $('#sample_editable_2_new').addClass('disabled');
            var id = $(this).attr('data-id');
            if (id) {
                editAjax(id);

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
                if ($('#sample_editable_2_new').hasClass('disabled'))
                {
                    $('#sample_editable_2_new').removeClass('disabled');
                }
                /* Editing this row and want to save it */
                isValid = false;
                if (!validateData(oTable, nEditing))
                {
                    var keys = saveRow(oTable, nEditing);
                    nEditing = null;
                    var dataId = $(this).attr('data-id');
                    var day = $('#global-date').val();
                    var aData = oTable.fnGetData(nRow);
                    var formData = {
                        photo_cost: aData[0],
                        video_cost: aData[2],
                        edit_cost: aData[4],
                        rent_cost: aData[6],
                        doctor_cost: aData[8],
                        curator_cost: aData[10],
                        other: aData[12],
                        comment: aData[14],
                        other_method_list: keys['payment_types_other'],
                        has_photo_list: keys['photo'],
                        has_video_list: keys['video'],
                        has_doctor_list: keys['doctor'],
                        video_edit_list: keys['video_edit'],
                        curator_list: keys['curator'],
                        rent_list: keys['rent'],
                        day: day

                    };
                    //console.log(JSON.stringify(formData));
                    saveAjax(formData, dataId, url);

                } else {
                    table.on('click', '.cancel', function (e) {
                        e.preventDefault();
                        if (!isValid)
                        {
                            oTable.fnDeleteRow(nRow);
                        }
                        isValid = true;
                        nEditing = null;
                        $('#exp-error').css('display', 'none');
                    });
                    $('#exp-error').css('display', 'block');

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
            handleTable2();
        }
    };

}();
jQuery(document).ready(function () {
    TableDatatablesEditable.init();
});