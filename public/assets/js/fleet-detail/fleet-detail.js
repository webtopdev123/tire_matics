let dt_user = null;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var current_fleet_axle_id = 0;
var current_fleet_axle_position = 0;
let formatter = new Intl.NumberFormat('en-US', {
  style: 'decimal',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

jQuery(function () {
  $('.tyre-container').on("click", function () {
    current_fleet_axle_id = $(this).attr('data-axle-id');
    current_fleet_axle_position = $(this).attr('data-tyre-position');
    var tyre_id = $(this).attr('data-tyre-id');

    if ($(this).hasClass('installed')) {
      loadTyreData(tyre_id);
    } else {
      if ($(this).hasClass('active')) {
        // open popup to select Tyre for installing
        getTableData();
        $('#axleListModal').modal('show');
      } else {
        // $('.tyre-container').removeClass('active');
        // $(this).addClass('active');

        if (tyre_id == 0) {
          // Haven't yet installed Tyre
          getTableData();
          $('#axleListModal').modal('show');
          loadTyreData(tyre_id);
        } else {
          loadTyreData(tyre_id);
        }
      }
    }

    $('.tyre-container').removeClass('active');
    $(this).addClass('active');

  });

  $('.spare-container .tyre-container').on("click", function () {
    current_fleet_axle_id = $(this).attr('data-axle-id');
    current_fleet_axle_position = $(this).attr('data-tyre-position');

    if (!$(this).hasClass('installed')) {
      getTableData();
      $('#axleListModal').modal('show');
    }

    $('.tyre-container').removeClass('active');
    $(this).addClass('active');

  });

  $('#axleListModal').on('change', '#filter-status', function () {
    getTableData();
  });

  $('#axleListModal').on('change', '#filter-type', function () {
    getTableData();
  });

  $('.tyre_info .btn-change').on('click', function () {
    let tyreId = $('.tyre_info .tyre_id').val();
    if (tyreId.length < 1) {
      return;
    }
    getTableData();
    $('#axleListModal').modal('show');
  });

  $('.moreInfo').addClass('d-none');  // Initially hide
  $('.btnShowMore').on('click', function () {
    $('.moreInfo').toggleClass('d-none');  // Toggle visibility

    // Toggle button text
    var isHidden = $('.moreInfo').hasClass('d-none');
    $(this).text(isHidden ? 'Show More' : 'Show Less');
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
      'filter_installed': 1,
      'filter_type': $('#filter-type').val(),
      'filter_status': $('#filter-status').val(),
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {

        console.log(response);

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
                  Serial No: ${e['tyre_serial']}<br>
                  ODT: ${formatter.format(e['tyre_odt'])}mm<br>
                  Tyre Presure : ${formatter.format(e['tyre_psi'])}psi<br>
                  Price: RM${formatter.format(e['tyre_price'])}<br>
                  Year: ${e['tyre_year']}<br>
                </td>
                <td class="align-center align-top">
                  ${e['tyre_brand'] ? e['tyre_brand']['brand_name'] : ''} (${e['tyre_brand_model'] ? e['tyre_brand_model']['model_name'] : ''})<br>
                  ${e['tyre_brand_model_size'] ? e['tyre_brand_model_size']['size_name'] : ''}
                </td>
                <td class="text-center align-top">${e['tyre_type']}</td>
                <td class="text-center align-top" style="white-space: nowrap;">
                  <button class="btn btn-primary mb-2 create-btn waves-effect waves-light btnInstall" data-tyre-id="${e['tyre_id']}">Assign</button>
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
          columns: [
            { width: '30px' },
            { width: 'auto' },
            { width: 'auto' },
            { width: 'auto' },
            { width: '40px', orderable: false },
          ],
          // add New role button
          buttons: [

          ],
          initComplete: function (settings, json) {
            $('.dt-buttons.btn-group').html(`
            <div class="mb-1 px-1 pull-right">
              <select id="filter-type" class="form-control form-select-sm" name="filter_type">
                  <option value="">- All Type -</option>
                  <option value="NEW">New Tyre</option>
                  <option value="COC">Customer Own Casing</option>
                  <option value="RETREAD">Stock Retread</option>
                  <option value="USED">Used Tyre</option>
              </select>
            </div>

            `);

            $('.dt-buttons.btn-group').removeClass('flex-wrap');

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

function loadTyreData(tyre_id = 0, open_inspection = true) {
  if (tyre_id == 0) {
    $('.tyre_info .tyre_id').val('');
    $('.tyre_info .tyre_serial').text('');
    $('.tyre_info .tyre_brand').text('');
    $('.tyre_info .tyre_model').text('');
    $('.tyre_info .tyre_size').text('');
    $('.tyre_info .tyre_type').text('');
    $('.tyre_info .tyre_psi').text('');
    $('.tyre_info .tyre_installed_date').text('');
  } else {
    $.ajax({
      url: '/fleet-tyre/get',
      type: 'GET',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      data: {
        'tyre_id': tyre_id,
      },
      success: function (response, status, xhr) {

        console.log(response);
        $('.tyre_info .tyre_id').val(response.data['tyre_id']);
        $('.tyre_info .axle_id').val(current_fleet_axle_id);
        $('.tyre_info .axle_position').val(current_fleet_axle_position);
        $('.tyre_info .tyre_serial').text(response.data['tyre_serial']);
        $('.tyre_info .tyre_brand').text(response.data['tyre_brand'] ? response.data['tyre_brand']['brand_name'] : '');
        $('.tyre_info .tyre_model').text(response.data['tyre_brand_model'] ? response.data['tyre_brand_model']['model_name'] : '');
        $('.tyre_info .tyre_size').text(response.data['tyre_brand_model_size'] ? response.data['tyre_brand_model_size']['size_name'] : '');
        $('.tyre_info .tyre_type').text(response.data['type_name']);
        $('.tyre_info .tyre_psi').text(`${response.data['tyre_psi']} PSI`);
        $('.tyre_info .tyre_installed_date').text(`${response.data['installed_date']}`);

        let projection = "-";
        if (!isNaN(response.data['tyre_projection']) && response.data['tyre_projection'] != 0) {
          projection = formatter.format(response.data['tyre_projection']) + "KM";
        }
        $('.tyre_info .tyre_projection').text(response.data['tyre_projection']);

        $('.tyre_info .tyre_cost_km').text(response.data['tyre_cost_km']);
        $('.tyre_info .tyre_price').text(`RM${formatter.format(response.data['tyre_price'])}`);
      },
      error: function (xhr, status, error) {
        console.log(xhr);
      }
    });
  }

  getChartData(tyre_id);
}
