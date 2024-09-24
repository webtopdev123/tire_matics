let validateEditBrand = null;
var edit_model_id = 0;
var edit_size_id = 0;

jQuery(function() {

  $('#main-table tbody').on('click', '.btnUpdate', function () {
    var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(`${key}: ${data[key]}`);

                if(key == 'tyre_model_id'){
                  edit_model_id = data[key];
                }else if(key == 'tyre_size_id'){
                  edit_size_id = data[key];
                } else {
                  $(`#editTyreModal input[name='${key}'], #editTyreModal select[name='${key}'], #editTyreModal textarea[name='${key}']`).val(data[key]);
                }
            }
        }
        $('#editTyreModal .sel-brand').trigger('change');
        $('#editTyreModal').modal('show');
  });

  // After choose Brand, ajax to serve to get Model data, also clean size dropdown
  $('#editTyreModal').on('change', '.sel-brand', function(){
    brand_id = $(this).val();
    if(brand_id != ''){
      // load data for model dropdown
      generateModelOption('#editTyreModal', brand_id, edit_model_id);

      // clean size dropdown
      $('#editTyreModal .sel-brand-model-size').html('');

    }
  });

  // After choose Model, ajax to serve to get Size data
  $('#editTyreModal').on('change', '.sel-brand-model', function(){
    model_id = $(this).val();
    console.log(model_id);
    if(model_id != ''){
      // load data for model dropdown
      generateSizelOption('#editTyreModal', model_id, edit_size_id);
    }
  });

  // After choose Size, ajax to serve to get Size data
  $('#editTyreModal').on('change', '.sel-brand-model-size', function () {
    size_id = $(this).val();
    if (size_id != '') {
        // load data for model dropdown
        fillSizeValue('#editTyreModal', size_id);
    }
});


  validateEditBrand = FormValidation.formValidation($('#editTyreModal form')[0], {
        fields: {
          tyre_serial: {
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

    $('#editTyreModal form #btn-update').on('click', function () {
      validateEditBrand.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-tyre/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#editTyreModal form').serialize(),
                    success: function (response, status, xhr) {
                      if (xhr.status === 200) {
                          console.log(response);
                          getTableData();
                          $('#editTyreModal').modal('hide');

                          toastr.success('Edited successfully.', '', { timeOut: 3000 });
                      }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    $('#editTyreModal input[name="tyre_serial"]').on('input', function() {
        $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
    });

    $('#editTyreModal input[name="tyre_year"]').on('input', function() {
      $(this).val($(this).val().toUpperCase().replace(/[^0-9]/g, '').substring(0, 4));
    });

});
