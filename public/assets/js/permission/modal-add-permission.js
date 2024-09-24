/**
 * Add new role Modal JS
 */

'use strict';
let addFormValidator = null;
document.addEventListener('DOMContentLoaded', function () {

  // Add Form validation
  addFormValidator = FormValidation.formValidation(document.getElementById('addPermissionForm'), {
    fields: {
      permission_name: {
        validators: {
          notEmpty: {
            message: 'Please enter permission name'
          }
        }
      },
      permission_code: {
        validators: {
          notEmpty: {
            message: 'Please enter permission code'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        // eleInvalidClass: '',
        eleValidClass: '',
        rowSelector: '.col-12'
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  // empty modal data when click add new
  document.getElementById('addPermissionModal').addEventListener('show.bs.modal', function (e) {
    $('#addPermissionModal #modalPermissionName').val('');
    $('#addPermissionModal #modalPermissionCode').val('');
  });

  // save Permission
  $('#addPermissionForm button[type="submit"]').on('click', function (event) {

    addFormValidator.validate().then(function (status) {

      if (status === 'Valid') {
        $.ajax({
          url: '/app/permission/add',
          type: 'POST',
          data: {
            permission_name: $('#addPermissionForm #modalPermissionName').val(),
            permission_code: $('#addPermissionForm #modalPermissionCode').val()
          },
          success: function (response) {

            $('#addPermissionModal').modal('hide');
            if (response.status == '200') {
              toastr.success('One record has successfully added.', '', { timeOut: 3000 });
              
              var dt_permission = $('.datatables-permissions').DataTable();
              dt_permission.ajax.reload(null, false);
            }

          },
          error: function (xhr, status, error) {

            $('#addPermissionModal').modal('hide');
            toastr.error('Some input is not completed.', '', { timeOut: 3000 });
           
          }
        });

      } else {
        event.preventDefault();
      }
    });

  });

});