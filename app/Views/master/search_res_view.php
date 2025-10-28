<section id="learn" class="p-5">
<div class="container pt-4">
    <?php if (isset($flash) && $flash !== ''): ?>
      <div class="alert alert-<?= esc($flashType) ?>"><?= esc($flash) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Cur Year</th>
                <th>Callsign</th>
                <th>Mem Type</th>
                <th>License</th>
                <th>Email</th>
                <th>Manual Payment</th>
                <th>Deactivate</th>
              </tr>
            </thead>
            <tbody>
            <?php if (!empty($rows)): ?>
              <?php foreach ($rows as $m): ?>
                <tr>
                  <td><?= esc($m['id_members'] ?? $m['id_member'] ?? '') ?></td>
                  <td>
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editMem<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>
                  </td>
                  <?php include 'modal_update_mem.php'; ?>
                  <td><?= esc($m['cur_year'] ?? '') ?></td>
                  <td><?= esc($m['callsign'] ?? '') ?></td>
                  <td>
                    <?php
                    // Decide which ID the modal should load
                    $parentId = 0;
                    if ((int)($m['id_mem_types'] ?? 0) === 2) {
                        // Primary member: show their own children
                        $parentId = (int)($m['id_members'] ?? 0);
                    } elseif (!empty($m['parent_primary'])) {
                        // Child member: open the family using the parent's id
                        $parentId = (int)$m['parent_primary'];
                    }

                    if ($parentId > 0): ?>
                      <a href="#"
                        class="family-link text-decoration-none"
                        data-parent-id="<?= esc($parentId) ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#parentModal">
                        <?= esc($m['type_description'] ?? '') ?>
                      </a>
                    <?php else: ?>
                      <?= esc($m['type_description'] ?? '') ?>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($m['license'] ?? '') ?></td>
                  <td><?= esc($m['email'] ?? '') ?></td>
                  <td>
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#manPayment<?= esc($m['id_members']) ?>">Make Payment</a>
                    <?php include 'mod_man_payment.php'; ?>
                  </td>
                  <td class="text-center">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#delMem<?= esc($m['id_members']) ?>"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center py-4 text-muted">No rows to display.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>

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
                    <div class="col-sm-3"><strong>Name:</strong> <span id="p-fname"></span>  <span id="p-lname"></span></div>
                    <div class="col-sm-4"><strong>Email:</strong> <span id="p-email"></span></div>
                  </div>

                  <h6 class="mt-3">Family Members</h6>
                  <div id="childrenEmpty" class="alert alert-warning d-none mb-2">No child members found.</div>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>ID</th><th>Name</th><th>Callsign</th><th>Email</th>
                          <th>License</th><th>Member Since</th><th>Pay Date</th>
                        </tr>
                      </thead>
                      <tbody id="childrenBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="accordion" id="accAddFam">
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Click to Add a Family Member
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accAddFam">
                        <div class="accordion-body">
                        <form id="parentForm" method="post" action="">
                          <div class="modal-body">
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
                                <label for="sel_lic">Member Type</label>
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
                                  class="form-control" id="comment" name="comment" rows="7" placeholder="Any Comment"></textarea>
                              </div>
                            </div>
                          </section>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
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

        </div>
      </div>
    </div>
  </div>
</section>
