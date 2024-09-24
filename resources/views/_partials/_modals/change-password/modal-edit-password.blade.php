<!-- Edit Level Modal -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">

        <div class="text-center mb-4">
          <h3 class="mb-2">Change Password</h3>
        </div>

        <form id="editPasswordForm" class="row" onsubmit="return false">

          <div class="col-12 mb-3">
            <label class="form-label" for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Current Password"
              autofocus required />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password"
              autofocus required />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password"
              autofocus required />
          </div>

          <div class="col-sm-12 mb-3 col-12 text-center demo-vertical-spacing">
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
<!--/ Edit Level Modal -->
