/**
 * App user list
 */

'use strict';

const permissionCode = 'merchant_edit';
let editFormValidator = null;

document.addEventListener('DOMContentLoaded', function (e) {
  // Validation
  editFormValidator = FormValidation.formValidation(document.getElementById('editForm'), {
    fields: {
      // merchant_logo: {
      //   validators: {
      //     notEmpty: {
      //       message: 'Please select logo'
      //     }
      //   }
      // },
      merchant_url: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant url'
          }
        }
      },
      merchant_whatsapp: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant merchant whatsapp url'
          },
          uri: {
            message: 'The URL is not valid',
            scheme: ['https']
          }
        }
      },
      merchant_facebook: {
        validators: {
          uri: {
            message: 'The URL is not valid',
            scheme: ['https']
          }
        }
      },
      merchant_instagram: {
        validators: {
          uri: {
            message: 'The URL is not valid',
            scheme: ['https']
          }
        }
      },
      merchant_tiktok: {
        validators: {
          uri: {
            message: 'The URL is not valid',
            scheme: ['https']
          }
        }
      },
      merchant_skincolor: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant merchant skincolor'
          }
        }
      },
      merchant_email: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant merchant email'
          }
        }
      },
      merchant_phone: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant merchant phone number'
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
    dt_merchant,
    userList = baseUrl + 'merchant-list';
  // Users List datatable
  if (dataTableRoles.length) {
    dt_merchant = dataTableRoles.DataTable({
      serverSide: true,
      pageLength: 100,
      ajax: baseUrl + 'setting/merchant-list', // JSON file to add data
      columns: [
        // columns according to JSON
        {
          data: ''
        },
        {
          data: 'merchant_id'
        },
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
            var $name = full['merchant_name'];
            return '<span class="text-nowrap">' + $name + '</span>';
          }
        },
        {
          // remove ordering from Name
          targets: 4,
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
              ' data-merchant_url="' +
              (full['merchant_url'] ? full['merchant_url'] : '') +
              '"' +
              ' data-merchant_whatsapp="' +
              (full['merchant_whatsapp'] ? full['merchant_whatsapp'] : '') +
              '"' +
              ' data-status="' +
              (full['status'] ? full['status'] : '') +
              '"' +
              ' data-merchant_facebook="' +
              (full['merchant_facebook'] ? full['merchant_facebook'] : '') +
              '"' +
              ' data-merchant_instagram="' +
              (full['merchant_instagram'] ? full['merchant_instagram'] : '') +
              '"' +
              ' data-merchant_tiktok="' +
              (full['merchant_tiktok'] ? full['merchant_tiktok'] : '') +
              '"' +
              ' data-merchant_priceshow="' +
              (full['merchant_priceshow'] ? full['merchant_priceshow'] : '') +
              '"' +
              ' data-merchant_logo="' +
              (full['merchant_logo'] ? full['merchant_logo'] : '') +
              '"' +
              ' data-merchant_email="' +
              (full['merchant_email'] ? full['merchant_email'] : '') +
              '"' +
              ' data-merchant_phone="' +
              (full['merchant_phone'] ? full['merchant_phone'] : '') +
              '"' +
              ' data-merchant_skincolor="' +
              (full['merchant_skincolor'] ? full['merchant_skincolor'] : '') +
              '"';

            var editBtn = '';

            if (permissionMap[permissionCode] && permissionMap[permissionCode]['permission_role_update'] === 1) {
              editBtn =
                '<button class="btn btn-sm btn-icon me-2" data-bs-target="#editModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
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
    //$('.dataTables_filter .form-control').removeClass('form-control-sm');
    //$('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

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

// set modal data when edit
document.getElementById('editModal').addEventListener('show.bs.modal', function (e) {

  $('#editModal input[type!="checkbox"], #editModal textarea').val('');
  $('#editModal #merchant_logo').val('');
  $('#editModal #display_merchant_logo').attr('src','');

  const merchantLogoImage = e.relatedTarget.dataset.merchant_logo;
  const merchantColor = e.relatedTarget.dataset.merchant_skincolor;
  const merchantEmail = e.relatedTarget.dataset.merchant_email;
  const merchantPhone = e.relatedTarget.dataset.merchant_phone;

  $.each(e.relatedTarget.dataset, function (key, value) {
    if (value !== 'undefined' && value !== '') {
      if(key == 'merchant_priceshow'){
        if(value == '1')
          $('#editModal input[name="' + key + '"]').prop('checked',true);
        else
          $('#editModal input[name="' + key + '"]').prop('checked',false);
      } else{
        $('#editModal input[name="' + key + '"]').val(value);
        $('#editModal textarea[name="' + key + '"]').val(value);
      }

      if (key == 'merchant_url') {
        $('#url_change').text(value);
      }

      $('#editModal #merchant_email').val(merchantEmail);
      $('#editModal #merchant_phone').val(merchantPhone);
      $('#editModal #merchant_skincolor').val(merchantColor);

      $('#editModal #display_merchant_logo').attr('src','/'+merchantLogoImage);
    }
  });
});

$('#editForm button[type="submit"]').on('click', function (event) {
  editFormValidator.validate().then(function (status) {
    if (status === 'Valid') {

      var formData = new FormData($('#editForm')[0]);

      var merchant_id=$('#editForm #merchant_id').val();

      $.ajax({
        url: '/setting/update-merchant/' + merchant_id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response, status, xhr) {
          $('#editModal').modal('hide');

          if (xhr.status === 200) {
            toastr.success('Record has successfully Updated.', '', { timeOut: 3000 });

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
