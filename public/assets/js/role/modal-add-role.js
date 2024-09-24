/**
 * Add new role Modal JS
 */

'use strict';
let addFormValidator = null;
document.addEventListener('DOMContentLoaded', function (e) {
  //get permission list
  $.ajax({
    url: '/setting/role/get-permission-list',
    type: 'GET',
    data: {},
    success: function (response) {
      console.log(response);
      
      var previousSubmenuName = null;

      $.each(response, function (index, item) {
        var submenuName = '';
        // Check if the current submenu_name is not empty and is different from the previous one
        if (item.submenu_name !== undefined && item.submenu_name !== null && item.submenu_name !== '' && item.submenu_name !== previousSubmenuName) {
          submenuName = `<tr>
                          <td class="text-nowrap fw-medium">${item.submenu_name}</td>
                          <td>
                            <div class="d-flex"></div>
                          </td>
                        </tr>`;
          previousSubmenuName = item.submenu_name; // Update previousSubmenuName with the current value
        }

        var readCheckBox = `<div class="form-check me-3 me-lg-5">
                        <input class="form-check-input" type="checkbox" id="perRead${index}" name="permissions[${item.permission_id}][read]" value="1" />
                        <label class="form-check-label" for="perRead${index}">
                          Read
                        </label>
                      </div>`;

        var createCheckBox = `<div class="form-check me-3 me-lg-5">
                          <input class="form-check-input" type="checkbox" id="percreate${index}" name="permissions[${item.permission_id}][create]" value="1" />
                          <label class="form-check-label" for="percreate${index}">
                            Create
                          </label>
                        </div>`;

        var updateCheckBox = `<div class="form-check me-3 me-lg-5">
                          <input class="form-check-input" type="checkbox" id="perUpdate${index}" name="permissions[${item.permission_id}][update]" value="1" />
                          <label class="form-check-label" for="perUpdate${index}">
                            Update
                          </label>
                        </div>`;

        var deleteCheckBox = `<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="perDelete${index}" name="permissions[${item.permission_id}][delete]" value="1" />
                          <label class="form-check-label" for="perDelete${index}">
                            Delete
                          </label>
                        </div>`;

        if (item.permission_read != '1') readCheckBox = '';

        if (item.permission_create != '1') createCheckBox = '';

        if (item.permission_update != '1') updateCheckBox = '';

        if (item.permission_delete != '1') deleteCheckBox = '';

        if (item.submenu_name !== undefined && item.submenu_name !== null && item.submenu_name !== '') {
          var permissionName = `<td class="text-nowrap fw-medium">|--&nbsp;${item.submenu_sub_name}</td>`;
        } else {
          var permissionName = `<td class="text-nowrap fw-medium">${item.name}</td>`;
        }
        // this tr for add modal
        var tr = `${submenuName}
                  <tr code=${item.code}>
                    ${permissionName}
                    <td>
                      <div class="d-flex">
                        ${readCheckBox}
                        ${createCheckBox}
                        ${updateCheckBox}
                        ${deleteCheckBox}
                      </div>
                    </td>
                  </tr>`;

        readCheckBox = `<div class="form-check me-3 me-lg-5">
                    <input class="form-check-input" type="checkbox" id="editPerRead${index}" name="permissions[${item.permission_id}][read]" value="1" />
                    <label class="form-check-label" for="editPerRead${index}">
                      Read
                    </label>
                  </div>`;

        createCheckBox = `<div class="form-check me-3 me-lg-5">
                      <input class="form-check-input" type="checkbox" id="editPercreate${index}" name="permissions[${item.permission_id}][create]" value="1" />
                      <label class="form-check-label" for="editPercreate${index}">
                        Create
                      </label>
                    </div>`;

        updateCheckBox = `<div class="form-check me-3 me-lg-5">
                      <input class="form-check-input" type="checkbox" id="editPerUpdate${index}" name="permissions[${item.permission_id}][update]" value="1" />
                      <label class="form-check-label" for="editPerUpdate${index}">
                        Update
                      </label>
                    </div>`;

        deleteCheckBox = `<div class="form-check">
                      <input class="form-check-input" type="checkbox" id="editPerDelete${index}" name="permissions[${item.permission_id}][delete]" value="1" />
                      <label class="form-check-label" for="editPerDelete${index}">
                        Delete
                      </label>
                    </div>`;

        if (item.permission_read != '1') readCheckBox = '';

        if (item.permission_create != '1') createCheckBox = '';

        if (item.permission_update != '1') updateCheckBox = '';

        if (item.permission_delete != '1') deleteCheckBox = '';

        var tr1 = `${submenuName}
                    <tr code="${item.code}">
                      ${permissionName}
                      <td>
                        <div class="d-flex">
                          ${readCheckBox}
                          ${createCheckBox}
                          ${updateCheckBox}
                          ${deleteCheckBox}
                        </div>
                      </td>
                    </tr>`;

        $('#addRoleForm #permissionTable tbody').append(tr);
        $('#editRoleForm #permissionTable tbody').append(tr1);

        var targetRow = $('#addRoleForm #permissionTable tbody tr[code="shift"]');
        var secondRow = $('#addRoleForm #permissionTable tbody tr:eq(1)');

        targetRow.insertBefore(secondRow);

        var targetRow1 = $('#editRoleForm #permissionTable tbody tr[code="shift"]');
        var secondRow1 = $('#editRoleForm #permissionTable tbody tr:eq(1)');

        targetRow1.insertBefore(secondRow1);

      });
    },
    error: function (xhr, status, error) {
      console.log(xhr);
      
      // console.error('Error:', error);
    }
  });

  // Add Form validation
  addFormValidator = FormValidation.formValidation(document.getElementById('addRoleForm'), {
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

  // Select All checkbox click
  const selectAll = document.querySelector('#selectAll');
  selectAll.addEventListener('change', t => {
    var checkboxList = document.querySelectorAll('[type="checkbox"]');

    checkboxList.forEach(e => {
      e.checked = t.target.checked;
    });
  });

  // empty modal when click add new
  document.getElementById('addRoleModal').addEventListener('show.bs.modal', function (e) {
    $('#addRoleModal #modalRoleName').val('');
    $('#addRoleModal #permissionTable input[type="checkbox"]').prop('checked', false);
  });

  // save role
  $('#addRoleForm button[type="submit"]').on('click', function (event) {
    addFormValidator.validate().then(function (status) {
      if (status === 'Valid') {
        var formData = $('#addRoleForm').serializeArray();
        $.ajax({
          type: 'POST',
          url: '/setting/role/add',
          data: formData,
          success: function (response) {
            $('#addRoleModal').modal('hide');
            toastr.success('One record has successfully added.', '', { timeOut: 3000 });

            var dt_roles = $('.datatables-roles').DataTable();
            dt_roles.ajax.reload(null, false);
          },
          error: function (error) {
            $('#addRoleModal').modal('hide');

            toastr.error('Some input is not completed.', '', { timeOut: 3000 });
          }
        });
      } else {
        event.preventDefault();
      }
    });
  });
});
