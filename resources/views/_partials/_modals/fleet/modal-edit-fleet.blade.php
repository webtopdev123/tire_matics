<div class="modal fade" id="updatecategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Update Fleet</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="fleet_id" hidden />
          <div class="col-md-6">
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Plate</label>
              <input type="text" name="fleet_plate" class="form-control input-plate" placeholder="Plate" autofocus />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Code</label>
              <input type="text" name="fleet_code" class="form-control input-plate" placeholder="Code" autofocus />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Configuration</label>
              <select class="form-select" name="configuration_id" disabled>
                <option value="">- SELECT -</option>
                @foreach ($fleetConfigurations as $config)
                <option value="{{ $config->configuration_id }}">{{ $config->configuration_type . ' (' .
                  $config->configuration_name . ')' }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Segment</label>
              <select class="form-select" name="segment_id">
                <option value="">- SELECT -</option>
                @foreach ($fleetSegments as $segment)
                <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Category</label>
              <select class="form-select" name="category_id">
                <option value="">- SELECT -</option>
                @foreach ($fleetBrandCategories as $category)
                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Goods</label><br>
              <div class="d-flex flex-column border border-1 p-2" style="max-height: 120px; overflow-y: scroll;">
                @foreach ($fleetGoods as $good)
                <label class="form-label fs-6 m-0"><input type="checkbox" name="fleet_goods_id[]"
                    value="{{ $good->goods_id }}" /> {{ $good->goods_name }}</label>
                @endforeach
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Brand</label>
              <select class="form-select sel-brand" name="fleet_brand_id">
                <option value="">- SELECT -</option>
                @foreach ($fleetBrands as $brand)
                <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Model</label>
              <select class="form-select sel-brand-model" name="fleet_brand_model_id">
                <option value="">- SELECT -</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Rim Width (inch)</label>
              <input type="number" name="fleet_rim_width" class="form-control" placeholder="Rim Width" autofocus />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Spare Tyre (unit)</label>
              <select class="form-select" name="fleet_spare_tyre">
                <option value="">- SELECT -</option>
                <option value="1">1</option>
                <option value="2">2</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">BDM (Kg)</label>
              <input type="number" name="fleet_bdm" class="form-control" placeholder="BDM" autofocus />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">BTM (Kg)</label>
              <input type="number" name="fleet_btm" class="form-control" placeholder="BTM" autofocus />
            </div>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button id="btn-update" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="reset" class="btn btn-danger btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>