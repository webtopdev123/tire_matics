@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

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
<script src="{{ asset('assets/js/dashboard/dashboard-charts.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center title-head">
    <h5 class="mb-2">Dashboard</h5>
    <div class="mb-1 px-1 pull-right" style="width:150px;">
      <select id="filterYear" class="select2 form-control filter_year">
        <?php for($i = 2024; $i <= date("Y"); $i++) { ?>
        <option value="<?php echo $i; ?>" <?php echo ($i == date('Y') ? 'selected' : '') ?>><?php echo $i; ?></option>
        <?php } ?>
      </select>
    </div>
</div>
    <div class="row mb-2">
      <div class="col-6">
        <div class="card p-3">
            <div style="font-weight: bold; font-size: 16px;">Monthly Purchased Tyres (Unit)</div>
            <div id="unit-tyres-chart" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
      <div class="col-6">
        <div class="card p-3">
            <div style="font-weight: bold; font-size: 16px;">Monthly Purchased Tyres (Cost)</div>
            <div id="cost-tyres-chart" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col-6">
        <div class="card p-3">
          <div style="font-weight: bold; font-size: 16px;">Monthly Installed Tyres (Unit)</div>
          <div id="unit-installed-tyres-chart" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
      <div class="col-6">
        <div class="card p-3">
            <div style="font-weight: bold; font-size: 16px;">Monthly Installed Tyres (Cost)</div>
            <div id="cost-installed-tyres-chart" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col-12">
        <div class="card p-3">
          <div style="font-weight: bold; font-size: 16px;">Tyre CPK Chart</div>
          <div id="cpk-tyres-chart" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card p-3">
            <div style="font-weight: bold; font-size: 16px;">Inventory</div>
            <div id="tyre-per-status-chart" style="width: 100%; height: 370px;"></div>
        </div>
      </div>
    </div>

<style>
  #tyre-per-status-chart {
    padding: 0;
    margin: 0;
  }
</style>



@endsection
