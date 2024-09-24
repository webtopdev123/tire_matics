let formValeditstockForm = null;

jQuery(function () {

    $('#main-table tbody').on('click', '.btnUpdate', function () {


        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);
                // $(`input[name='${key}'], select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
                $(`input[name='${key}']:not([type='file']), select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
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
                // if (!validatePosition($('#updatecategoryModal input[name="configuration_setting"]').val())) {
                //     console.log("Format invalid");
                //     Swal.fire({
                //         title: '',
                //         text: "Invalid Format in Positions",
                //         icon: 'warning',
                //         showCancelButton: false,
                //         confirmButtonColor: '#f1f1f2',
                //         confirmButtonText: 'Close',
                //         customClass: {
                //             confirmButton: 'btn btn-label-danger',
                //         }
                //     });
                //     return;
                // }

                let formData = new FormData($('#updatecategoryModal form')[0]);

                $.ajax({
                    url: '/fleet-configuration/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    // data: $('#updatecategoryModal form').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getTableData();
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
