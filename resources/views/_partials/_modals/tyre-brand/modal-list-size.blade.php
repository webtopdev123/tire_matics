<div class="modal fade" id="sizelistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Size List</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="model_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Name</label>
            <input type="text" name="size_name" class="form-control" placeholder="Name" autofocus />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Original Tread Depth (mm)</label>
            <input type="number" name="size_tread_depth" class="form-control" placeholder="Original Tread Depth" autofocus />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Tyre Pressure (psi)</label>
            <input type="number" name="size_psi" class="form-control" placeholder="Tyre Pressure" autofocus />
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button id="btn-create" class="btn btn-primary me-sm-3 me-1">Create</button>
          </div>
        </form>

          <div class="table-responsive mx-n1 px-1 scrollbar mt-4">
            <table id="size-table" class="table table-sm fs-9 border-top size-table" style="width: 100%;">
              <thead>
                <tr>
                  <th class="text-uppercase" style="width: 30px;">NO#</th>
                  <th class="text-uppercase white-space-nowrap">Name</th>
                  <th class="text-uppercase white-space-nowrap">Detail</th>
                  <th class="" style="width: 40px;">Actions</th>
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
