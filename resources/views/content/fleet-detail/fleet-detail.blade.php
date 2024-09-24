@extends('layouts/layoutMaster')

@section('title', 'Fleet Detail')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}"></script>
<script
    src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}"></script>
<script
    src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/echarts@latest/dist/echarts.min.js"></script>
<script src="{{ asset('assets/js/fleet-detail/fleet-detail.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-detail/modal-add-inspection.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-detail/modal-unmount.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-detail/modal-confirm-assign.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-detail/chart.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/fleet-detail/modal-edit-inspection.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

<style>
    .tyre-container {
        background-color: #fff;
        border: 1px double #666666;
        border-radius: 6px;
        cursor: pointer;
        min-width: 80px;
    }

    .tyre-container.installed {
        background-color: #7367f0;
        color: #fff;
    }

    .tyre-container.active {
        background-color: #30336b;
        color: #fff;
    }

    .tyre-container:hover {
        animation: 0.3s click infinite;
    }

    .btnShowMore{
      color: #000;
      background-color: #f5f5f5;
    }

    @keyframes click {
        0% {
            transform: scale(1);
        }

        25% {
            transform: scale(1.01);
        }

        50% {
            transform: scale(1.02);
        }

        75% {
            transform: scale(1.01);
        }

        100% {
            transform: scale(1);
        }
    }

    .tyre-container div {
        color: #000;
    }

    .tyre-container.installed div,
    .tyre-container.active div {
        color: #fff;
    }

    .axle-type p {
        background-color: #000;
        padding: 4px 6px;
        color: #fff;
        display: inline;
    }

    .axle div {
        font-size: 10px;
    }

    .axle-1 .axle {
        margin-top: 80px;
    }

    .tyre-container .update-date {
        font-size: 10px;

    }


    .btn-update:hover {
        background-color: #f6f6f6;
    }

    .btn-unmount:hover {
        background-color: #f6f6f6;
    }
</style>

<div class="d-flex justify-content-between align-items-center title-head">

    <h5 class="mb-2"><a href="{{ url('/fleet') }}"><i class="menu-icon tf-icons ti ti-arrow-back"></i></a>Fleet Detail
    </h5>
</div>

