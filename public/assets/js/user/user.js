/**
 * Page User List
 */

'use strict';
let dt_user = null;
const permissionCode = 'setting-user';

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

  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    userView = baseUrl + 'user',
    statusObj = {
      2: { title: 'Pending', class: 'bg-label-warning' },
      1: { title: 'Active', class: 'bg-label-success' },
      0: { title: 'Inactive', class: 'bg-label-secondary' }
    };

  // member list datatable initialize
  if (dt_user_table.length) {
    dt_user = dt_user_table.DataTable({
      serverSide: true,
      pageLength: 100,
      ajax: baseUrl + 'setting/user/list',
      columns: [{ data: '' }, { data: 'id' }, { data: '' }, { data: 'name' }, { data: 'role_name' }, { data: 'status' }, { data: 'action' }],
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
          // member full name and email
          targets: 3,
          className: 'text-left',
          orderable: false,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['name'],
              $image = full['avatar'];
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['name'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="text-body text-truncate"><span class="fw-medium">' +
              $name +
              '</span></span>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // member Role
          className: 'text-center',
          targets: 4,
          orderable: false,
          width: '10%',
          render: function (data, type, full, meta) {
            return full['role_name'];
          }
        },
        {
          // member Status
          className: 'text-center',
          targets: 5,
          orderable: false,
          width: '10%',
          render: function (data, type, full, meta) {
            var $status = full['status'];

            return (
              '<span class="badge ' +
              statusObj[$status].class +
              '" text-capitalized>' +
              statusObj[$status].title +
              '</span>'
            );
          }
        },
        {
          // Actions
          className: 'text-center',
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          width: '10%',
          render: function (data, type, full, meta) {
            var userIid = full['id'];

            var editData =
              ' data-user_id="' +
              full['id'] +
              '" data-name=\'' +
              full['name'] +
              "' data-merchant_id='" +
              full['merchant_id'] +
              "' data-role_id='" +
              full['role_id'] +
              "' data-status='" +
              full['status'] +
              "' data-email='" +
              full['email'] +
              "'";

            var editBtn = '',
              deleteBtn = '';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn =
                '<button class="btn btn-sm btn-icon me-2" data-bs-target="#editModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
                editData +
                '><i class="ti ti-edit"></i></button>';
            }

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_delete'] === 1 && userIid !== 1 && userIid !== userAuthId) {
              deleteBtn =
                '<button class="btn btn-sm btn-icon delete-record" data-user_id="' +
                userIid +
                '"><i class="ti ti-trash"></i></button>';
            }

            return '<span class="text-nowrap">' + editBtn + deleteBtn + '</span>';
          }
        }
      ],
      order: [[5, 'desc']],
      dom:
      '<"row mx-1"' +
      '<"col-sm-12 col-md-3 col-xl-2"f>' +
      '>t' +
      '<"row mx-2"' +
      '<"col-sm-12 col-md-6"i>' +
      '<"col-sm-12 col-md-6"p>' +
      '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      },
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
      },
      // add new member button
      buttons: [
      ]
    });

    if (
      !permissionMap[permissionCode] ||
      (permissionMap[permissionCode]['permission_role_update'] !== 1 &&
        permissionMap[permissionCode]['permission_role_delete'] !== 1)
    ) {
      dt_user.column(7).visible(false);
    }

    var button = $(
      '<button class="btn btn-primary mb-2 create-btn" data-bs-target="#addModal" data-bs-toggle="modal" type="submit">Create</button>'
    );

    if (!permissionMap[permissionCode] || permissionMap[permissionCode]['permission_role_create'] !== 1) {
      $('.create-btn').remove();
    } else {
      $('.title-head').append(button);
    }

    $('#DataTables_Table_0 th:contains("Status"), #DataTables_Table_0 th:contains("Actions")').css(
      'text-align',
      'center'
    );

    if ($('input.form-control[type="search"]').parent().is('label')) {
      $('input.form-control[type="search"]').unwrap();
    }
  }

  // Filter form control to default size
  // setTimeout(() => {
  //   $('.dataTables_filter .form-control').removeClass('form-control-sm');
  //   $('.dataTables_length .form-select').removeClass('form-select-sm');
  // }, 300);

  $(document).on('click', '.delete-record', function () {
    var data_id = $(this).attr('data-user_id');
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
      url: '/setting/user/delete',
      type: 'POST',
      data: {
        user_id: data_id
      },
      success: function (response, status, xhr) {
        if (xhr.status === 200) {
          toastr.success('One record has successfully deleted.', '', { timeOut: 3000 });

          dt_user.ajax.reload(null, false);
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
