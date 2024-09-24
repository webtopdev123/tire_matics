let modelListFormValid = null;
let dataTable_brand_model_obj = null;
var current_brand_id = 0;

jQuery(function () {

    // catch event close edit model to open the model popup
    $('#updatemodelModal').on('hidden.bs.modal', function () {
      if(current_brand_id != 0){
        $('#brand_id-' + current_brand_id).trigger('click');
      }

    })

    document.getElementById('modellistModal').addEventListener('show.bs.modal', function (e) {
      $('#modellistModal input').val('');

      const model_data = JSON.parse(e.relatedTarget.dataset.model_data);

      current_brand_id = model_data.brand_id;

      $('#modellistModal [name="brand_id"]').val(model_data.brand_id);


      loadFleetBrandModelDatatableList(model_data.brand_id);

    });


    modelListFormValid = FormValidation.formValidation($('#modellistModal form')[0], {
        fields: {
          model_name: {
                validators: {
                    notEmpty: {
                        message: "please enter model name"
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

    $('#modellistModal button[type="submit"]').on('click', function () {
        modelListFormValid.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet-brand-model/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#modellistModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {


                            $('#modellistModal [name="model_name"]').val('');

                            if(dataTable_brand_model_obj != null){
                              dataTable_brand_model_obj.ajax.reload(null, false);
                            }

                            if(dataTable_obj != null){
                              dataTable_obj.ajax.reload(null, false);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete-fleet-brand-model-record', function () {

      var data_id = $(this).attr('data-model_id');

      Swal.fire({
          title: '',
          text: 'Are you sure you want to delete this record?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          didOpen: () => {
              $('.swal2-deny').remove();
          }
      }).then(result => {
          if (result.isConfirmed) {
            deleteFleetBrandModelData(data_id);
            // $('.modal').modal('hide');
          }
      });

    });

});


function deleteFleetBrandModelData(id) {
    $.ajax({
        url: '/fleet-brand-model/delete',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        data: {
            id: id
        },
        success: function (response, status, xhr) {

            if (xhr.status === 200) {

              $('#toast-container').remove();
              toastr.success('One record has successfully deleted.', '', { timeOut: 3000 });

              if(dataTable_obj != null)
                  dataTable_obj.ajax.reload(null, false);
              }

              if(dataTable_brand_model_obj != null){
                dataTable_brand_model_obj.ajax.reload(null, false);
              }

        },
        error: function (xhr, status, error) {


            if (xhr.responseJSON && xhr.responseJSON.message) {

              $('#toast-container').remove();
              toastr.error(xhr.responseJSON.message, '', { timeOut: 3000 });
            }
        }
    });
}

function loadFleetBrandModelDatatableList(brand_id){

  if ($.fn.DataTable.isDataTable('.datatables-brand-model')) {
    var table = $('.datatables-brand-model').DataTable();
    table.clear().destroy();
  }

  var dataTable = $('.datatables-brand-model');
  // Users List datatable
  if (dataTable.length) {
    dataTable_brand_model_obj = dataTable.DataTable({
      pageLength: 100,
      serverSide: true,
      paging: true,
      ajax: {
          url: '/fleet-brand-model/list',
          type: 'GET',
          data: function(d) {
            d.brand_id=brand_id
          }
      },
      columns: [
        {
          width: '30px'
        },
        {
          data: 'model_name',
          width: 'auto',
        },
        {
          width: '40px',
          orderable: false,
          data: ''
        }
      ],
      columnDefs: [
        {
          targets: 0,
          width:  '5%',
          orderable: true,
          render: function (data, type, full, meta) {
            return (meta.row+1) + '.';
          }
        },
        {
          targets: 1,
          width:  'auto',
          orderable: true,
          render: function (data, type, full, meta) {

            return full['model_name'];
          }
        },
        {
          // Actions
          className: 'text-center',
          targets: 2,
          width:  '5%',
          searchable: false,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {

            var model_id=full['model_id'];

            var jsonData=" data-model_data='"+JSON.stringify(full)+"'";

           var editBtn =
              '<button class="btn btn-sm btn-icon me-2" id="model-' + model_id + '" data-bs-target="#updatemodelModal" data-bs-toggle="modal" data-bs-dismiss="modal" data-backdrop="static" data-keyboard="false" ' +
              jsonData +
              '><i class="ti ti-edit"></i></button>';

            var deleteBtn =
              '<button class="btn btn-sm btn-icon delete-fleet-brand-model-record" data-model_id="' +
              model_id +
              '"><i class="ti ti-trash"></i></button>';

            return editBtn + deleteBtn;
          }
        }
      ],
      order: [],
      dom:
        '<"row mx-1 justify-content-between align-items-center"' +
        '<"col-sm-12 col-md-4 col-xl-4" f>' +
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

      ]
    });

    if ($('#fleet_brand_model_list input.form-control[type="search"]').parent().is('label')) {
      $('#fleet_brand_model_list input.form-control[type="search"]').unwrap();
    }

  }

  setTimeout(() => {
    $('#fleet_brand_model_list .dataTables_filter .form-control').removeClass('form-control-sm');
    $('#fleet_brand_model_list .dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

}
