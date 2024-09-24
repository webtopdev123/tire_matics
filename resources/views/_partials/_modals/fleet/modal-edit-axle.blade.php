<div class="modal fade" id="updatemodelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Update Model</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="axle_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Axle Tyre Places</label>
            <select class="form-select" name="axle_tyre_places">
              <option value="">- SELECT -</option>
              <option value="2">2</option>
              <option value="4">4</option>
              <option value="6">6</option>
              <option value="8">8</option>
            </select>
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