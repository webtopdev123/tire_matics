let valeditInspection = null;

jQuery(function() {

    $('#addInspectionModal tbody').on('click', '.btnUpdate', function () {
        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);
                $(`
                    #updateInspectionModal input[name='${key}'],
                    #updateInspectionModal select[name='${key}'],
                    #updateInspectionModal textarea[name='${key}']
                `)
                .val(data[key]).trigger('change');
            }
        }
        $('#addInspectionModal').modal('hide');
        $('#updateInspectionModal').modal('show');
    });

    $('#updateInspectionModal').on('hidden.bs.modal', function () {
        $('#addInspectionModal').modal('show');
    });

    valeditInspection = FormValidation.formValidation($('#updateInspectionModal form')[0], {
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

    $('#updateInspectionModal form #btn-update').on('click', function () {
        valeditInspection.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-tyre-inspection/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updateInspectionModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getInspectionList();

                            $('#updateInspectionModal').modal('hide');
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
