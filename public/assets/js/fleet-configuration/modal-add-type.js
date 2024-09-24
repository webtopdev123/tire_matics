let formValcreatestockForm = null;

$(document).ready(function () {

    $('#btn-create').on('click', function () {
        $('#createcategoryModal form')[0].reset();
        $('#createcategoryModal').modal('show');
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

                // if (!validatePosition($('#createcategoryModal input[name="configuration_setting"]').val())) {
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

                let formData = new FormData($('#createcategoryModal form')[0]);

                $.ajax({
                    url: '/fleet-configuration/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    // data: $('#createcategoryModal form').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
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

function validatePosition(input) {
    // Regular expression to match the format "2-2-2" or "2-4-2"
    const regex = /^(2|4)(-(2|4))*$/;

    return regex.test(input);

    // // Check if the format is correct (i.e., numbers separated by hyphens and only 2 or 4)
    // if (regex.test(input)) {
    //     // Split the input by hyphens and convert to an array of numbers
    //     const numbers = input.split('-').map(Number);

    //     // Check if all numbers are either 2 or 4
    //     return numbers.every(num => num === 2 || num === 4);
    // } else {
    //     return false;
    // }
}