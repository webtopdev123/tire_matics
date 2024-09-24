let formValcreatestockForm = null;

$(document).ready(function () {

    $('#btn-create').on('click', function () {
        $('#createTyreModal form')[0].reset();
        $('#createTyreModal').modal('show');
    });

    // After choose Brand, ajax to serve to get Model data, also clean size dropdown
    $('#createTyreModal').on('change', '.sel-brand', function () {
        brand_id = $(this).val();
        if (brand_id != '') {
            // load data for model dropdown
            generateModelOption('#createTyreModal', brand_id);

            // clean size dropdown
            $('#createTyreModal .sel-brand-model-size').html('');
        }
    });

    // After choose Model, ajax to serve to get Size data
    $('#createTyreModal').on('change', '.sel-brand-model', function () {
        model_id = $(this).val();
        if (model_id != '') {
            // load data for model dropdown
            generateSizelOption('#createTyreModal', model_id);
        }
    });

    // After choose Size, ajax to serve to get Size data
    $('#createTyreModal').on('change', '.sel-brand-model-size', function () {
        size_id = $(this).val();
        if (size_id != '') {
            // load data for model dropdown
            fillSizeValue('#createTyreModal', size_id);
        }
    });

    formValcreatestockForm = FormValidation.formValidation($('#createTyreModal form')[0], {
        fields: {
            "tyre_serial[]": {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_odt: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_psi: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_price: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_year: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    },
                    stringLength: {
                        min: 4,
                        message: 'This field is invalid',
                    }

                }
            },
            tyre_status: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_type: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_brand_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_model_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            tyre_size_id: {
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

    $('#createTyreModal form #btn-create').on('click', function () {
        formValcreatestockForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-tyre/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#createTyreModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            toastr.success('Added successfully.', '', { timeOut: 3000 });
                            getTableData();
                            $('#createTyreModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    $('#createTyreModal').on('input', '.tyre_serial', function () {
        $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
    });

    $('#createTyreModal input[name="tyre_year"]').on('input', function () {
        $(this).val($(this).val().toUpperCase().replace(/[^0-9]/g, '').substring(0, 4));
    });

    $('#createTyreModal .btnAddSerial').on('click', function () {
      var firstChildClone = $('#createTyreModal #tyre-serial-list').children().first().clone();
      firstChildClone.val('');
      $('#createTyreModal #tyre-serial-list').append(firstChildClone);
  });

});
