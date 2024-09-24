let validateEditBrand = null;

jQuery(function() {

  document.getElementById('updatecategoryModal').addEventListener('show.bs.modal', function (e) {
    $('#updatecategoryModal input').val('');

    const model_data = JSON.parse(e.relatedTarget.dataset.model_data);

    $('#updatecategoryModal [name="brand_id"]').val(model_data.brand_id);
    $('#updatecategoryModal [name="brand_name"]').val(model_data.brand_name);

  });


  validateEditBrand = FormValidation.formValidation($('#updatecategoryModal form')[0], {
        fields: {
            brand_name: {
                validators: {
                    notEmpty: {
                        message: "please enter brand name"
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
      validateEditBrand.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-brand/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updatecategoryModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            
                            if(dataTable_obj != null){
                              dataTable_obj.ajax.reload(null, false);
                            }

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
