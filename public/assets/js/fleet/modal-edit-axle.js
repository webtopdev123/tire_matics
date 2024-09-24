let modelEditFormValid = null;

jQuery(function() {

    $('#model-table tbody').on('click', '.btnUpdate', function () {

        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);
                $(`
                    #updatemodelModal input[name='${key}'],
                    #updatemodelModal select[name='${key}'],
                    #updatemodelModal textarea[name='${key}']
                `)
                .val(data[key]).trigger('change');
            }
        }
        $('#updatemodelModal').modal('show');
    });

    modelEditFormValid = FormValidation.formValidation($('#updatemodelModal form')[0], {
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

    $('#updatemodelModal form #btn-update').on('click', function () {
        modelEditFormValid.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-axle/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updatemodelModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getModelList();

                            $('#updatemodelModal').modal('hide');
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
