<div class="modal fade" id="createcategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5 pb-md-0 pt-md-2">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body pb-md-4">
        <div class="text-center mb-4">
          <h3 class="mb-2">Create Category</h3>
        </div>
        <form class="row" onsubmit="return false">
          <div class="col-12 mb-3">
            <label class="form-label" for="add_product_parent_category">Parent Category</label>
            <select id="add_product_parent_category" class="select2 form-select" name="parent_id">
              <option value="">- SELECT -</option>
            </select>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label" style="font-weight:bold;">Category Name</label>
            <input type="text" name="category_name" class="form-control" placeholder="Category Name" autofocus />
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button id="btn-create" class="btn btn-primary me-sm-3 me-1">Create</button>
            <button type="reset" class="btn btn-danger btn-label-secondary" data-bs-dismiss="modal"
              aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
