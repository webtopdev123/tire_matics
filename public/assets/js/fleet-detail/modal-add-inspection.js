let validateCreateBrandForm = null;

jQuery(function () {

    $('.tyre_info .btn-update').on('click', function () {
        //$('#addInspectionModal form')[0].reset();

        let tyreId = $('.tyre_info .tyre_id').val();
        if (tyreId.length < 1) {
            return;
        }

        $("#addInspectionModal input[name='tyre_id']").val(tyreId);
        $("#addInspectionModal input[name='fleet_id']").val($('.tyre_info .fleet_id').val());
        $("#addInspectionModal input[name='axle_id']").val(current_fleet_axle_id);
        $("#addInspectionModal input[name='axle_position']").val(current_fleet_axle_position);

        getInspectionList();

        $('#addInspectionModal').modal('show');
    });

    validateCreateBrandForm = FormValidation.formValidation($('#addInspectionModal form')[0], {
        fields: {
            brand_name: {
                validators: {
                    notEmpty: {
                        message: "please enter brand name"
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

    $('#addInspectionModal button[type="submit"]').on('click', function () {
        validateCreateBrandForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-tyre-inspection/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#addInspectionModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            toastr.success('Inspection added successfully.', '', { timeOut: 3000 });
                            $('#addInspectionModal input[name=inspection_rtd]').val('');
                            $('#addInspectionModal input[name=inspection_psi]').val('');
                            // $('#addInspectionModal').modal('hide');

                            let tyre_id = $("#addInspectionModal input[name='tyre_id']").val();
                            console.log("tyre_id");
                            console.log(tyre_id);

                            loadTyreData(tyre_id);
                            getInspectionList();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    $('#addInspectionModal table').on('click', '.btnDelete', function () {
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

function getInspectionList() {
    $.ajax({
        url: '/fleet-tyre-inspection/list',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            'tyre_id': $("#addInspectionModal input[name='tyre_id']").val(),
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
                            <td class="align-middle text-left">${e['tyre']['tyre_serial']}</td>
                            <td class="align-middle text-center">${formatter.format(e['inspection_mileage'])}</td>
                            <td class="align-middle text-center">${e['inspection_rtd']}</td>
                            <td class="align-middle text-center">${e['inspection_psi']}</td>
                            <td class="align-middle white-space-nowrap text-center" style="white-space: nowrap;">${e['created_at']}</td>
                            <td class="text-center align-top" style="white-space: nowrap;">
                                <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                                <button class="btn btn-sm btn-icon btnDelete" data-id="${e['inspection_id']}"><i class="ti ti-trash"></i></button>
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

function deleteItem(id) {

    $.ajax({
      url: '/fleet-tyre-inspection/delete',
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

          getInspectionList();
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
