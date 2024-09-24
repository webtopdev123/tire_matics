<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3 class="role-title mb-2">Create New Role</h3>
          <p class="text-muted">Set role permissions</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-3" onsubmit="return false">
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">Role Name</label>
            <input type="text" id="modalRoleName" name="role_name" class="form-control" placeholder="Role Name" tabindex="-1" />
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
                        <input class="form-check-input" type="checkbox" id="selectAll" />
                        <label class="form-check-label" for="selectAll">
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
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
