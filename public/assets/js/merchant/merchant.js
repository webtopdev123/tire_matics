/**
 * App user list
 */

'use strict';

const permissionCode = 'merchant';
let addFormValidator = null;
let editFormValidator = null;

document.addEventListener('DOMContentLoaded', function (e) {
  // Add validation
  addFormValidator = FormValidation.formValidation(document.getElementById('addForm'), {
    fields: {
      merchant_name: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant name'
          }
        }
      },
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter your login username'
          },
          stringLength: {
            min: 6,
            message: 'Password must be at least 6 characters long'
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
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        // eleInvalidClass: '',
        eleValidClass: ''
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  editFormValidator = FormValidation.formValidation(document.getElementById('editForm'), {
    fields: {
      merchant_name: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant name'
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

  // $("#addForm input[name='merchant_url']").on('input', function() {
  //   $("#addForm #url_change").html($(this).val());
  // });

  // $("#editForm input[name='merchant_url']").on('input', function() {
  //   $("#editForm #url_change").html($(this).val());
  // });

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
    dt_merchant,
    userList = baseUrl + 'merchant-list';
  // Users List datatable
  if (dataTableRoles.length) {
    dt_merchant = dataTableRoles.DataTable({
      serverSide: true,
      pageLength: 100,
      ajax: baseUrl + 'app/merchant-list', // JSON file to add data
      columns: [
        // columns according to JSON
        {
          data: ''
        },
        {
          data: 'name'
        },
        {
          data: 'created_date'
        },
        {
          data: ''
        }
      ],
      columnDefs: [
        {
          targets: 0,
          width: '5%',
          orderable: false,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">'+(meta.row+1)+'.</span>';
          }
        },
        {
          // Name
          targets: 1,
          orderable: true,
          render: function (data, type, full, meta) {
            var $name = full['merchant_name'];
            return '<span class="text-nowrap">' + $name + '</span>';
          }
        },
        {
          // remove ordering from Name
          targets: 2,
          orderable: true,
          className: 'text-center',
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

            var editData =
              ' data-merchant_id="' +
              (full['merchant_id'] ? full['merchant_id'] : '') +
              '"' +
              ' data-merchant_name="' +
              (full['merchant_name'] ? full['merchant_name'] : '') +
              '"' +
              ' data-status="' +
              (full['status'] ? full['status'] : '') +
              '"';

            var editBtn = '',deleteBtn = '',merchantAccessBtn='';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn =
                '<button class="btn btn-sm btn-icon me-2" data-bs-target="#editModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
                editData +
                '><i class="ti ti-edit"></i></button>';
            }
            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_delete'] === 1) {
              deleteBtn =
                '<button class="btn btn-sm btn-icon delete-record" data-merchant-id="' +
                full['merchant_id'] +
                '"><i class="ti ti-trash"></i></button>';
            }

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_read'] === 1) {
              merchantAccessBtn =`<a href="/app/switch-merchant/${full['merchant_id']}" class="btn btn-sm btn-icon me-2">
                                    <i class="ti ti-login" style="transform:scaleX(-1);"></i>
                                  </a>`;
            }

            return '<span class="text-nowrap">' + editBtn + deleteBtn + merchantAccessBtn + '</span>';
          }
        }
      ],
      order: [[0, 'desc']],
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

    var button = $(
      '<button class="btn btn-primary mb-2 create-btn" data-bs-target="#addModal" data-bs-toggle="modal" type="submit">Create</button>'
    );

    if (!permissionMap[permissionCode] || permissionMap[permissionCode]['permission_role_create'] !== 1) {
      $('.create-btn').remove();
    } else {
      $('.title-head').append(button);
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
    //$('.dataTables_filter .form-control').removeClass('form-control-sm');
    //$('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  $(document).on('click', '.delete-record', function () {
    var data_id = $(this).attr('data-merchant-id');
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
      url: '/app/merchant/' + data_id,
      type: 'DELETE',
      data: {
        merchant_id: data_id
      },
      success: function (response, status, xhr) {
        if (xhr.status === 200) {
          toastr.success('One record has successfully deleted.', '', { timeOut: 3000 });
          dt_merchant.ajax.reload(null, false);
          reloadMerchantSwitch();
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

function reloadMerchantSwitch() {
  $.ajax({
      url: '/app/merchant-render',
      method: 'GET',
      success: function(response) {
          $('#merchantSwitch').html(response);
      },
      error: function(error) {
          console.error('Error:', error);
      }
  });
}

document.getElementById('addModal').addEventListener('show.bs.modal', function (e) {
  $('#addModal input[type!="checkbox"], #addModal textarea').val('');
});

$('#addForm button[type="submit"]').on('click', function (event) {
  addFormValidator.validate().then(function (status) {
    if (status === 'Valid') {

      $('#addForm button[type="submit"]').attr('disabled',true);

      const formData = new FormData(document.getElementById('addForm'));

      $.ajax({
        type: 'POST',
        url: '/app/merchant',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response, status, xhr) {
          $('#addForm button[type="submit"]').attr('disabled',false);
          $('#addModal').modal('hide');

          if (xhr.status === 200) {
            toastr.success('One record has successfully added.', '', { timeOut: 3000 });

            var dt_merchant = $('.datatables').DataTable();
            dt_merchant.ajax.reload(null, false);
            reloadMerchantSwitch();
          }
        },
        error: function (xhr) {
          console.log(xhr);
          $('#addForm button[type="submit"]').attr('disabled',false);
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

// set modal data when edit
document.getElementById('editModal').addEventListener('show.bs.modal', function (e) {

  $('#editModal input[type!="checkbox"], #editModal textarea').val('');

  $.each(e.relatedTarget.dataset, function (key, value) {
    if (value !== 'undefined' && value !== '') {

      if (key == 'merchant_logo') {
        $('#merchant_logo_image').attr('src','/'+value)
      }
      else if(key == 'merchant_priceshow'){

        if(value == '1')
          $('#editModal input[name="' + key + '"]').prop('checked',true);
        else
          $('#editModal input[name="' + key + '"]').prop('checked',false);
      }
      else{
        $('#editModal input[name="' + key + '"]').val(value);
        $('#editModal textarea[name="' + key + '"]').val(value);
      }
    }
  });


});

$('#editForm button[type="submit"]').on('click', function (event) {
  editFormValidator.validate().then(function (status) {
    if (status === 'Valid') {

      var formData = new FormData($('#editForm')[0]);

      var merchant_id=$('#editForm #merchant_id').val();

      $.ajax({
        url: '/app/merchant/update/' + merchant_id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response, status, xhr) {
          $('#editModal').modal('hide');

          if (xhr.status === 200) {
            toastr.success('One record has successfully Updated.', '', { timeOut: 3000 });

            var dt_merchant = $('.datatables').DataTable();
            dt_merchant.ajax.reload(null, false);
            reloadMerchantSwitch();
          }
        },
        error: function (xhr) {
          $('#editModal').modal('hide');

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
