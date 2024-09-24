let valConfirmAssign = null;

jQuery(function () {
  $('#axleListModal').on('click', '.btnInstall', function () {
    tyre_id = $(this).attr('data-tyre-id');
    $('#confirmAssignModal').modal('show');
  });

  valConfirmAssign = FormValidation.formValidation($('#confirmAssignModal form')[0], {
    fields: {
      inspection_mileage: {
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

  $('#confirmAssignModal button[type="submit"]').on('click', function () {
    valConfirmAssign.validate().then(function (status) {
      if (status === 'Valid') {
        $.ajax({
          url: '/fleet-detail/install',
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          data: {
            'tyre_id': tyre_id,
            'current_fleet_axle_id': current_fleet_axle_id,
            'current_fleet_axle_position': current_fleet_axle_position,
            'inspection_mileage': $('#confirmAssignModal form input[name="inspection_mileage"]').val(),
          },
          // data: $('#confirmAssignModal form').serialize(),
          success: function (response, status, xhr) {
            if (response.success) {
              // show message complete and close popup
              $('#axleListModal').modal('hide');
              toastr.success('Tyre installed successfully.', '', { timeOut: 3000 });
              // load Tyre info
              // loadTyreData(tyre_id, false);

              // $('.tyre-container.active').attr('data-tyre-id', tyre_id);

              // //set installed for this tyre
              // if (!$('.tyre-container.active').hasClass('installed')) {
              //   $('.tyre-container.active').addClass('installed');
              //   $('.tyre-container.active .percen').text('100%');
              // }

              location.reload();
              $('#confirmAssignModal').modal('hide');
            } else {
              toastr.warning('Error: Tyre installed before.', '', { timeOut: 3000 });
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
