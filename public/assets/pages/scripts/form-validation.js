var FormValidation = function () {

    // basic validation
    var handleValidation1 = function () {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#form_sample_1');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                name: {
                    minlength: 3,
                    required: true
                },
                address: {
                    minlength: 3,
                    required: true
                },
                rent: {
                    required: true,
                    number: true
                }
            },
            messages: {
                name: {
                    required: "Название обязательно!",
                    minlength: jQuery.validator.format("Название должно иметь не менее {0} символов")
                },
                address: {
                    required: "Адрес обязателен!",
                    minlength: jQuery.validator.format("Адрес должен иметь не менее {0} символов")
                },
                rent: {
                    required: "Аренда обязательна!",
                    number: jQuery.validator.format("Укажите числовое значение")
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                error.insertAfter(element.closest(".input-group"));
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
            },
            unhighlight: function (element) { // revert the change done by hightlight

            },
            success: function (label, element) {
                var icon = $(element).parent('.input-icon').children('i');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                icon.removeClass("fa-warning").addClass("fa-check");
            },
            submitHandler: function (form) {
                success1.show();
                error1.hide();
                form[0].submit();
            }
        });


    }

    // validation using icons
    var handleValidation2 = function () {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form2 = $('#form_sample_2');
        var error2 = $('.alert-danger', form2);
        var success2 = $('.alert-success', form2);

        form2.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                lastname: {
                    minlength: 3,
                    required: true
                },
                middlename: {
                    minlength: 3,
                    required: true
                },
                firstname: {
                    minlength: 3,
                    required: true
                },
            },
            messages: {// custom messages for radio buttons and checkboxes
                firstname: {
                    minlength: jQuery.validator.format("Имя должно быть не менее {0} символов!"),
                    required: "Имя обязательно!"
                },
                lastname: {
                    minlength: jQuery.validator.format("Фамилия должна быть не менее {0} символов!"),
                    required: "Фамилия обязательна!"
                },
                middlename: {
                    minlength: jQuery.validator.format("Отчество должно быть не менее {0} символов!"),
                    required: "Отчество обязательно!"
                },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success2.hide();
                error2.show();
                App.scrollTo(error2, -200);
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                error.insertAfter(element.closest(".input-group"));
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
            },
            unhighlight: function (element) { // revert the change done by hightlight

            },
            success: function (label, element) {
                var icon = $(element).parent('.input-icon').children('i');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                icon.removeClass("fa-warning").addClass("fa-check");
            },
            submitHandler: function (form) {
                success2.show();
                error2.hide();
                form[0].submit(); // submit the form
            }
        });


    }

    // advance validation
    var handleValidation3 = function () {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form3 = $('#form_sample_3');
        var error3 = $('.alert-danger', form3);
        var success3 = $('.alert-success', form3);

        //IMPORTANT: update CKEDITOR textarea with actual content before submit
        form3.on('submit', function () {
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
        })

        form3.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                name: {
                    minlength: 3,
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                roles_list: {
                    required: true
                },
                password:
                        {
                            required: true,
                            minlength: 6,
                        }

            },
            messages: {// custom messages for radio buttons and checkboxes
                roles_list: {
                    required: "Необходимо указать роль!"
                },
                password: {
                    required: "Необходимо указать пароль!",
                    minlength: jQuery.validator.format("Длина пароля должна быть не менее {0} символов!")
                },
                name: {
                    minlength: jQuery.validator.format("Имя должно быть не менее {0} символов!"),
                    required: "Имя пользователя обязательно!"
                },
                email: {
                    required: "Email обязателен!",
                    email: "Некорректный формат email!"
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type

                error.insertAfter(element.closest(".input-group"));


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
                form[0].submit(); // submit the form
            }

        });

        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('.select2me', form3).change(function () {
            form3.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
    }

    var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {

            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }

    return {
        //main function to initiate the module
        init: function () {

            handleWysihtml5();
            handleValidation1();
            handleValidation2();
            handleValidation3();

        }

    };

}();

jQuery(document).ready(function () {
    FormValidation.init();
});