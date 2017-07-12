var FormValidation = function () {

    // advance validation
    var handleValidation3 = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

            var form3 = $('#form_sample_3');
            var error3 = $('.alert-danger', form3);
            var success3 = $('.alert-success', form3);

            form3.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error text-alert', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
            rules: {
                old_password:
                          {
                            required: true,
                            minlength: 6,
                        },
                password:
                        {
                            required: true,
                            minlength: 6,
                        },
                password_confirmation:
                        {
                            required: true,
                            equalTo:"#password",
                        }

            },

                messages: { // custom messages for radio buttons and checkboxes
               
                old_password: {
                    required: "Необходимо указать пароль!",
                    minlength: jQuery.validator.format("Длина пароля должна быть не менее {0} символов!")
                },
                password: {
                    required: "Необходимо указать пароль!",
                    minlength: jQuery.validator.format("Длина пароля должна быть не менее {0} символов!")
                },
                password_confirmation: {
                    required: "Необходимо указать пароль!",
                    minlength: jQuery.validator.format("Длина пароля должна быть не менее {0} символов!"),
                    equalTo: "Пароли не совпадают!",
                },
          
                },

                errorPlacement: function (error, element) { // render error placement for each input type
   
                        error.insertAfter(element.closest(".form-group"));
                   

                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success3.hide();
                    error3.show();
                    App.scrollTo(error3, -200);
                },

                highlight: function (element) { // hightlight error inputs
                   $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

            submitHandler: function (form) {
                success3.show();
                error3.hide();
                var url = $(form).attr('action');
                var old = $('#old_password').val();
                var current = $('#password').val();
                $.ajax({
                    data: {old_password: old, password: current},
                    dataType: 'json',
                    type: 'POST',
                    url: url,
                    success: function (response) {
                 
                        $(form).prepend('<div id="response" class="alert ' + response.alert + '">' + response.text + '</div>');
                        $(form)[0].reset();
                      
                        setTimeout(function () {
                            $('#response').remove();
                        }, 3000);
                   
                    }
                });
                return false;
            }

        });

    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation3();
          //  ajaxSend();
        }

    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});