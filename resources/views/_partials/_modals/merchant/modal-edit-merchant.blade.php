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

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Merchant Name</label>
                        <input type="text" name="merchant_name" class="form-control"
                            placeholder="Merchant Name" autofocus/>
                    </div>

                    <!-- <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Merchant Url (https://ecatalog.cloud/<span id="url_change"></span>)</label>
                        <input type="text" name="merchant_url" class="form-control" placeholder="Merchant Url"/>
                    </div> -->

                    <!-- <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Merchant Url</label>
                        <input type="text" name="merchant_url" class="form-control"
                            placeholder="Merchant Url"/>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Merchant Logo</label>
                        <div class="d-flex">
                          <img src="" style="width:40px;height:40px;object-fit:cover;margin-right:10px;" id="merchant_logo_image"/>
                          <input type="file" name="merchant_logo" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Whatsapp</label>
                        <input type="text" name="merchant_whatsapp" class="form-control"
                            placeholder="Whatsapp"/>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="merchant_facebook" class="form-control"
                            placeholder="Facebook"/>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Instagram</label>
                        <input type="text" name="merchant_instagram" class="form-control"
                            placeholder="Instagram"/>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <label class="form-label">Tiktok</label>
                        <input type="text" name="merchant_tiktok" class="form-control"
                            placeholder="Tiktok"/>
                    </div>

                    <div class="col-6 mb-0 mt-1">
                        <input type="checkbox" name="merchant_priceshow" class="form-check-input" value="1" style="margin-right:5px;"/>
                        <label class="form-label">Merchant Price Show</label>
                    </div> -->

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
<!--/ Add Role Modal -->
