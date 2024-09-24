<div class="modal fade" id="addInspectionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Create Inspection</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input type="hidden" name="tyre_id">
          <input type="hidden" name="fleet_id">
          <input type="hidden" name="axle_id">
          <input type="hidden" name="axle_position">
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Mileage (Km/hr)</label>
            <input type="number" name="inspection_mileage" class="form-control" placeholder="Mileage (Km/hr)" autofocus />
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Remaining Tread Depth (mm)</label>
            <input type="number" name="inspection_rtd" class="form-control" placeholder="Remaining Tread Depth (mm)" autofocus />
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Pressure (psi)</label>
            <input type="number" name="inspection_psi" class="form-control" placeholder="Pressure (psi)" autofocus />
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="button" class="btn btn-danger btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
        <div class="table-responsive mx-n1 px-1 scrollbar mt-4" style="height: 450px;">
            <table id="model-table" class="table table-sm fs-9 border-top model-table" style="width: 100%;">
              <thead>
                <tr>
                  <th class="text-uppercase white-space-nowrap text-left">S/N</th>
                  <th class="text-uppercase white-space-nowrap text-center">Mileage (km/hr)</th>
                  <th class="text-uppercase white-space-nowrap text-center">RTD (mm)</th>
                  <th class="text-uppercase white-space-nowrap text-center">Pressure (psi)</th>
                  <th class="text-uppercase white-space-nowrap text-center">Date</th>
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
