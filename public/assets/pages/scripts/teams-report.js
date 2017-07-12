function sendData(a, b, c) {
    $.ajax({
        data: b,
        dataType: "json",
        type: "POST",
        url: c,
        success: function(b) {
            if (!b.error) {
                a.fnClearTable();
                for (var c in b) $.each(b[c], function(b, c) {
                    a.fnAddData([c.name, c.cash_expenses, c.cashless_expenses, c.card_expenses, c.total_expenses, c.cash_incomes, c.card_incomes, c.cashless_incomes, c.total_incomes, c.total])
                })
            }
        }
    })
}
var TableDatatablesButtons = function() {
    var a = function() {
            $(".date-picker").datepicker({
                rtl: App.isRTL(),
                autoclose: !0
            })
        },
        b = function() {
            $("#league_list").select2({
                placeholder: "Лиги",
                allowClear: !0
            })
        },
        c = function() {
            var a = $("#sample_3"),
                b = a.dataTable({
                    language: {
                        aria: {
                            sortAscending: ": активировать для сортировки столбца по возрастанию",
                            sortDescending: ": активировать для сортировки столбца по убыванию"
                        },
                        processing: "Подождите...",
                        search: "Поиск:",
                        lengthMenu: "Показать _MENU_ записей",
                        info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                        infoEmpty: "Записи с 0 до 0 из 0 записей",
                        infoFiltered: "(отфильтровано из _MAX_ записей)",
                        infoPostFix: "",
                        loadingRecords: "Загрузка записей...",
                        zeroRecords: "Записи отсутствуют.",
                        emptyTable: "В таблице отсутствуют данные",
                        paginate: {
                            first: "Первая",
                            previous: "Предыдущая",
                            next: "Следующая",
                            last: "Последняя"
                        }
                    },
                    buttons: [{
                        extend: "print",
                        className: "btn dark btn-outline"
                    }, {
                        extend: "copy",
                        className: "btn red btn-outline"
                    }, {
                        extend: "pdf",
                        className: "btn green btn-outline"
                    }, {
                        extend: "excel",
                        className: "btn yellow btn-outline "
                    }, {
                        extend: "csv",
                        className: "btn purple btn-outline "
                    }, {
                        extend: "colvis",
                        className: "btn dark btn-outline",
                        text: "Columns"
                    }],
                    responsive: !0,
                    order: [
                        [0, "asc"]
                    ],
                    lengthMenu: [
                        [5, 10, 15, 20, -1],
                        [5, 10, 15, 20, "All"]
                    ],
                    pageLength: 10
                });
            $("#sample_3_tools > li > a.tool-action").on("click", function() {
                var a = $(this).attr("data-action");
                b.DataTable().button(a).trigger()
            }), $("#send_btn").on("click", function(a) {
                a.preventDefault();
                var c = $("#date_from").val(),
                    d = $("#date_until").val();
                if ("" !== d && "" !== c || "" == d && "" == c) {
                    var e = $("select#league_list").val(),
                        f = $(this).data("href"),
                        g = $("#fees").is(":checked"),
                        h = {
                            date_from: c,
                            date_until: d,
                            league_list: e,
                            fees: g
                        };
                    sendData(b, h, f)
                } else $(".alert-danger").css("display", "block")
            }), $("#reload_btn").on("click", function(a) {
                a.preventDefault(), $("#date_from").val(""), $("#date_until").val(""), $("#fees").removeAttr("checked"), $("#league_list").val(null).trigger("change");
                var c = $(this).data("href");
                sendData(b, formData = {}, c)
            })
        };
    return {
        init: function() {
            jQuery().dataTable && (a(), b(), c())
        }
    }
}();
jQuery(document).ready(function() {
    TableDatatablesButtons.init()
});