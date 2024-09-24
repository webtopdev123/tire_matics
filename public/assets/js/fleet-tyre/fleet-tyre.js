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

  $('#filter-status').change(function(){
    getTableData();
  });

  $('#filter-type').change(function(){
    getTableData();
  });

});

function getTableData() {


  $.ajax({
    url: '/fleet-tyre/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'filter_type': $('#filter-type').val(),
      'filter_status': $('#filter-status').val(),
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {
        let formatter = new Intl.NumberFormat('en-US', {
          style: 'decimal',
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });

        var tableBody = "";

        response.data.forEach((e, i) => {
          tableBody += `
            <tr>
                <td class="align-center align-top">${i + 1}.</td>
                <td class="align-center align-top">
                  Serial No.: ${e['tyre_serial']}<br>
                  ODT: ${formatter.format(e['tyre_odt'])}mm<br>
                  Tyre Pressure: ${formatter.format(e['tyre_psi'])}psi<br>
                  Price: RM${formatter.format(e['tyre_price'])}<br>
                  Year: ${e['tyre_year']}<br>
                </td>
                <td class="align-center align-top">
                  ${e['tyre_brand']?e['tyre_brand']['brand_name']:''} (${e['tyre_brand_model']?e['tyre_brand_model']['model_name']:''})<br>
                  ${e['tyre_brand_model_size']?e['tyre_brand_model_size']['size_name']:''}
                </td>
                <td class="text-center align-top">${e['type_name']}</td>
                <td class="text-center align-top">${e['status_name']}</td>
                <td class="text-center align-top" style="white-space: nowrap;">
                  <button class="btn btn-sm btn-icon btnLog" data-id="${e['tyre_id']}"><i class="ti ti-eye"></i></button>
                  <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                  <button class="btn btn-sm btn-icon btnDelete" data-id="${e['tyre_id']}"><i class="ti ti-trash"></i></button>
                </td>
            </tr>`;
        });

        $('#main-table').DataTable().clear().destroy();
        $('#main-table tbody').html(tableBody);
        $('#main-table').DataTable({
          pageLength: 100,
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
          initComplete: function (settings, json) {
            if ($('#fleet_brand_list input.form-control[type="search"]').parent().is('label')) {
              $('#fleet_brand_list input.form-control[type="search"]').unwrap();
            }

          }

        });
      }
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });

}

function deleteItem(id) {

  $.ajax({
    url: '/fleet-tyre/delete',
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
        toastr.success('Deleted successfully.', '', { timeOut: 3000 });
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

function generateModelOption(modal, brand_id, selected_model_id = 0){
  // $('.sel-brand-model').html('');
  console.log(selected_model_id);
  console.log(modal);
  $.ajax({
    url: '/tyre-brand-model/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'brand_id': brand_id,
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {


        var option_html = "<option value=''>--SELECT--</option>";

        response.data.forEach((e, i) => {
          if(selected_model_id == e['model_id']){
            option_html += `<option value="${e['model_id']}" selected>${e['model_name']}</option>`;
          } else {
            option_html += `<option value="${e['model_id']}">${e['model_name']}</option>`;
          }

        });

        $(modal + ' .sel-brand-model').html(option_html);

        if(selected_model_id > 0){
          $(modal + ' .sel-brand-model').trigger('change');
        }
      }
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });
}


function generateSizelOption($modal, model_id, selected_size_id = 0){
  // $('.sel-brand-model-size').html('');

  $.ajax({
    url: '/tyre-brand-model-size/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'model_id': model_id,
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {


        var option_html = "<option value=''>--SELECT--</option>";

        response.data.forEach((e, i) => {
          if(selected_size_id == e['size_id']){
            option_html += `<option value="${e['size_id']}" selected>${e['size_name']}</option>`;
          } else {
            option_html += `<option value="${e['size_id']}">${e['size_name']}</option>`;
          }

        });

        $($modal + ' .sel-brand-model-size').html(option_html);
      }
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });
}

function fillSizeValue($modal, size_id){

  $.ajax({
    url: '/tyre-brand-model-size/get',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'size_id': size_id,
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {
        $($modal + ' input[name=tyre_odt]').val(response['data']['size_tread_depth']);
        $($modal + ' input[name=tyre_psi]').val(response['data']['size_psi']);
      }
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });
}
