<!-- Add Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3 class="role-title mb-2">Modify Role</h3>
          <p class="text-muted">Modify role permissions</p>
        </div>
        <!-- Add role form -->
        <form id="editRoleForm" class="row g-3" onsubmit="return false">
          <div class="col-12 mb-4">
            <label class="form-label" for="editRoleName">Role Name</label>
            <input type="text" id="editRoleName" name="role_name" class="form-control" placeholder="Enter a role name" tabindex="-1" />
          </div>
          <div class="col-12">
            <h5>Role Permissions</h5>
            <!-- Permission table -->
            <div class="table-responsive max-height-350">
              <table class="table table-flush-spacing" id="permissionTable">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium">Administrator Access <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i></td>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editSelectAll" />
                        <label class="form-check-label" for="editSelectAll">
                          Select All
                        </label>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center mt-4">
            <input type="hidden" id="role_id" name="role_id"/>
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