<div class="row">
    <div class="col-3">
        <div class="card p-3">
            <div style="font-size: 16px;">
                <b><u>Fleet</u></b>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Plate No.</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_plate }}</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Rim Width</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_rim_width }}mm</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Spare Tyre</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_spare_tyre }} Unit</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>BDM</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_bdm }}Kg</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>BTM</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_btm }}Kg</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Brand</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleetbrand->brand_name }}</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Model</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleetbrandmodel->model_name }}</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Segment</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleetsegment->segment_name }}</div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Category</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleetcategory->category_name }}</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Configuration</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->FleetConfiguration->configuration_type }} ({{
                    $fleet->FleetConfiguration->configuration_name }})
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2 moreInfo">
                <div>Status</div>
                <div class="text-end" style="font-weight: 500;">{{ $fleet->fleet_status == 1?'Active':'Inactive' }}
                </div>
            </div>
            <button class="btn btn-sm btnShowMore mt-2">Show More</button>
        </div>

        <div class="card p-3 mt-2 tyre_info">
            <div style="font-size: 16px;">
                <b><u>Tyre Information</u></b>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>S/N</div>
                <div class="text-end tyre_serial" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Brand</div>
                <div class="text-end tyre_brand" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Model</div>
                <div class="text-end tyre_model" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Size</div>
                <div class="text-end tyre_size" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Type</div>
                <div class="text-end tyre_type" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Tyre Presure</div>
                <div class="text-end tyre_psi" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Installed Date</div>
                <div class="text-end tyre_installed_date" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Projection</div>
                <div class="text-end tyre_projection" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>CPK</div>
                <div class="text-end tyre_cost_km" style="font-weight: 500;">-</div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div>Cost</div>
                <div class="text-end tyre_price" style="font-weight: 500;">-</div>
            </div>

            <input class="tyre_id" name="tyre_id" type="text" hidden>
            <input id="fleet_id" class="fleet_id" name="fleet_id" value="<?php echo $fleet_id; ?>" type="text" hidden>
            <input class="axle_id" name="axle_id" type="text" hidden>
            <input class="axle_position" name="axle_position" type="text" hidden>
            <hr class="m-1">
            <div class="d-flex flex-row flex-wrap p-1" style="user-select: none;">
                <div class="d-flex flex-column flex-fill align-items-center text-center cursor-pointer btn-update p-1">
                    <img src="https://app.primofleet.com/images/tire/update-tire.png" alt="" srcset=""
                        style="width: 32px; height: 32px;">
                    <div style="font-size: 12px;">Inspection</div>
                </div>
                <div class="vr m-2 mt-0 mb-0" style="color: #dbdade;"></div>
                <div class="d-flex flex-column flex-fill align-items-center text-center cursor-pointer btn-unmount p-1">
                    <img src="https://app.primofleet.com/images/tire/remove-tire.png" alt="" srcset=""
                        style="width: 32px; height: 32px;">
                    <div style="font-size: 12px;">Unmount Tyre</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 text-center fleet_body">
        <?php $index = 1; ?>
        <?php foreach($fleet_axles as $fleet_axle ) {?>
        <?php if($fleet_axle['axle_tyre_number'] == 2) {?>
        <div class="d-flex justify-content-center axle-<?php echo $index; ?>" style="width: 100%; margin-bottom: 6px;">
            <div class="tyre-container <?php echo $fleet_axle->axle_position_l1 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_l1" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_l1 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_l1 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_l1 > 0){ ?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_l1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_l1_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
            <div class="axle">
                <div class="axle-type">
                    <p class="m-0">
                        <?php echo $fleet_axle->axle_type ?>
                    </p>
                </div>
                <img style="max-width: 270px;"
                    src="/assets/img/fleet-parts/axle-<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
            </div>
            <div class="tyre-container <?php echo $fleet_axle->axle_position_r1 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_r1" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_r1 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_r1 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_r1 > 0){ ?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_r1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_r1_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
        </div>
        <?php } ?>
        <?php if($fleet_axle['axle_tyre_number'] == 4) {?>
        <div class="d-flex justify-content-center" style="width: 100%; margin-bottom: 6px;">
            <div class="tyre-container <?php echo $fleet_axle->axle_position_l2 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_l2" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_l2 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_l2 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_l2 > 0) {?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_l2_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_l2_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
            <div class="tyre-container <?php echo $fleet_axle->axle_position_l1 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_l1" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_l1 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_l1 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_l1 > 0){ ?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_l1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_l1_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
            <div class="axle">
                <div class="axle-type">
                    <p class="m-0">
                        <?php echo $fleet_axle->axle_type ?>
                    </p>
                </div>
                <img style="max-width: 270px;"
                    src="/assets/img/fleet-parts/axle-<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
            </div>
            <div class="tyre-container <?php echo $fleet_axle->axle_position_r1 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_r1" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_r1 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_r1 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_r1) {?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_r1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_r1_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
            <div class="tyre-container <?php echo $fleet_axle->axle_position_r2 > 0 ?'installed':'' ?>"
                data-tyre-position="axle_position_r2" data-axle-id="<?php echo $fleet_axle->axle_id ?>"
                data-tyre-id="<?php echo $fleet_axle->axle_position_r2 ?>">
                <div>T
                    <?php echo $index; $index ++; ?>
                </div>
                <div class="percen">
                    <?php echo $fleet_axle->axle_position_r2 > 0 ? '100%':'-' ?>
                </div>
                <img style="min-width: 80px;"
                    src="/assets/img/fleet-parts/tyre<?php echo ($fleet_axle->axle_row == 1)?1:($fleet_axle->axle_row<$axle_row?2:3)  ?>.svg"
                    alt="">
                <p class="update-date m-0">
                    <?php if($fleet_axle->axle_position_r2 > 0) {?>
                    Last Update <br>
                    <?php echo ($fleet_axle->axle_position_r2_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_axle->axle_position_r2_info->updated_at)->format('d/m/Y'):'-';?>
                    <?php } ?>
                </p>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>

    <div class="col-3">
        <div class="card p-3">
            <div class="mb-1">
              <div style="font-weight: bold; font-size: 16px;">Tyre CPK Chart</div>
              <div id="cpk-tyres-chart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="mb-1">
                <div style="font-weight: bold; font-size: 16px;">Projection</div>
                <div id="projection-chart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="">
                <div style="font-weight: bold; font-size: 16px;">CPK (cost per km)</div>
                <div id="cpk-chart" style="width: 100%; height: 300px;"></div>
            </div>

        </div>

        <div class="card p-3 mt-2 text-center justify-content-center spare-container">
            <div style="font-size: 16px;">
                <b><u>Spare Tyre</u></b>
            </div>
            <div class="d-flex <?php echo ($fleet->fleet_spare_tyre == 2)?"
                justify-content-between":"justify-content-center" ?> p-1">
                <?php if($fleet_spare) {?>
                <?php if($fleet->fleet_spare_tyre >= 1) {?>
                <div class="col-6">
                    <div class="tyre-container <?php echo $fleet_spare->axle_position_l1 > 0 ?'installed':'' ?>"
                        data-tyre-position="axle_position_l1" data-axle-id="<?php echo $fleet_spare->axle_id ?>"
                        data-tyre-id="<?php echo $fleet_spare->axle_position_l1 ?>">
                        <div>ST1</div>
                        <div>
                            <?php echo $fleet_spare->axle_position_l1 > 0 ? '100%':'-' ?>
                        </div>
                        <img style="min-width: 80px;" src="/assets/img/fleet-parts/spare.svg" alt="">
                        <p class="update-date m-0">
                            <?php if ($fleet_spare->axle_position_l1 > 0){ ?>
                            Last Update <br>
                            <?php echo ($fleet_spare->axle_position_l1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_spare->axle_position_l1_info->updated_at)->format('d/m/Y'):'-';?>
                            <?php } ?>
                        </p>
                    </div>
                    <?php if($fleet_spare->axle_position_l1 > 0) {?>
                    <div class="text-center cursor-pointer btn-unmount mt-1"
                        data-tyre-id="{{ $fleet_spare->axle_position_l1 }}">
                        <img src="https://app.primofleet.com/images/tire/remove-tire.png" alt="" srcset=""
                            style="width: 32px; height: 32px;">
                        <div style="font-size: 12px;">Unmount Tyre</div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if($fleet->fleet_spare_tyre == 2) {?>
                <div class="col-6">
                    <div class="tyre-container <?php echo $fleet_spare->axle_position_r1 > 0 ?'installed':'' ?>"
                        data-tyre-position="axle_position_r1" data-axle-id="<?php echo $fleet_spare->axle_id ?>"
                        data-tyre-id="<?php echo $fleet_spare->axle_position_r1 ?>">
                        <div>ST2</div>
                        <div>
                            <?php echo $fleet_spare->axle_position_r1 > 0 ? '100%':'-' ?>
                        </div>
                        <img style="min-width: 80px;" src="/assets/img/fleet-parts/spare.svg" alt="">
                        <p class="update-date m-0">
                            <?php if($fleet_spare->axle_position_r1 > 0){ ?>
                            Last Update <br>
                            <?php echo ($fleet_spare->axle_position_r1_info)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$fleet_spare->axle_position_r1_info->updated_at)->format('d/m/Y'):'-';?>
                            <?php } ?>
                        </p>
                    </div>
                    <?php if($fleet_spare->axle_position_r1 > 0) {?>
                    <div class="text-center cursor-pointer mt-1 btn-unmount"
                        data-tyre-id="{{ $fleet_spare->axle_position_r1 }}">
                        <img src="https://app.primofleet.com/images/tire/remove-tire.png" alt="" srcset=""
                            style="width: 32px; height: 32px;">
                        <div style="font-size: 12px;">Unmount Tyre</div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


@include('_partials/_modals/fleet-detail/modal-list-tyre')
@include('_partials/_modals/fleet-detail/modal-add-inspection')
@include('_partials/_modals/fleet-detail/modal-unmount')
@include('_partials/_modals/fleet-detail/modal-confirm-assign')

@include('_partials/_modals/fleet-detail/modal-edit-inspection')

@endsection
