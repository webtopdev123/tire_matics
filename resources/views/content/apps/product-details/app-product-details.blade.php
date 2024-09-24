@extends('layouts/layoutMaster')

@section('title', 'Level - Apps')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/product-details/app-product-details.js')}}"></script>
@endsection

@section('content')
<h4 class="mb-4">Menu Details</h4>


<!-- menu Table -->
<div class="card row" id="product-details" style="display:flex;flex-direction:row;padding:5px;padding-bottom:50px;">

  <div class="product-image col-md-4 col-12" style="height:300px;">
    <div style="height:100%;text-align:center;">
      <img src="#" style="width:auto;height:100%;object-fit:cover;max-width:100%;"/>
    </div>

  </div>

  <div class="product-detail col-md-8 col-12 mt-3 mt-md-0">
    <div id="product-name"></div>

    <div id="product-price" class="mt-2"></div>

    <div id="product-description" class="mt-3" style="min-height:80px;background:#f8f7fa;padding:2px;"></div>

    <div id="product-variant" style="font-size:0.8rem;" class="mt-3">

    </div>

    <div id="product-quantity" style="display:flex;gap:10px;" class="mt-3 justify-content-center justify-content-md-start">

      <div>
        <i class="fa fa-minus" style="border-radius:50%;background:var(--bs-primary);color:white;padding:2px;cursor: pointer;" onclick="decreaseQuantity()"></i>
      </div>

      <div class="quantity">
        1
      </div>

      <div>
        <i class="fa fa-plus" style="border-radius:50%;background:var(--bs-primary);color:white;padding:2px;cursor: pointer;" onclick="increaseQuantity()"></i>
      </div>

    </div>

    <div id="product-total-amount" class="mt-3 d-flex justify-content-end justify-content-md-start" style="background: #f8f7fa;padding: 2px;padding-top: 5px;padding-bottom: 5px;">

        Total Amount : RM &nbsp;<span id="amount"></span>

    </div>

    <button class="dt-button btn btn-primary mb-3 mb-md-0 mt-3" type="button" id="cart-confirm-btn">
      <span>Confirm</span>
    </button>


  </div>

</div>
<!--/ menu Table -->
<style>

  .product-variant{
    border: 1px solid #c8c8c8;
    padding: 5px;
    border-radius: 2px;
    width:60px;
    text-align:center;
    cursor:pointer;
  }
  .product-variant.active{
    border-color:black;
  }

</style>
<script>

var product_group_id={{ $product_group_id }};
var noImageUrl = "{{ asset('assets/img/no_image.jpg') }}";
</script>
@endsection
