let dt_user = null;
var csrfToken = $('meta[name="csrf-token"]').attr('content');

jQuery(function () {

  getTableData();

  $('#main-table tbody').on('click', '.btnDelete', function () {
    Swal.fire({
      title: '',
      text: "Are you sure you want to delete this record?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#f1f1f2',
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-label-danger',
        cancelButton: 'btn btn-label-secondary'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        deleteItem($(this).attr('data-id'));
      }
    })

  });
});

function getTableData() {
  $.ajax({
    url: '/fleet-good/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'filter_group_id': $('#filter-group').val(),
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {

        console.log(response);

        var tableBody = "";

        response.data.forEach((e, i) => {
          tableBody += `
                    <tr>
                        <td class="align-middle">${i + 1}.</td>
                        <td class="align-middle">${e['goods_name']}</td>
                        <td class="d-flex justify-content-center align-items-center" style="white-space: nowrap;">
                          <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                          <button class="btn btn-sm btn-icon btnDelete" data-id="${e['goods_id']}"><i class="ti ti-trash"></i></button>
                        </td>
                    </tr>`;
        });

        $('#main-table').DataTable().clear().destroy();
        $('#main-table tbody').html(tableBody);
        $('#main-table').DataTable({
          pageLength: 100,
          // dom: 'rti',
          // ordering: false,
          autoWidth: false,
          paging: false,            // Disable pagination
          lengthChange: false,
          order: [],
          dom:
            '<"row mx-1 justify-content-between align-items-center"' +
            '<"col-sm-12 col-md-3 col-xl-2" f>' +
            '<"col-sm-12 col-md-4 col-xl-4 text-end"B>' +
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

          ],
          columns: [
            { width: '30px' },
            {},
            { width: '40px', orderable: false },
          ],
          initComplete: function (settings, json) {
            if ($('#fleet_brand_list input.form-control[type="search"]').parent().is('label')) {
              $('#fleet_brand_list input.form-control[type="search"]').unwrap();
            }

           // $('#fleet_brand_list .dataTables_filter .form-control').removeClass('form-control-sm');
            //$('#fleet_brand_list .dataTables_length .form-select').removeClass('form-select-sm');
          }

        });
      }
    },
    error: function (xhr, status, error) {

      // if (xhr.responseJSON && xhr.responseJSON.message) {

      //     $('.alert').html(xhr.responseJSON.message);
      //     $('.alert').removeClass('d-none');

      //     setTimeout(function() {
      //         $('.alert').addClass('d-none');
      //     }, 3000);
      // }
      console.log(xhr);
    }
  });


}

function deleteItem(id) {

  $.ajax({
    url: '/fleet-good/delete',
    type: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
    },
    data: {
      id: id
    },
    success: function (response, status, xhr) {

      if (xhr.status === 200) {

        $('.alert').html('Delete Succussfully');
        $('.alert').removeClass('d-none');

        setTimeout(function () {
          $('.alert').addClass('d-none');
        }, 3000);

        getTableData();
      }

    },
    error: function (xhr, status, error) {

      console.log(xhr);

      if (xhr.responseJSON && xhr.responseJSON.message) {

        $('.alert').html(xhr.responseJSON.message);
        $('.alert').removeClass('d-none');

        setTimeout(function () {
          $('.alert').addClass('d-none');
        }, 3000);
      }
    }
  });
}
