'use strict';

const permissionCode = 'change_password';
let editPasswordFormValidator = null;

document.addEventListener('DOMContentLoaded', function (e) {
  // Validation
  editPasswordFormValidator = FormValidation.formValidation(document.getElementById('editPasswordForm'), {
    fields: {
      current_password: {
        validators: {
          notEmpty: {
            message: 'Please enter current password'
          }
        }
      },
      new_password: {
        validators: {
          notEmpty: {
            message: 'Please enter new password'
          }
        }
      },
      confirm_password: {
        validators: {
          notEmpty: {
            message: 'Please enter confirm password'
          }
        }
      },
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        // eleInvalidClass: '',
        eleValidClass: '',
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });
});

// Datatable (jquery)
$(function () {
  const select2 = $('.select2');

  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: '- Select -',
        dropdownParent: $this.parent()
      });
    });
  }

  var csrfToken = $('meta[name="csrf-token"]').attr('content');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  });
  // get member roles list

  var dataTableRoles = $('.datatables'),
    dt_merchant
  // Users List datatable
  if (dataTableRoles.length) {
    dt_merchant = dataTableRoles.DataTable({
      serverSide: true,
      pageLength:50,
      ajax: baseUrl + 'setting/list', // JSON file to add data
      columns: [
        // columns according to JSON
        {
          data: ''
        },
        {
          data: 'id'
        },
        {
          data: ''
        },
        {
          data: 'name'
        },
        {
          data: ''
        }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          targets: 1,
          searchable: false,
          visible: false
        },
        {
          targets: 2,
          width: '5%',
          orderable: false,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">'+(meta.row+1)+'.</span>';
          }
        },
        {
          // Name
          targets: 3,
          orderable: true,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            return '<span class="text-nowrap">' + $name + '</span>';
          }
        },
        {
          // Actions
          className: 'text-center',
          targets: -1,
          searchable: false,
          title: 'Actions',
          orderable: false,
          width: '10%',
          render: function (data, type, full, meta) {

            var editData =
              ' data-id="' +
              (full['id'] ? full['id'] : '') +
              '"';

            var editBtn = '';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn =
                '<button class="btn btn-sm btn-icon me-2" data-bs-target="#editPasswordModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
                editData +
                '><i class="ti ti-edit"></i></button>';
            }

            return '<span class="text-nowrap">' + editBtn + '</span>';
          }
        }
      ],
      order: [[4, 'desc']],
      dom:
        '<"row mx-1"' +
        '<"col-sm-12 col-md-3" f>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search..'
      },
      // Buttons with Dropdown
      buttons: [],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['merchant_name'];
            },
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function (settings, json) {
      }
    });

    if (
      !permissionMap[permissionCode] ||
      (permissionMap[permissionCode]['permission_role_update'] !== 1 &&
        permissionMap[permissionCode]['permission_role_delete'] !== 1)
    ) {
      dt_merchant.column(6).visible(false);
    }

    $('#DataTables_Table_0_filter label')
      .contents()
      .filter(function () {
        return this.nodeType === 3; // Filter out text nodes
      })
      .remove();

    if ($('input.form-control[type="search"]').parent().is('label')) {
      $('input.form-control[type="search"]').unwrap();
    }
  }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// set modal data when edit
document.getElementById('editPasswordModal').addEventListener('show.bs.modal', function (e) {

  $('#editPasswordModal input[type!="checkbox"], #editPasswordModal textarea').val('');

  $.each(e.relatedTarget.dataset, function (key, value) {
    if (value !== 'undefined' && value !== '') {
      $('#editPasswordModal input[name="' + key + '"]').val(value);
      $('#editPasswordModal textarea[name="' + key + '"]').val(value);
    }
  });


});

$('#editPasswordForm button[type="submit"]').on('click', function (event) {
  editPasswordFormValidator.validate().then(function (status) {
    if (status === 'Valid') {

      var formData = new FormData($('#editPasswordForm')[0]);

      $.ajax({
        url: '/setting/update-password',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response, status, xhr) {
          $('#editPasswordModal').modal('hide');

          if (xhr.status === 200) {
            toastr.success('Password has successfully Updated.', '', { timeOut: 3000 });

            var dt_merchant = $('.datatables').DataTable();
            dt_merchant.ajax.reload(null, false);
          }
        },
        error: function (xhr) {
          console.log(xhr);
          
          $('#editPasswordModal').modal('hide');

          if (xhr.status == 422) {
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
