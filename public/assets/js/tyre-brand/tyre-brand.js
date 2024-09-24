let dataTable_obj = null;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var current_brand_id = 0;
var current_size_id = 0;
var flag_modal = true;

jQuery(function () {
    // catch event close edit model to open the model popup
    $('#updatemodelModal, #sizelistModal').on('hidden.bs.modal', function () {
      console.log(current_brand_id);
      if(current_brand_id != 0 && flag_modal){
        console.log("ok");
        $('#brand_id-' + current_brand_id).trigger('click');
      }
    });

    $('#updatesizeModal').on('hidden.bs.modal', function () {
      if(current_size_id != 0){
        $('#size_id-' + current_size_id).trigger('click');
        flag_modal = true;
      }
    });

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
    url: '/tyre-brand/list',
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
                        <td class="align-middle">${e['brand_name']}</td>
                        <td class="text-center  style="white-space: nowrap;">${e['total_models']}</td>
                        <td class="" style="white-space: nowrap;">
                          <button class="btn btn-primary btnModels" id="brand_id-${e['brand_id']}" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'>Model</button>
                          <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                          <button class="btn btn-sm btn-icon btnDelete" data-id="${e['brand_id']}"><i class="ti ti-trash"></i></button>
                        </td>
                    </tr>`;
        });

        $('#main-table').DataTable().clear().destroy();
        $('#main-table tbody').html(tableBody);
        $('#main-table').DataTable({
          pageLength: 100,
          order: [[0 ,'asc']],
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
          // Add New Level Button
          buttons: [],
        });

        if ($('#tyre_brand_list input.form-control[type="search"]').parent().is('label')) {
          $('#tyre_brand_list input.form-control[type="search"]').unwrap();
        }
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
    url: '/tyre-brand/delete',
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


