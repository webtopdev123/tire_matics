<!-- Edit Member Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3 class="mb-2">Modify User</h3>
          <p class="text-muted">Modify User information</p>
        </div>
        <form id="editForm" class="row" onsubmit="return false">


          <div class="col-md-4 mb-3">
            <label class="form-label" for="editRole">Role</label>
            <select id="editRole" class="select2 form-select" name="role_id">
              <option value="">- SELECT -</option>
            </select>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label" for="editStatus">Status</label>
            <select id="editStatus" class="select2 form-select" name="status">
              <option value="">Select</option>
              <option value="1">Active</option>
              <option value="2">Pending</option>
            </select>
          </div>

          <hr>

          <div class="col-md-4 mb-3">
            <label class="form-label" for="editName">Login Username</label>
            <input type="text" id="editName" name="name" class="form-control" placeholder="Login Username" autofocus />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label" for="editLoginPass">Login Password</label>
            <input type="password" id="editLoginPass" name="password" class="form-control" placeholder="Login Password"
              autofocus />
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <input type="hidden" name="user_id" id="editUserId" value="" />
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Member Modal -->
