let valUnmountForm = null;

jQuery(function() {

    $('.tyre_info .btn-unmount').on('click', function () {
        $('#unmountModal form')[0].reset();

        let tyreId = $('.tyre_info .tyre_id').val();
        if (tyreId.length < 1) {
            return;
        }

        $("#unmountModal input[name='tyre_id']").val(tyreId);

        $('#unmountModal').modal('show');
    });

    $('.spare-container .btn-unmount').on('click', function () {
        $('#unmountModal form')[0].reset();

        let tyreId = $(this).attr('data-tyre-id');
        if (tyreId.length < 1) {
            return;
        }

        $("#unmountModal input[name='tyre_id']").val(tyreId);

        $('#unmountModal').modal('show');
    });

    valUnmountForm = FormValidation.formValidation($('#unmountModal form')[0], {
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

    $('#unmountModal button[type="submit"]').on('click', function () {
      valUnmountForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-detail/unmount',
                    type: 'POST',
                    headers: {
                      'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#unmountModal form').serialize(),
                    success: function (response, status, xhr) {
                        toastr.success('Tyre unmounted successfully.', '', { timeOut: 3000 });
                        $('#unmountModal').modal('hide');
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                      console.log(xhr);
                      toastr.warning(error, '', { timeOut: 3000 });
                    }
                  });
            }
        });
    });

});
