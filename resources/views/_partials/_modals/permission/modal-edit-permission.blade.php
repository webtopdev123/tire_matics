<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">

        <div class="text-center mb-4">
          <h3 class="mb-2">Edit Permission</h3>
        </div>

        <form id="editPermissionForm" class="row" onsubmit="return false">

          <div class="col-sm-12 mb-3">
            <label class="form-label" for="editPermissionName">Permission Name</label>
            <input type="text" id="editPermissionName" name="edit_permission_name" class="form-control"
              placeholder="Permission Name" autofocus />
          </div>

          <div class="col-sm-12 mb-3">
            <label class="form-label" for="editPermissionCode">Permission Code</label>
            <input type="text" id="editPermissionCode" name="edit_permission_code" class="form-control"
              placeholder="Permission Code" autofocus />
          </div>

          <div class="col-sm-12 mb-3 col-12 text-center demo-vertical-spacing">
            <input type="hidden" id="permission_id" />
            <label class="form-label invisible d-none d-sm-inline-block">Button</label>
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
<!--/ Edit Permission Modal -->