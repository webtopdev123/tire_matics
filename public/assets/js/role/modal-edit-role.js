let editFormValidator = null;

document.addEventListener('DOMContentLoaded', function (e) {
  // Add Form Validation
  editFormValidator = FormValidation.formValidation(document.getElementById('editRoleForm'), {
    fields: {
      role_name: {
        validators: {
          notEmpty: {
            message: 'Please enter Level name'
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

  // set modal data when edit
  document.getElementById('editRoleModal').addEventListener('show.bs.modal', function (e) {
    $('#editRoleModal #role_id').val('');
    $('#editRoleModal #editRoleName').val('');

    const id = e.relatedTarget.dataset.role_id;
    const name = e.relatedTarget.dataset.name;

    $('#editRoleModal #editRoleName').val(name);
    $('#editRoleModal #role_id').val(id);

    var permissionsData = JSON.parse(e.relatedTarget.dataset.permissions);
    $('#editRoleModal #permissionTable input[type="checkbox"]').prop('checked', false);

    $.each(permissionsData, function (index, permission) {
      var permissionId = permission.permission_id;
      var isRead = permission.permission_role_read;
      var isCreate = permission.permission_role_create;
      var isUpdate = permission.permission_role_update;
      var isDelete = permission.permission_role_delete;

      if (isRead == '1') {
        $('#editRoleModal #permissionTable [name="permissions[' + permissionId + '][read]"]').prop('checked', true);
      }
      if (isCreate == '1') {
        $('#editRoleModal #permissionTable [name="permissions[' + permissionId + '][create]"]').prop('checked', true);
      }
      if (isUpdate == '1') {
        $('#editRoleModal #permissionTable [name="permissions[' + permissionId + '][update]"]').prop('checked', true);
      }
      if (isDelete == '1') {
        $('#editRoleModal #permissionTable [name="permissions[' + permissionId + '][delete]"]').prop('checked', true);
      }
    });
  });

  // update role
  $('#editRoleModal button[type="submit"]').on('click', function (event) {
    editFormValidator.validate().then(function (status) {
      if (status === 'Valid') {
        var formData = $('#editRoleForm').serializeArray();
        $.ajax({
          url: '/setting/role/edit',
          type: 'POST',
          data: formData,
          success: function (response, status, xhr) {
            $('#editRoleModal').modal('hide');
            if (xhr.status === 200) {
              toastr.success('One record has successfully Updated.', '', { timeOut: 3000 });

              var dt_roles = $('.datatables-roles').DataTable();
              dt_roles.ajax.reload(null, false);
            }
          },
          error: function (xhr, status, error) {
            $('#editRoleModal').modal('hide');
            toastr.error('Some input is not completed.', '', { timeOut: 3000 });
          }
        });
      } else {
        event.preventDefault();
      }
    });
  });

  // Select All checkbox click
  const selectAll = document.querySelector('#editSelectAll');
  selectAll.addEventListener('change', t => {
    var checkboxList = document.querySelectorAll('[type="checkbox"]');

    checkboxList.forEach(e => {
      e.checked = t.target.checked;
    });
  });
});
