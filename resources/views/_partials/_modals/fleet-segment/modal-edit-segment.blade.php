<div class="modal fade" id="updatecategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Update Segment</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="segment_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label">Parent Segment</label>
            <select class="select2 form-select" name="parent_id">
              <option value="0">- SELECT -</option>
              @foreach ($segmentParents as $segment)
              <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Segment Name</label>
            <input type="text" name="segment_name" class="form-control" placeholder="Segment Name" autofocus />
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
