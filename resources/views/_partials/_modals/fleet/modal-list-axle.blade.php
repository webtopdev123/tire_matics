<div class="modal fade" id="modellistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Axle List</h3>
        </div>
        <form class="row" onsubmit="return false">
          <!-- <input name="fleet_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Axle Tyre Places</label>
            <select class="form-select" name="axle_tyre_places">
                <option value="">- SELECT -</option>
                <option value="2">2</option>
                <option value="4">4</option>
                <option value="6">6</option>
                <option value="8">8</option>
              </select>
          </div>
          <div class="col-12 text-center">
            <button id="btn-create" class="btn btn-primary me-sm-3 me-1">Add Axle</button>
          </div> -->
        </form>

          <div class="table-responsive mx-n1 px-1 scrollbar mt-4" style="height: 450px;">
            <table id="model-table" class="table table-sm fs-9 border-top model-table" style="width: 100%;">
              <thead>
                <tr>
                  <th class="text-uppercase white-space-nowrap">NO#</th>
                  <th class="text-uppercase white-space-nowrap">Axle</th>
                  <th class="text-uppercase text-end white-space-nowrap">Tyre Places</th>
                  <th class="text-uppercase text-end white-space-nowrap">Total Tyres</th>
                  <th class="text-uppercase text-center white-space-nowrap">Actions</th>
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
