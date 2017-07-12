$('.date-picker').load('.date-picker').datepicker({
    format: "dd-mm-yyyy",
    minViewMode: 1,
    multidate: true,
    autoclose: true,
    toggleActive: true,
    //defaultDate: new Date(),
});
 $('.datepicker').datepicker("setDate",'2010-07-25');

$(document).on("click", ".date-picker button", function () {
    $(this).closest(".date-picker.date").datepicker('show');
});
$(document).on('change', ".date-picker", function () {
    $('.datepicker').hide();
});
$(document).on("focus", ".is_number", function () {
    $(this).closest('input.is_number').inputmask('999,999,999 руб.', {
        numericInput: true,
        rightAlignNumerics: false,
    });
});
$(document).on("blur", ".is_number", function () {
    $(this).closest('input.is_number').inputmask('remove');
});    