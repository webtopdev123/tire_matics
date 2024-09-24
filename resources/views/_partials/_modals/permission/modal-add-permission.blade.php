<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">

        <div class="text-center mb-4">
          <h3 class="mb-2">Create Permission</h3>
        </div>

        <form id="addPermissionForm" class="row" onsubmit="return false">

          <div class="col-12 mb-3">
            <label class="form-label" for="modalPermissionName">Permission Name</label>
            <input type="text" id="modalPermissionName" name="permission_name" class="form-control"
              placeholder="Permission Name" autofocus />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="modalPermissionName">Permission Code</label>
            <input type="text" id="modalPermissionCode" name="permission_code" class="form-control"
              placeholder="Permission Code" autofocus />
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
<!--/ Add Permission Modal -->
