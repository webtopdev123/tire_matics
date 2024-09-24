let formValeditstockForm = null;

jQuery(function() {

    $('#main-table tbody').on('click', '.btnUpdate', function () {

        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);
                $(`input[name='${key}'], select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
            }
        }
        $('#updatecategoryModal').modal('show');
    });

    formValeditstockForm = FormValidation.formValidation($('#updatecategoryModal form')[0], {
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

    $('#updatecategoryModal form #btn-update').on('click', function () {
        formValeditstockForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-category/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updatecategoryModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            var dt_level = $('.datatables-main').DataTable();
                            dt_level.ajax.reload(null, false);
                            $('#updatecategoryModal').modal('hide');
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
