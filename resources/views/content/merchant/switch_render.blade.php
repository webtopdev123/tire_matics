<li>
  <a class="dropdown-item {{ Auth::user()->merchant_id == 0 ? 'active' : '' }}" href="{{ route('merchant.switch', ['merchant_id' => 0]) }}" data-merchant="{{ 0 }}">
    <span class="align-middle">All</span>
  </a>
</li>
@foreach($arrayMerchant as $merchant)
<li>
  <a class="dropdown-item {{ Auth::user()->merchant_id == $merchant->merchant_id ? 'active' : '' }}" href="{{ route('merchant.switch', ['merchant_id' => $merchant->merchant_id]) }}" data-merchant="{{ $merchant->merchant_id }}">
    <span class="align-middle">{{ $merchant->merchant_name }}</span>
  </a>
</li>
@endforeach
