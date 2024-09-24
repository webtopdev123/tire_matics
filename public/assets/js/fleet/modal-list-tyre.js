let sizeListFormValid = null;
let dataTable_size_obj=null;

jQuery(function () {

    $('#model-table tbody').on('click', '.btnModels', function () {
        var data = $(this).attr('data-object');
        data = JSON.parse(data);
        console.log(data);
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                $(`input[name='${key}'], select[name='${key}'], textarea[name='${key}']`).val(data[key]).trigger('change');
            }
        }
        getSizeList();
        getTyreDropdown();
        $('#sizelistModal').modal('show');
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
                    url: '/fleet-tyre/set',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#sizelistModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            $('#sizelistModal input').not('[name="axle_id"]').val('');
                            getSizeList();
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

    $('#size-table tbody').on('click', '.btnDelete', function () {
        Swal.fire({
            title: '',
            text: "Are you sure you want to delete this record?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#f1f1f2',
            confirmButtonText: 'Unset',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-label-danger',
                cancelButton: 'btn btn-label-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                unsetTyre($(this).attr('data-id'));
            }
        })

    });

});

function getSizeList() {
    $.ajax({
        url: '/fleet-tyre/list',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            'axle_id': $(`input[name='axle_id']`).val(),
        },
        success: function (response, status, xhr) {
            if (xhr.status === 200) {

                console.log(response);

                var tableBody = "";

                response.data.forEach((e, i) => {
                    tableBody += `
                        <tr>
                            <td class="align-middle">${i + 1}.</td>
                            <td class="align-middle">${e['tyre_serial']}</td>
                            <td class="align-middle text-end">${e['tyre_type']}</td>
                            <td class="d-flex justify-content-center align-items-center" style="white-space: nowrap;">
                              <button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(e).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                              <button class="btn btn-sm btn-icon btnDelete" data-id="${e['tyre_id']}"><i class="ti ti-trash"></i></button>
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
                        { width: '40px' },
                        {},
                        { width: '40px' },
                        { width: '80px' },
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

function unsetTyre(id) {
    $.ajax({
        url: '/fleet-tyre/unset',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        data: {
            id: id
        },
        success: function (response, status, xhr) {

            if (xhr.status === 200) {

                $('.alert').html('Unset Succussfully');
                $('.alert').removeClass('d-none');

                setTimeout(function () {
                    $('.alert').addClass('d-none');
                }, 3000);

                getSizeList();
                getTyreDropdown();
                getModelList();
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

function getTyreDropdown() {
    $.ajax({
        url: '/fleet-tyre/list-dropdown',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },

        success: function (response, status, xhr) {
            if (xhr.status === 200) {

                console.log(response);

                var newOptionsHtml = `<option value="">- SELECT -</option>`;

                response.data.forEach((e, i) => {
                    newOptionsHtml += `<option value="${e['tyre_id']}">${e['tyre_serial']}</option>`;
                });

                $('#sizelistModal select[name="tyre_id"]').html(newOptionsHtml);
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
