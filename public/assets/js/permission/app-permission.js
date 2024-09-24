/**
 * App user list (jquery)
 */

'use strict';
const permissionCode='permission';
$(function () {

  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  });

  // Permissions List datatable initialize
  var dataTablePermissions = $('.datatables-permissions'), dt_permission,
    userList = baseUrl + 'app/members';
  if (dataTablePermissions.length) {
    dt_permission = dataTablePermissions.DataTable({
      serverSide: true,
      ajax: baseUrl + 'app/permission/list',
      columns: [
        { data: '' },
        { data: 'name' },
        { data: 'assigned_to' },
        { data: 'created_date' },
        { data: '' }
      ],
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">' + (meta.row+1) + '</span>';
          }
        },
        {
          // Permission Name
          targets: 1,
          orderable: true,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            return '<span class="text-nowrap">' + $name + '</span>';
          }
        },
        {
          // Assigned to
          className: 'text-center',
          targets: 2,
          orderable: false,
          width: '20%',
          render: function (data, type, full, meta) {
            var $assignedTo = full['assigned_to'],
              $output = '';
            for (var i = 0; i < $assignedTo.length; i++) {
              var val = $assignedTo[i];
              $output += '<a href="' + userList + '"><span class="badge bg-label-primary m-1">' + val + '</span></a>';
            }
            return '<span class="text-nowrap">' + $output + '</span>';
          }
        },
        {
          // created date
          targets: 3,
          className: 'text-center',
          orderable: true,
          width: '10%',
          render: function (data, type, full, meta) {
            var $date = full['created_date'];
            return '<span class="text-nowrap">' + $date + '</span>';
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
            var permissionId = full['permission_id'];
            var permissionName = full['name'];
            var permissionC = full['code'];

            var editData = ' data-permissionid="' + permissionId + '" data-name="' + permissionName + '" data-code="' + permissionC + '"';
            var editBtn='',deleteBtn='';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn='<button class="btn btn-sm btn-icon me-2" data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"' + editData + '><i class="ti ti-edit"></i></button>';
            }
            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_delete'] === 1) {
              deleteBtn='<button class="btn btn-sm btn-icon delete-record" data-permission-id="' + permissionId + '"><i class="ti ti-trash"></i></button>';
            }

            return (
              '<span class="text-nowrap">' + editBtn + deleteBtn +'</span>'
            );
          }
        }
      ],
      order: [
        [0, 'asc']
      ],
      dom: '<"row mx-1"' +
        '<"col-sm-12 col-md-3" f>' +
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
      // Add New Permission Button
      buttons: [{
        text: 'Add New',
        className: 'add-new btn btn-primary mb-3 mb-md-0 permission-create',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addPermissionModal'
        },
        init: function (api, node, config) {
          $(node).removeClass('btn-secondary');
        }
      }]
    });

    if (!permissionMap[permissionCode] || ( permissionMap[permissionCode]['permission_role_update'] !== 1 && permissionMap[permissionCode]['permission_role_delete'] !== 1 )) {
      dt_permission.column(4).visible(false);
    }

    var button = $(
      '<button class="btn btn-primary mb-2 create-btn" data-bs-target="#addPermissionModal" data-bs-toggle="modal" type="submit">Create</button>'
    );

    if (!permissionMap[permissionCode] || permissionMap[permissionCode]['permission_role_create'] !== 1) {
      $('.create-btn').remove();
    } else {
      $('.title-head').append(button);
    }

    $('#DataTables_Table_0 th:contains("Actions")').css(
      'text-align',
      'center'
    );

    if ($('input.form-control[type="search"]').parent().is('label')) {
      $('input.form-control[type="search"]').unwrap();
    }

  }

  // Delete Record
  $('.datatables-permissions tbody').on('click', '.delete-record', function () {

    Swal.fire({
      title: '',
      text: "Are you sure you want to delete this record?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        deletePermission($(this).attr('data-permission-id'));
      }
    })

  });


  // Filter form control to default size
  setTimeout(() => {
    //$('.dataTables_filter .form-control').removeClass('form-control-sm');
    //$('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  function deletePermission(permissionId) {

    $.ajax({
      url: '/app/permission/delete',
      type: 'POST',
      data: {
        permission_id: permissionId
      },
      success: function (response, status, xhr) {

        if (xhr.status === 200) {
          toastr.success('One record has successfully deleted.', '', { timeOut: 3000 });

          dt_permission.ajax.reload(null, false);
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
