/**
 * Add new role Modal JS
 */

'use strict';
let editFormValidator = null;
document.addEventListener('DOMContentLoaded', function (e) {
  // Add Form Validation
  editFormValidator = FormValidation.formValidation(document.getElementById('editForm'), {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter username'
          }
        }
      },
      password: {
        validators: {
          callback: {
            message: 'Password must be at least 6 characters long',
            callback: function (value, validator) {
              if (value.value === '') {
                return true;
              } else {
                return value.value.length >= 6;
              }
            }
          }
        }
      },
      merchant_id: {
        validators: {
          notEmpty: {
            message: 'Please select merchant'
          }
        }
      },
      role_id: {
        validators: {
          notEmpty: {
            message: 'Please select role'
          }
        }
      },
      status: {
        validators: {
          notEmpty: {
            message: 'Please select status'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  // set modal data when edit
  document.getElementById('editModal').addEventListener('show.bs.modal', function (e) {
    // var userdetails = JSON.parse(e.relatedTarget.dataset.userdetails);

    $('#editModal input, #editModal textarea').val('');
    $('#editModal #editStatus').val('').trigger('change');
    $('#editModal #editRole').val('').trigger('change');
    $('#editModal #editMerchant').val('').trigger('change');

    $.each(e.relatedTarget.dataset, function (key, value) {
      if (value !== 'undefined' && value !== '') {
        $('#editModal input[name="' + key + '"]').val(value);
        $('#editModal textarea[name="' + key + '"]').val(value);

        if (key == 'status') {
          $('#editModal #editStatus').val(value).trigger('change');
        }

        if (key == 'role_id') {
          $('#editModal #editRole').val(value).trigger('change');
        }

        if (key == 'merchant_id') {
          $('#editModal #editMerchant').val(value).trigger('change');
        }
      }
    });
  });

  $('#editForm button[type="submit"]').on('click', function (event) {
    editFormValidator.validate().then(function (status) {
      if (status === 'Valid') {
        var formData = $('#editForm').serializeArray();
        $.ajax({
          url: '/setting/user/edit',
          type: 'POST',
          data: formData,
          success: function (response, status, xhr) {
            $('#editModal').modal('hide');
            if (xhr.status === 200) {
              toastr.success('One record has successfully Updated.', 'Success!', { timeOut: 3000 });

              var dt_users = $('.datatables-users').DataTable();
              dt_users.ajax.reload(null, false);
            }
          },
          error: function (xhr, status, error) {
            $('#editModal').modal('hide');

            if (xhr.status === 422) {
              var errors = xhr.responseJSON.errors;
              // Assuming you're using SweetAlert for displaying errors
              toastr.error(Object.values(errors).flat().join('\n'), 'Validation Error!', { timeOut: 3000 });

            } else {
              // Handle other error cases
              toastr.error('Unexpected error occurred.', 'Error!', { timeOut: 3000 });

            }
          }
        });
      } else {
        event.preventDefault();
      }
    });
  });
});
