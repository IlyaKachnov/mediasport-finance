$(document).on("focus","input", function () {
  $(this).closest('input').inputmask('999,999,999 руб.', {
        numericInput: true,
        rightAlignNumerics: false,
      });    
})
.on("blur","input", function () {
  $(this).closest('input').inputmask('remove');
}); 