$('.date-picker').load('.date-picker').datepicker({
    format: "dd-mm-yyyy",
    minViewMode: 1,
    multidate: true,
    autoclose: true,
    toggleActive: true
});

$(document).on("click", ".date-picker button", function () {
    $(this).closest(".date-picker.date").datepicker('show');
});
$(document).on("focus",".is_number", function () {
  $(this).closest('input.is_number').inputmask('999,999,999 руб.', {
        numericInput: true,
        rightAlignNumerics: false,
      });    
}); 
$(document).on("blur",".is_number", function () {
  $(this).closest('input.is_number').inputmask('remove');
});    