let modelEditFormValid = null;

jQuery(function() {

  
  document.getElementById('updatemodelModal').addEventListener('show.bs.modal', function (e) {
    $('#updatemodelModal input').val('');

    const model_data = JSON.parse(e.relatedTarget.dataset.model_data);

    $('#updatemodelModal [name="model_id"]').val(model_data.model_id);
    $('#updatemodelModal [name="model_name"]').val(model_data.model_name);

  });

    modelEditFormValid = FormValidation.formValidation($('#updatemodelModal form')[0], {
        fields: {
            model_name: {
                validators: {
                    notEmpty: {
                        message: "please select model name"
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

    $('#updatemodelModal button[type="submit"]').on('click', function () {
        modelEditFormValid.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-brand-model/update',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#updatemodelModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            
                            if(dataTable_brand_model_obj != null){
                              dataTable_brand_model_obj.ajax.reload(null, false);
                            }

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
