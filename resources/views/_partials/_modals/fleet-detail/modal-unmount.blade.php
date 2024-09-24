<div class="modal fade" id="unmountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Unmount Tyre</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input type="hidden" name="tyre_id">
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Status</label>
            <select class="form-select" name="log_status">
              <option value="RETREADING">Retreading</option>
              <option value="DISPOSED">Disposed</option>
              <option value="SPARE">Spare</option>
            </select>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Remark (optional)</label>
            <textarea name="log_remark" class="form-control" rows="4"></textarea>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="reset" class="btn btn-danger btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>