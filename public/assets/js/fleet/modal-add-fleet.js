let formValcreatestockForm = null;

$( document ).ready(function() {

    $('#btn-create').on('click', function () {
        $('#createcategoryModal form')[0].reset();
        $('#createcategoryModal').modal('show');
    });

    formValcreatestockForm = FormValidation.formValidation($('#createcategoryModal form')[0], {
        fields: {
            fleet_plate: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
            fleet_code: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_axle_1: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_axle_2: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_capacity: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_size: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_engine: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_chassis: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_bdm: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_btm: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_travel_distance: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_travel_time: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_overload_estimate: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_goods_type: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_rim_model: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_spare_type: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_road_condition: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        prime_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_brand_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_brand_model_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        fleet_type_id: {
                validators: {
                    notEmpty: {
                        message: "This field is required"
                    }
                }
            },
	        category_id: {
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

    $('#createcategoryModal form #btn-create').on('click', function () {
        formValcreatestockForm.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    url: '/fleet/create',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#createcategoryModal form').serialize(),
                    success: function (response, status, xhr) {
                        if (xhr.status === 200) {
                            console.log(response);
                            getTableData();
                            $('#createcategoryModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
        });
    });

    // After choose Brand, ajax to serve to get Model data, also clean size dropdown
    $('#createcategoryModal').on('change', '.sel-brand', function(){
      brand_id = $(this).val();
      if(brand_id != ''){
        // load data for model dropdown
        generateModelOption('#createcategoryModal', brand_id);
      }
    });

    $('#createcategoryModal input[name="fleet_plate"]').on('input', function() {
      $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
    });

});
