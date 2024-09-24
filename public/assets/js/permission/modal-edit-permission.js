/**
 * Add new role Modal JS
 */

'use strict';
document.addEventListener('DOMContentLoaded', function () {

  // set modal data when edit
  document.getElementById('editPermissionModal').addEventListener('show.bs.modal', function (e) {

    $('#editPermissionModal #permission_id').val('');

    const id = e.relatedTarget.dataset.permissionid;
    const name = e.relatedTarget.dataset.name;
    const code = e.relatedTarget.dataset.code;

    $('#editPermissionModal #editPermissionName').val(name);
    $('#editPermissionModal #editPermissionCode').val(code);
    $('#editPermissionModal #permission_id').val(id);
  });

  // update permission
  $('#editPermissionModal button[type="submit"]').on('click', function (event) {

    event.preventDefault();
    $.ajax({
      url: '/app/permission/edit',
      type: 'POST',
      data: {
        permission_id: $('#editPermissionModal #permission_id').val(),
        permission_name: $('#editPermissionModal #editPermissionName').val(),
        permission_code: $('#editPermissionModal #editPermissionCode').val()
      },
      success: function (response, status, xhr) {

        $('#editPermissionModal').modal('hide');
        if (xhr.status === 200) {
          toastr.success('One record has successfully Updated.', '', { timeOut: 3000 });
         
          var dt_permission = $('.datatables-permissions').DataTable();
          dt_permission.ajax.reload(null, false);
        }

      },
      error: function (xhr, status, error) {

        $('#editPermissionModal').modal('hide');
        toastr.error('Some input is not completed.', '', { timeOut: 3000 });

      }
    });


  });

});

