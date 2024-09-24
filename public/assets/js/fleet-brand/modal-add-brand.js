let validateCreateBrandForm = null;

$( document ).ready(function() {

    document.getElementById('createcategoryModal').addEventListener('show.bs.modal', function (e) {
      $('#createcategoryModal input').val('');
    });

    validateCreateBrandForm = FormValidation.formValidation($('#createcategoryModal form')[0], {
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

    $('#createcategoryModal button[type="submit"]').on('click', function () {
      validateCreateBrandForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-brand/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#createcategoryModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            
                          if(dataTable_obj != null){
                            dataTable_obj.ajax.reload(null, false);
                          }

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
