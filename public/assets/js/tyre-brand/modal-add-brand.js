let formValcreatestockForm = null;

$( document ).ready(function() {

    $('#btn-create').on('click', function () {
        $('#createcategoryModal form')[0].reset();
        $('#createcategoryModal').modal('show');
        $('#modellistModal').modal('hide');
    });

    formValcreatestockForm = FormValidation.formValidation($('#createcategoryModal form')[0], {
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

    $('#createcategoryModal form #btn-create').on('click', function () {
        formValcreatestockForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/tyre-brand/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#createcategoryModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getTableData();
                            $('#createcategoryModal').modal('hide');
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
