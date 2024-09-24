<!-- Add Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content p-3 p-md-5">
          <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-body">
              <div class="text-center mb-4">
                  <h3 class="role-title mb-2">Modify Merchant</h3>
                  <p class="text-muted">Modify merchant information</p>
              </div>
              <form id="editForm" class="row g-3" onsubmit="return false">
                  <div class="col-12 mb-3">
                    <label class="form-label" for="merchant_logo">Image</label>
                    <input type="file" id="merchant_logo" name="merchant_logo" class="form-control" required />
                  </div>

                  <div class="col-12 mb-3">
                      <img src="" style="width:150px;height:100px;object-fit:cover;" id="display_merchant_logo"/>
                  </div>

                  <div class="col-12 mb-0 mt-1">
                    <label class="form-label">Merchant Url (https://ecatalog.cloud/<span id="url_change"></span>)</label>
                    <input type="url" id="merchant_url" name="merchant_url" class="form-control"
                        placeholder="Merchant Url" oninput="validateProfileUrl(this)"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                    <label class="form-label">Email <span style="color: red;">(Compulsory)</span></label>
                    <input type="email" name="merchant_email" id="merchant_email" class="form-control"
                        placeholder="Email"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                    <label class="form-label">Phone Number <span style="color: red;">(Compulsory)</span></label>
                    <input type="text" name="merchant_phone" id="merchant_phone" class="form-control"
                        placeholder="Phone Number"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                      <label class="form-label">Whatsapp <span style="color: red;">(Compulsory)</span></label>
                      <input type="url" name="merchant_whatsapp" class="form-control"
                          placeholder="Whatsapp"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                      <label class="form-label">Facebook <span style="color: red;">(Optional)</span></label>
                      <input type="url" name="merchant_facebook" class="form-control"
                          placeholder="Facebook"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                      <label class="form-label">Instagram <span style="color: red;">(Optional)</span></label>
                      <input type="url" name="merchant_instagram" class="form-control"
                          placeholder="Instagram"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                      <label class="form-label">Tiktok <span style="color: red;">(Optional)</span></label>
                      <input type="url" name="merchant_tiktok" class="form-control"
                          placeholder="Tiktok"/>
                  </div>

                  <div class="col-6 mb-0 mt-1">
                    <label class="form-label">Skin Color</label>
                    <input type="color" name="merchant_skincolor" id="merchant_skincolor" class="form-control"
                        placeholder="Skin Color"/>
                  </div>

                  <div class="col-12 mb-0 mt-4">
                      <input type="checkbox" name="merchant_priceshow" class="form-check-input" value="1" style="margin-right:5px;"/>
                      <label class="form-label">Show Price</label>
                  </div>

                  <div class="col-12 text-center mt-4">
                      <input type="hidden" id="merchant_id" name="merchant_id" />
                      <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                          aria-label="Close">Cancel</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
  function validateProfileUrl(input) {
    // Get the current value of the input
    let currentValue = input.value;

    // Convert letters to lowercase
    currentValue = currentValue.toLowerCase();

    // Remove any non-alphabetic characters, except numbers
    currentValue = currentValue.replace(/[^a-z0-9]/g, '');

    // Update the input value
    input.value = currentValue;

    document.getElementById('url_change').textContent = currentValue;
  }
</script>
<!--/ Add Role Modal -->
