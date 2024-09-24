let sizeListFormValid = null;
let dataTable_size_obj=null;

jQuery(function () {

    $('#model-table tbody').on('click', '.btnSizes', function () {
        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        current_size_id = data['model_id'];
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                $(`input[name='${key}'], select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
            }
        }
        getSizeList();
        $('#sizelistModal').modal('show');
        $('#modellistModal').modal('hide');
    });

    sizeListFormValid = FormValidation.formValidation($('#sizelistModal form')[0], {
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

    $('#sizelistModal form #btn-create').on('click', function () {
        sizeListFormValid.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/tyre-brand-model-size/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#sizelistModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            $('#sizelistModal input').not('[name="model_id"]').val('');
                            // $('#sizelistModal form')[0].reset();
                            getSizeList();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    $('#size-table tbody').on('click', '.btnDelete', function () {
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
                deleteModel($(this).attr('data-id'));
            }
        })

    });

});

function getSizeList() {
    $.ajax({
        url: '/tyre-brand-model-size/list',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            'model_id': $(`input[name='model_id']`).val(),
        },
        success: function (response, status, xhr) {
            if (xhr.status === 200) {

                console.log(response);

                var tableBody = "";

                response.data.forEach((e, i) => {
                    tableBody += `
                        <tr>
                            <td class="text-center align-top">${i + 1}.</td>

                            <td class="text-left align-top">
                              ${e['size_name']}<br>
                            </td>
                            <td class="text-left align-top">
                              Original Tread Depth: ${e['size_tread_depth']}mm<br>
                              Tyre Presure: ${e['size_psi']}psi
                            </td>
                            <td class="align-top" style="white-space: nowrap;">
                              <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                              <button class="btn btn-sm btn-icon btnDelete" data-id="${e['size_id']}"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>`;
                });

                $('#size-table').DataTable().clear().destroy();
                $('#size-table tbody').html(tableBody);
                $('#size-table').DataTable({
                    pageLength: 100,
                    dom: 'rti',
                    // ordering: false,
                    autoWidth: false,
                    columns: [
                      { width: '30px' },
                      {width: '150px'},
                      {width: 'auto'},
                      { width: '40px', orderable: false },
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

function deleteModel(id) {
    $.ajax({
        url: '/tyre-brand-model-size/delete',
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

                getSizeList();
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
