let dt_user = null;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
let dataTable_obj = null;

jQuery(function () {

  loadDatatableList();

  $(document).on('click', '.delete-fleet-brand-record', function () {

    var data_id = $(this).attr('data-brand_id');

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
          deleteFleetBrandData(data_id);
          $('.modal').modal('hide');
        }
    });

  });
});

function deleteFleetBrandData(id) {

  $.ajax({
    url: '/fleet-brand/delete',
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

    },
    error: function (xhr, status, error) {

      if (xhr.responseJSON && xhr.responseJSON.message) {

        $('#toast-container').remove();
          toastr.error(xhr.responseJSON.message, '', { timeOut: 3000 });

      }
    }
  });
}


function loadDatatableList(){

  if ($.fn.DataTable.isDataTable('.datatables-main')) {
    var table = $('.datatables-main').DataTable();
    table.clear().destroy();
  }

  var dataTable = $('.datatables-main');
  // Users List datatable
  if (dataTable.length) {
    dataTable_obj = dataTable.DataTable({
      pageLength: 100,
      serverSide: true,
      paging: true,
      ajax: {
          url: '/fleet-brand/list',
          type: 'GET',
          data: function(d) {

          }
      },
      columns: [
        {
        },
        {
          data: 'brand_name'
        },
        {
          data: 'total_models'
        },
        {
          data: ''
        }
      ],
      columnDefs: [
        {
          targets: 0,

          orderable: false,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">'+(meta.row+1)+'.</span>';
          }
        },
        {
          targets: 1,
          orderable: false,
          render: function (data, type, full, meta) {

            return '<span class="text-wrap">' + full['brand_name'] + '</span>';
          }
        },
        {
          targets: 2,
          className:'text-center',

          orderable: false,
          render: function (data, type, full, meta) {

            return '<span class="text-wrap">' + full['total_models'] + '</span>';
          }
        },
        {
          // Actions
          className: 'text-center',
          targets: -1,

          searchable: false,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {

            var brand_id=full['brand_id'];

            var jsonData=" data-model_data='"+JSON.stringify(full)+"'";

           var modelBtn=`
            <button class="btn btn-primary" id="brand_id-${brand_id}" data-bs-target="#modellistModal" data-bs-toggle="modal" data-bs-dismiss="modal" data-backdrop="static" data-keyboard="false" data-model_data='${JSON.stringify(full).replace(/'/g, "&#39;")}'>Model</button>
           `;

           var editBtn =
              '<button class="btn btn-sm btn-icon me-2" data-bs-target="#updatecategoryModal" data-bs-toggle="modal" data-bs-dismiss="modal"' +
              jsonData +
              '><i class="ti ti-edit"></i></button>';

            var deleteBtn =
              '<button class="btn btn-sm btn-icon delete-fleet-brand-record" data-brand_id="' +
              brand_id +
              '"><i class="ti ti-trash"></i></button>';

            return '<span class="text-nowrap">' + modelBtn + editBtn + deleteBtn + '</span>';
          }
        }
      ],
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

      ]
    });

    if ($('#fleet_brand_list input.form-control[type="search"]').parent().is('label')) {
      $('#fleet_brand_list input.form-control[type="search"]').unwrap();
    }

    var button = $(
      '<button class="btn btn-primary mb-2 create-btn" data-bs-target="#createcategoryModal" data-bs-toggle="modal" type="submit">Create</button>'
    );

    if($('.create-btn').length == 0)
      $('.title-head').append(button);
  }

  setTimeout(() => {
    //$('#fleet_brand_list .dataTables_filter .form-control').removeClass('form-control-sm');
    //$('#fleet_brand_list .dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

}
