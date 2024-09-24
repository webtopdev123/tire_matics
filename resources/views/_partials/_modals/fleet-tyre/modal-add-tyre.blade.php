<div class="modal fade" id="createTyreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Create Inventory</h3>
        </div>
        <form class="row" onsubmit="return false">
          <div class="col-md-6">
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Brand</label>
              <select class="form-select sel-brand" name="tyre_brand_id">
                <option value="">- SELECT -</option>
                @foreach ($tyreBrands as $brand)
                <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Model</label>
              <select class="form-select sel-brand-model" name="tyre_model_id">
                <option value="">- SELECT -</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Size</label>
              <select class="form-select sel-brand-model-size" name="tyre_size_id">
                <option value="">- SELECT -</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Original Tread Depth (mm)</label>
              <input type="number" name="tyre_odt" class="form-control" placeholder="Original Tread Depth (mm)" style="background: #f5f5f5 !important;"
                autofocus readonly />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Tyre Presure (psi)</label>
              <input type="number" name="tyre_psi" class="form-control" placeholder="Tyre Presure (psi)" autofocus readonly style="background: #f5f5f5 !important;" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Price (RM)</label>
              <input type="number" name="tyre_price" class="form-control" placeholder="Price" autofocus  />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Year</label>
              <input type="number" name="tyre_year" class="form-control" placeholder="Year" min=1000 max=9999
                autofocus />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Type</label>
              <select class="form-select" name="tyre_type">
                <option value="">- SELECT -</option>
                @foreach ($tyreType as $key => $type)
                <option value="{{ $key }}">{{ $type }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label" style="font-weight:bold;">Status</label>
              <select class="form-select" name="tyre_status" disabled>
                @foreach ($tyreStatus as $key => $status)
                <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Serial No.</label>
            <div class="d-flex">
              <div id="tyre-serial-list" class="flex-grow-1">
                <input type="text" name="tyre_serial[]" class="form-control mt-2 tyre_serial" placeholder="Serial No." autofocus />
              </div>
              <button class="btn btn-sm btn-icon btnAddSerial ml-2 mt-2 waves-effect waves-light"
              onclick="event.preventDefault();"
              style="margin-left: 6px;">
                <i class="ti ti-plus"></i>
              </button>
            </div>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button id="btn-create" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="reset" class="btn btn-danger btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
