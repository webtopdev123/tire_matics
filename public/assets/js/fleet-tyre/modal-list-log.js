
jQuery(function() {

  $('#main-table tbody').on('click', '.btnLog', function () {
    var tyre_id = $(this).attr('data-id');
    getLogList(tyre_id);

    $('#loglistModal').modal('show');
  });


});

function getLogList(tyre_id) {
  $.ajax({
      url: '/fleet-tyre-log/list',
      type: 'GET',
      headers: {
          'X-CSRF-TOKEN': csrfToken
      },
      data: {
          'tyre_id': tyre_id,
      },
      success: function (response, status, xhr) {
          if (xhr.status === 200) {
            let formatter = new Intl.NumberFormat('en-US', {
              style: 'decimal',
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
            });

              console.log(response);

              var tableBody = "";

              response.data.forEach((e, i) => {
                  tableBody += `
                      <tr>
                          <td class="text-center align-top">${e['created_at']}</td>

                          <td class="text-left align-top">
                            Plate No: ${e['fleet']['fleet_plate']}<br>
                            Rim Width: ${formatter.format(e['fleet']['fleet_rim_width'])}mm <br>
                            Spare Tyre: ${e['fleet']['fleet_spare_tyre']} unit<br>
                          </td>
                          <td class="text-center align-top">${e['axle']['axle_type']}</td>
                          <td class="text-center align-top">${e['fleet_axle_position'].toUpperCase()}</td>
                          <td class="text-center align-top">${e['log_status']}</td>
                          <td class="text-left align-top">${e['log_remark']}</td>
                      </tr>`;
              });

              $('#log-table').DataTable().clear().destroy();
              $('#log-table tbody').html(tableBody);
              $('#log-table').DataTable({
                  pageLength: 100,
                  dom: 'rti',
                  // ordering: false,
                  autoWidth: false,
                  columns: [
                    { width: 'auto' },
                    { width: 'auto' },
                    { width: 'auto' },
                    { width: 'auto' },
                    { width: 'auto' },
                    { width: 'auto' }
                  ],
                  language: {
                      info: "_START_ to _END_ Items of _TOTAL_",
                  },
                  initComplete: function () {
                      $('.dt-info').css('font-size', '14px');
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
