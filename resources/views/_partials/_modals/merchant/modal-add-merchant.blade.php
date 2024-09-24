<!-- Add Role Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="title mb-2">Create Merchant</h3>
                    <p class="text-muted">Set merchant information</p>
                </div>
                <!-- Add role form -->
                <form id="addForm" class="row g-3" onsubmit="return false">

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
                        <input type="file" name="merchant_logo" class="form-control"/>
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
                        <input type="checkbox" name="merchant_priceshow" class="form-check-input" value="1" style="margin-right:5px;" checked/>
                        <label class="form-label">Merchant Price Show</label>
                    </div> -->

                    <hr>

                    <div class="col-4 mb-0 mt-0">
                        <label class="form-label" for="addLoginUsername">Login Username</label>
                        <input type="text" id="addLoginUsername" name="name" class="form-control" placeholder="Login Username"/>
                    </div>

                    <div class="col-4 mb-0 mt-0">
                        <label class="form-label" for="addLoginPass">Login Password</label>
                        <input type="password" id="addLoginPass" name="password" class="form-control" placeholder="Login Password"/>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Create</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
