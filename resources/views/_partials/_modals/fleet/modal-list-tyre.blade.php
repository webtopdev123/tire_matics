<div class="modal fade" id="sizelistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Tyre List</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="axle_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Tyre</label>
            <select class="form-select" name="tyre_id">
              <option value="">- SELECT -</option>
            </select>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Position</label>
            <input type="number" name="tyre_position" class="form-control" placeholder="Position" autofocus />
          </div>
          <div class="col-12 text-center">
            <button id="btn-create" class="btn btn-primary me-sm-3 me-1">Set Tyre</button>
          </div>
        </form>

        <div class="table-responsive mx-n1 px-1 scrollbar mt-4" style="height: 450px;">
            <table id="size-table" class="table table-sm fs-9 border-top size-table" style="width: 100%;">
              <thead>
                <tr>
                  <th class="text-uppercase">NO#</th>
                  <th class="text-uppercase " style="min-width:150px;">Serial</th>
                  <th class="text-uppercase text-end white-space-nowrap">Position</th>
                  <th class="text-uppercase text-center " style="min-width:100px;width:100px;">Actions</th>
                </tr>
              </thead>
              <tbody class="list">

              </tbody>
            </table>
          </div>

      </div>
    </div>
  </div>
</div>
