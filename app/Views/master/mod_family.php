<!-- Parent Details Modal -->
<div class="modal fade" id="parentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Family Details</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
            <div id="parent-loading" class="text-muted">Loading…</div>
            <div id="parent-error" class="alert alert-danger d-none"></div>

            <h6 class="mt-3">Paying Member</h6>
            <div id="parent-data" class="d-none">
                <div class="row g-2 mb-3">
                <div class="col-sm-2"><strong>ID:</strong> <span id="p-id"></span></div>
                <div class="col-sm-4"><strong>Name:</strong> <span id="p-fname"></span>  <span id="p-lname"></span></div>
                <div class="col-sm-5"><strong>Email:</strong> <span id="p-email"></span></div>
                </div>

                <h6 class="mt-3">Family Members</h6>
                <div id="childrenEmpty" class="alert alert-warning d-none mb-2">No family members found.</div>
                <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th><th>Name</th><th>Callsign</th><th>Email</th>
                        <th>License</th><th>Mem Since</th><th>Remove</th>
                    </tr>
                    </thead>
                    <tbody id="childrenBody"></tbody>
                </table>
                </div>
            </div>
            <form id="existingMemForm" method="post" action="">
            <input type="hidden" id="par_id_input" name="par_id_input" value="">
                <div class="row my-3">
                    <div class="col-lg-4">
                        <label for="id_existing_mem" class="form-label">Add a Family Member</label>
                        <input class="form-control" id="id_existing_mem" name="id_existing_mem" type="text" placeholder="ID Members" aria-label="Existing ID Members">
                    </div>
                    <div class="col-lg-4 d-flex flex-column d-grid">
                        <label for="id_mem_type">Member Type</label>
                        <select id="id_mem_type" name="id_mem_type" class="form-select mt-auto" required>
                            <option value="3" selected>Spouse</option>
                            <option value="4">Additional</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex flex-column d-grid">
                        <button type="submit" class="btn btn-outline-secondary mt-auto">Submit</button>
                    </div>
                </div>
            </form>
            <div class="row mt-3">
            <div class="col">
                <div class="accordion" id="accAddFam">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Click to Add a New Family Member
                    </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accAddFam">
                    <div class="accordion-body">
                    <form id="parentForm" method="post" action="">
                    <input type="hidden" id="parent_id_input" name="parent_id_input" value="">
                        <section class="px-2">
                        <div class="row mb-3">
                            <div class="col-lg-3">
                            <div class="form-check">
                                <label class="form-check-label" for="arrl"> ARRL Member</label>
                                <input class="form-check-input" type="checkbox" name="arrl" />
                            </div>
                            </div>
                            <div class="col-lg-4">
                            <div class="form-check">
                                <label class="form-check-label" for="arrl"> List in Directory OK </label>
                                <input class="form-check-input" type="checkbox" name="ok_mem_dir" />
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg py-1">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name">
                            </div>
                            <div class="col-lg py-1">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name">
                            </div>
                            <div class="col-lg py-1">
                                <label for="callsign">Callsign</label>
                                <input type="text" class="form-control" id="callsign" name="callsign" placeholder="Enter Callsign">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 py-1">
                                <label for="sel_lic">License Type</label>
                                <select class="form-select" name="sel_lic">
                                    <?php
                                    foreach($lic as $license) {
                                        if($license == 'Technician') { ?>
                                        <option value="<?php echo $license; ?>" selected><?php echo $license; ?></option>
                                    <?php    }
                                        else { ?>
                                        <option value="<?php echo $license; ?>"><?php echo $license; ?></option>
                                    <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6 py-1">
                            <label for="id_mem_types">Member Type</label>
                            <select id="id_mem_types" name="id_mem_types" class="form-select" required>
                                <option value="3" selected>Spouse</option>
                                <option value="4">Additional</option>
                            </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg py-1">
                            <label for="w_phone">Cell Phone</label>
                            <input type="text" class="form-control" id="w_phone" name="w_phone" placeholder="000-000-0000">
                            </div>
                            <div class="col-lg py-1">
                            <label for="h_phone">Home Phone</label>
                            <input type="text" class="form-control" id="h_phone" name="h_phone" placeholder="000-000-0000">
                            </div>
                            <div class="col-lg py-1">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col py-1">
                                <label for="comment">Comments</label>
                                <textarea
                                class="form-control" id="comment" name="comment" rows="3" placeholder="Any Comment"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </div>
                        </section>
                    </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            </div>
            <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>