/**
 * App user list
 */

'use strict';
const permissionCode = 'setting-role';
// Datatable (jquery)
$(function () {
  var csrfToken = $('meta[name="csrf-token"]').attr('content');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  });

  // roles list datatable initialize
  var dataTableRoles = $('.datatables-roles'),
    dt_roles;
  // Users List datatable
  if (dataTableRoles.length) {
    dt_roles = dataTableRoles.DataTable({
      serverSide: true,
      pageLength: 100,
      ajax: baseUrl + 'setting/role/list',
      columns: [
        {
          data: ''
        },
        {
          data: 'role_id'
        },
        {
        },
        {
          data: 'role_name'
        },
        {
          data: 'users_count'
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
          targets: 3,
          orderable: true,
          render: function (data, type, full, meta) {
            var $name = full['role_name'];
            return '<span class="text-nowrap">' + $name + '</span>';
          }
        },
        {
          targets: 4,
          className: 'text-center',
          orderable: true,
          width: '20%',
          render: function (data, type, full, meta) {
            var $count = full['users_count'];
            return '<span class="text-nowrap">' + $count + '</span>';
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
            var rolesId = full['role_id'];
            var rolesName = full['role_name'];
            var permissionsData = JSON.stringify(full['permissions']);

            var editData =
              ' data-role_id="' +
              rolesId +
              '" data-name="' +
              rolesName +
              '"data-permissions=\'' +
              permissionsData +
              "'";
            var editBtn = '',
              deleteBtn = '';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn =
                '<button class="btn btn-sm btn-icon me-2" data-bs-target="#editRoleModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
                editData +
                '><i class="ti ti-edit"></i></button>';
            }
            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_delete'] === 1) {
              deleteBtn =
                '<button class="btn btn-sm btn-icon delete-record" data-role_id="' +
                rolesId +
                '"><i class="ti ti-trash"></i></button>';
            }

            return '<span class="text-nowrap">' + editBtn + deleteBtn + '</span>';
          }
        }
      ],
      order: [[4, 'asc']],
      dom:
        '<"row mx-1"' +
        '<"col-sm-12 col-md-3 col-xl-2" f>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      },
      // add New role button
      buttons: [
        {
          text: 'Add New',
          className: 'add-new btn btn-primary mb-3 mb-md-0 permission-create',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addRoleModal'
          },
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['role_name'];
            }
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
      }
    });

    if (!permissionMap[permissionCode] || permissionMap[permissionCode]['permission_role_create'] !== 1) {
      $('.permission-create').css('display', 'none');
    }
    if (
      !permissionMap[permissionCode] ||
      (permissionMap[permissionCode]['permission_role_update'] !== 1 &&
        permissionMap[permissionCode]['permission_role_delete'] !== 1)
    ) {
      dt_roles.column(6).visible(false);
    }

    var button = $(
      '<button class="btn btn-primary mb-2 create-btn" data-bs-target="#addRoleModal" data-bs-toggle="modal" type="submit">Create</button>'
    );

    if (!permissionMap[permissionCode] || permissionMap[permissionCode]['permission_role_create'] !== 1) {
      $('.create-btn').remove();
    } else {
      $('.title-head').append(button);
    }

    if ($('input.form-control[type="search"]').parent().is('label')) {
      $('input.form-control[type="search"]').unwrap();
    }
  }

  // Delete Record
  // $('.datatables-roles tbody').on('click', '.delete-record', function () {
  //   Swal.fire({
  //     title: '',
  //     text: 'Are you sure you want to delete this record?',
  //     icon: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'Delete',
  //     cancelButtonText: 'Cancel'
  //   }).then(result => {
  //     if (result.isConfirmed) {
  //       deleteRole($(this).attr('data-role_id'));
  //     }
  //   });
  // });

  // Filter form control to default size
  // setTimeout(() => {
  //   $('.dataTables_filter .form-control').removeClass('form-control-sm');
  //   $('.dataTables_length .form-select').removeClass('form-select-sm');
  // }, 300);

  $(document).on('click', '.delete-record', function () {
    var data_id = $(this).attr('data-role_id');
    handleDelete(data_id);
  });

  function handleDelete(data_id) {
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        didOpen: () => {
            $('.swal2-deny').remove();
        }
    }).then(result => {
        if (result.isConfirmed) {
          deleteData(data_id);
          $('.modal').modal('hide');
        }
    });
  }

  function deleteData(data_id) {
    $.ajax({
      url: '/setting/role/delete',
      type: 'POST',
      data: {
        role_id: data_id
      },
      success: function (response, status, xhr) {
        if (xhr.status === 200) {
          toastr.success('One record has successfully deleted.', '', { timeOut: 3000 });

          dt_roles.ajax.reload(null, false);
        }
      },
      error: function (xhr, status, error) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
          toastr.error(xhr.responseJSON.message, '', { timeOut: 3000 });
        }
      }
    });
  }

});
