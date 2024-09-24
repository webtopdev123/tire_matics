<div class="modal fade" id="modellistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2" >
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Model List</h3>
        </div>
        <form class="row" onsubmit="return false">
          <input name="brand_id" hidden />
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Model Name</label>
            <input type="text" name="model_name" class="form-control" placeholder="Model Name" autofocus />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
          </div>
        </form>


          <div class="table-responsive" id="fleet_brand_model_list" style="height: 450px;">
              <table class="datatables-brand-model table table-sm fs-9 border-top" style="width: 100%;">
                  <thead>
                      <tr>
                          <th class="align-center" style="width:30px">NO#</th>
                          <th class="align-center">Model Name</th>
                          <th class="align-center" style="width: 40px;">Actions</th>
                      </tr>
                  </thead>
              </table>
          </div>

      </div>
    </div>
  </div>
</div>
