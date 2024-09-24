let sizeEditFormValid = null;

jQuery(function() {

    $('#size-table tbody').on('click', '.btnUpdate', function () {

        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);
                $(`
                    #updatesizeModal input[name='${key}'],
                    #updatesizeModal select[name='${key}'],
                    #updatesizeModal textarea[name='${key}']
                `)
                .val(data[key]).trigger('change');
            }
        }
        flag_modal = false;

        $('#updatesizeModal').modal('show');
        $('#modellistModal').modal('hide');
        $('#sizelistModal').modal('hide');
    });

    sizeEditFormValid = FormValidation.formValidation($('#updatesizeModal form')[0], {
        fields: {
            merchant_name: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: function (field, ele) {
                    return '.mb-3';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });

    $('#updatesizeModal form #btn-update').on('click', function () {
        sizeEditFormValid.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/tyre-brand-model-size/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updatesizeModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getSizeList();

                            $('#updatesizeModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

});
