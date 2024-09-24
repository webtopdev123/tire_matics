let modelListFormValid = null;
let dataTable_modal_obj = null;

jQuery(function () {

  $('#main-table tbody').on('click', '.btnModels', function () {
    var data = $(this).attr('data-object');
    data = JSON.parse(data);
    console.log(data);
    for (const key in data) {
      if (data.hasOwnProperty(key)) {
        $(`input[name='${key}'], select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
      }
    }
    getModelList();
    $('#modellistModal').modal('show');
  });

  modelListFormValid = FormValidation.formValidation($('#modellistModal form')[0], {
    fields: {
      merchant_name: {
        validators: {
          notEmpty: {
            message: "This field is required"
          }
        }
      },
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  $('#modellistModal form #btn-create').on('click', function () {
    modelListFormValid.validate().then(function (status) {
      if (status === 'Valid') {
        $.ajax({
          url: '/fleet-axle/create',
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          data: $('#modellistModal form').serialize(),
          success: function (response, status, xhr) {
            if (xhr.status === 200) {
              console.log(response);
              $('#modellistModal [name="model_name"]').val('');

              getModelList();
            }
          },
          error: function (xhr, status, error) {
            console.log(xhr);
          }
        });
      }
    });
  });

  $('#model-table tbody').on('click', '.btnDelete', function () {
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
        deleteTyreBrandModel($(this).attr('data-id'));
      }
    })

  });

});

function getModelList() {
  $.ajax({
    url: '/fleet-axle/list',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: {
      'fleet_id': $(`input[name='fleet_id']`).val(),
    },
    success: function (response, status, xhr) {
      if (xhr.status === 200) {
        console.log(response);

        var tableBody = "";

        response.data.forEach((e, i) => {
          tableBody += `
                        <tr>
                            <td class="align-middle">${i + 1}.</td>
                            <td class="align-middle">Axle ${i + 1}</td>
                            <td class="align-middle text-end">${e['axle_tyre_places']}</td>
                            <td class="align-middle text-end">${e['total_tyre']}</td>
                            <td class="d-flex justify-content-center align-items-center" style="white-space: nowrap;">
                              <button class="btn btn-primary btnModels" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'>Tyre</button>
                            </td>
                        </tr>`;
        });

        $('#model-table').DataTable().clear().destroy();
        $('#model-table tbody').html(tableBody);
        $('#model-table').DataTable({
          pageLength: 100,
          dom: 'rti',
          // ordering: false,
          autoWidth: false,
          columns: [
            { width: '40px' },
            {},
            {},
            {},
            { width: '80px', orderable: false },
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

function deleteTyreBrandModel(id) {
  $.ajax({
    url: '/fleet-axle/delete',
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

        getModelList();
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
