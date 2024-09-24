<!-- Add Member Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="mb-2">Create User</h3>
                    <p class="text-muted">Set User information</p>
                </div>
                <form id="addForm" class="row" onsubmit="return false">

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="addRole">Role</label>
                        <select id="addRole" class="select2 form-select" name="role_id" autofocus>
                            <option value="">- SELECT -</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="addStatus">Status</label>
                        <select id="addStatus" class="select2 form-select" name="status" autofocus>
                            <option value="1">Active</option>
                            <option value="2">Pending</option>
                        </select>
                    </div>

                    <hr>

                    <div class="col-md-4 mb-3">
                      <label class="form-label" for="addName">Login Username</label>
                      <input type="text" id="addName" name="name" class="form-control"
                          placeholder="Login Username" autofocus />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="addLoginPass">Login Password</label>
                        <input type="password" id="addLoginPass" name="password" class="form-control"
                            placeholder="Login Password" autofocus />
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
<!--/ Add Member Modal -->
