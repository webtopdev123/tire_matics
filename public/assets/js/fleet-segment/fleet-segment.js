let dt_user = null;
var csrfToken = $('meta[name="csrf-token"]').attr('content');

jQuery(function () {


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

    // Level List datatable initialize
    var dataTableLevels = $('.datatables-main'),
    dt_level;
  if (dataTableLevels.length) {
    console.log('fdsf');
    dt_level = dataTableLevels.DataTable({
      serverSide: true,
      pageLength: 100,
      ajax: {
        url: baseUrl + 'fleet-segment/list',
        dataSrc: function (data) {
          console.log(data);

          var processedData = buildCategoryTreeList(data.data);

          console.log(processedData);

          categoryTreeOptions(processedData);

          return processedData;
        },
        "error": function (xhr, error, thrown) {
          console.log(xhr);
        }
      },
      columns: [
        {
          data: 'segment_id'
        },
        {
          data: 'segment_name'
        }, {
          data: ''
        }
      ],
      columnDefs: [
        {
          // NO#
          targets: 0,
          orderable: false,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">' + (meta.row + 1) + '.</span>';
          }
        },
        {
          // category Name
          targets: 1,
          orderable: true,
          render: function (data, type, full, meta) {
            var $name = full['segment_name'];
            var levelString = '';

            for (let i = 0; i < parseInt(full['level']); i++) {
              levelString += '|--&nbsp;';
            }

            return `<span class="text-nowrap">${levelString}${$name}</span>`;
          }
        },
        {
          // Actions
          className: 'text-center',
          targets: 2,
          searchable: false,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            var categoryId = full['segment_id'];


            var button = `<button class="btn btn-sm btn-icon btnUpdate" data-object='${JSON.stringify(full).replace(/'/g, "&#39;")}'><i class="ti ti-edit"></i></button>
                          <button class="btn btn-sm btn-icon btnDelete" data-id="${full['segment_id']}"><i class="ti ti-trash"></i></button>`;

            return '<span class="text-nowrap">' + button + '</span>';
          }
        }
      ],
      order: [[0 ,'asc']],
      dom:
        '<"row mx-1"' +
        '<"col-sm-12 col-md-3 col-xl-2" f>' +
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
      // Add New Level Button
      buttons: [],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['segment_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    var button = $(
      '<div class="col text-end" style="text-align:end;"><button class="btn btn-primary mb-2 col-12 create-btn" data-bs-target="#addProductCategoryModal" data-bs-toggle="modal" type="submit" style="width:auto;">Create</button></div>'
    );


    $('#DataTables_Table_0 th:contains("Actions")').css('text-align', 'center');

    if ($('input.form-control[type="search"]').parent().is('label')) {
      $('input.form-control[type="search"]').unwrap();
    }
  }

});

// function getTableData() {


// }

function deleteItem(id) {

  $.ajax({
    url: '/fleet-segment/delete',
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

        var dt_level = $('.datatables-main').DataTable();
                            dt_level.ajax.reload(null, false);
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

function buildCategoryTreeList(categories, parentId = null, level = 0) {
  let result = [];

  for (let category of categories) {
    if (category.parent_id == parentId || (parentId == null && category.parent_id == 0)) {
      category.level = level;
      result.push(category);
      result = result.concat(buildCategoryTreeList(categories, category.segment_id, level + 1));
    }
  }

  return result;
}

function categoryTreeOptions(categoryTree) {
  let optionsHtml = '<option value="0" >--SELECT--</option>';

  categoryTree.forEach((category, key) => {
    if(category.parent_id == 0){
      optionsHtml += `<option value="${category.segment_id}" class="level_${category.level}">${''.repeat(category.level)}${category.segment_name}</option>`;
    }

  });

  // for (let category in categoryTree) {
  //   console.log(category);
  //   optionsHtml += `<option value="${category.value}" class="level_${category.level}">${'  '.repeat(category.level)}${category.name}</option>`;
  //     // if (category.children) {
  //     //     for (let child of category.children) {
  //     //         buildOptions(child);
  //     //     }
  //     // }
  // }


  $('#add_product_parent_category').empty();
  // var newOption = $('<option></option>').val("0").text("--SELECT--");
  // $('#add_product_parent_category').append(newOption);
  $('#add_product_parent_category').append(optionsHtml);

  $('#edit_product_parent_category').empty();
  // $('#edit_product_parent_category').append(newOption);
  $('#edit_product_parent_category').append(optionsHtml);

}
