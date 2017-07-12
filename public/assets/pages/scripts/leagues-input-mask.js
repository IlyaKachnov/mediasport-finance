$(document).on("focus",".number", function () {
  $(this).closest('input.number').inputmask('999,999,999 руб.', {
        numericInput: true,
        rightAlignNumerics: false,
      });    
}); 
$(document).on("blur",".number", function () {
  $(this).closest('input.number').inputmask('remove');
}); 