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

});

let formatter = new Intl.NumberFormat('en-US', {
  style: 'decimal',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

function getTableData() {
  $.ajax({
    url: '/fleet/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'filter_status': $('#filter-status').val(),
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {

        console.log(response);

        var tableBody = "";

        response.data.forEach((e, i) => {
          tableBody += `
                    <tr>
                        <td class="align-center align-top">${i + 1}.</td>
                        <td class="align-center align-top">
                          Plate No: ${e['fleet_plate']}<br>
                          Code: ${e['fleet_code']}<br>
                          Configuration: ${e['fleet_configuration']?e['fleet_configuration']['configuration_type'] + ' (' + e['fleet_configuration']['configuration_name'] + ')':''}<br>
                          Rim Width: ${formatter.format(e['fleet_rim_width'])}inch <br>
                          Spare Tyre: ${e['fleet_spare_tyre']} unit<br>
                          ${e['created_at']}<br>
                        </td>
                        <td class="align-center align-top">
                          ${e['fleetbrand']?e['fleetbrand']['brand_name']:''} (${e['fleetbrandmodel']?e['fleetbrandmodel']['model_name']:''})<br>
                          Segment: ${e['fleetsegment']?e['fleetsegment']['segment_name']:''}<br>
                          Category: ${e['fleetcategory']?e['fleetcategory']['category_name']:''}<br>
                          BDM: ${formatter.format(e['fleet_bdm'])}Kg<br>
                          BTM: ${formatter.format(e['fleet_btm'])}Kg<br>
                        </td>
                        <td class="text-center align-top">${e['fleet_status']==1?'Active':'Inactive'}</td>
                        <td class="text-center align-top" style="white-space: nowrap;">
                          <a href="/fleet-detail/${e['fleet_id']}"><button class="btn btn-primary" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'>Manage</button></a>
                          <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                          <button class="btn btn-sm btn-icon btnDelete" data-id="${e['fleet_id']}"><i class="ti ti-trash"></i></button>
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
          // columns: [
          //   { width: '40px' },  // Set width for the first column
          //   { width: '' },  // Set width for the second column
          //   { width: '120px' },  // Set width for the third column
          //   { width: '80px' },  // Set width for the fourth column
          // ],
          initComplete: function (settings, json) {
            if ($('#fleet_brand_list input.form-control[type="search"]').parent().is('label')) {
              $('#fleet_brand_list input.form-control[type="search"]').unwrap();
            }

            //$('#fleet_brand_list .dataTables_filter .form-control').removeClass('form-control-sm');
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
    url: '/fleet/delete',
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

function generateModelOption(modal, brand_id, selected_model_id = 0){
  // $('.sel-brand-model').html('');
  console.log(selected_model_id);
  console.log(modal);
  $.ajax({
    url: '/fleet-brand-model/list',
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
      }
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });
}
