'use strict';
let addFormValidator = null;
document.addEventListener('DOMContentLoaded', function (e) {
  // Add Form Validation
  addFormValidator = FormValidation.formValidation(document.getElementById('addForm'), {
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
          notEmpty: {
            message: 'Please enter your login password'
          },
          stringLength: {
            min: 6,
            message: 'Password must be at least 6 characters long'
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

  fillDropDown('role', ['editRole', 'addRole'], '');
  // fillDropDown('merchant', ['addMerchant', 'addMerchant']);
  fillDropDown('merchant', ['editMerchant', 'addMerchant'], '');

  document.getElementById('addModal').addEventListener('show.bs.modal', function (e) {
    $('#addModal input, #addModal textarea').val('');
    $('#addModal #addStatus').val(1).trigger('change');
    $('#addModal #addRole').val('').trigger('change');
    $('#addModal #addMerchant').val('').trigger('change');
  });

  $('#addForm button[type="submit"]').on('click', function (event) {
    addFormValidator.validate().then(function (status) {
      if (status === 'Valid') {
        var formData = $('#addForm').serializeArray();

        $.ajax({
          type: 'POST',
          url: '/setting/user/add',
          data: formData,
          success: function (response) {
            console.log(response);
            
            $('#addModal').modal('hide');

            toastr.success('One record has successfully added.', '', { timeOut: 3000 });

            var dt_user = $('.datatables-users').DataTable();
            dt_user.ajax.reload(null, false);
          },
          error: function (xhr) {
            console.log(xhr);
            
            $('#addModal').modal('hide');

            if (xhr.status === 422) {
              var errors = xhr.responseJSON.errors;
              // Assuming you're using SweetAlert for displaying errors
              toastr.error(Object.values(errors).flat().join('\n'), '', { timeOut: 3000 });
            } else {
              // Handle other error cases
              toastr.error('Unexpected error occurred.', '', { timeOut: 3000 });

            }
          }
        });
      } else {
        event.preventDefault();
      }
    });
  });
});

function fillDropDown(route, selectIds, data, callback) {
  $.ajax({
    url: '/filldropdown/' + route,
    type: 'GET',
    data: data,
    success: function (response, status, xhr) {
      var options = '';

      $.each(response, function (index, item) {
        options += `<option value="${item.value}">${item.name}</option>`;
      });

      if (options != '') {
        for (const index in selectIds) {
          $('#' + selectIds[index]).append(options);
        }
      }

      if (typeof callback === 'function') {
        callback(response);
      }
    },
    error: function (xhr, status, error) {}
  });
}
