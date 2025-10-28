



<div class="container mt-5 pt-4">
  <div class="row">
    <div class="col">
        <?php if($forYear != 0) { ?>
            <h4 class="mb-3">Current Members</h4>
            <p> Total of <?php echo $numMems; ?> members. Click for <a href="<?php echo base_url() . 'index.php/all-members'; ?>" class="text-decoration-none">All Members</a></p>
        <?php } else { ?>
            <h4 class="mb-3">All Members</h4>
            <p>Total of <?php echo $numMems; ?> members. Click for <a href="<?php echo base_url() . 'index.php/members'; ?>" class="text-decoration-none">Current Members</a> only</p>
        <?php } ?>
      </div>
  </div>
  <div class="row">
      <div class="col">
      <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-striped align-middle mb-0">
            <thead class="table-light">
            <tr>
              <?php
                // Helper to flip sort direction
                function nextDir($cur) { return $cur === 'ASC' ? 'DESC' : 'ASC'; }

                $columns = [
                    'lname'  => 'Name',
                    'email'      => 'Email',
                    'callsign'      => 'Callsign',
                    'license' => 'License',
                    'mem_since'      => 'Member Since',
                    'parent_primary'      => 'Mem Type',
                    'pay_date' => 'Pay Date',
                    'pl1' => 'Deactivate'
                ];
              ?>

              <?php foreach ($columns as $col => $label):
                  $arrow    = ($sort === $col) ? ($dir === 'ASC' ? '↑' : '↓') : '↕';
                  $arrowDir = ($sort === $col) ? nextDir($dir) : 'ASC';
                  if($forYear != 0) {
                      $link     = site_url('members?sort=' . $col . '&dir=' . $arrowDir . '&page=' . (int)($page));
                  }
                  else {
                      $link     = site_url('all-members?sort=' . $col . '&dir=' . $arrowDir . '&page=' . (int)($page));
                  }
              ?>
                <th>
                <?= esc($label) ?>
                <?php if($col != 'parent_primary' && $col != 'mem_since' && $col != 'pay_date'&& $col != 'license' && $col != 'pl1') {?>
                  <a href="<?= $link ?>" class="ms-1 text-decoration-none fs-4 fw-bold" style="color: black;"><?= $arrow ?: '↕' ?></a>
                <?php } ?>
                </th>
              <?php endforeach; ?>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($members as $m): ?>
              <tr>
                <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editMem<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>
              </td>
              <?php include 'modal_update_mem.php'; ?>
                <td><?= esc($m['email'] ?? '') ?></td>
                <td><?= esc($m['callsign'] ?? '') ?></td>
                <td><?= esc($m['license'] ?? '') ?></td>
                <td><?= esc($m['mem_since'] ?? '') ?></td>
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
                        <?= esc($m['description'] ?? '') ?>
                      </a>
                    <?php else: ?>
                      <?= esc($m['description'] ?? '') ?>
                    <?php endif; ?>
                </td>
                <td><?= esc(date('m-d-Y', strtotime($m['pay_date'])) ?? '') ?></td>
                <td class="text-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#delMem<?= esc($m['id_members']) ?>"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          </div>
          </div>
          </div>

        </div>
      </div>
    <div class="row">
      <div class="col">
      <?php
        // Inputs passed from controller:
        // $page (int), $perPage (int), $total (int), $sort (string), $dir (string)

        $page       = max(1, (int)($page ?? 1));
        $perPage    = max(1, (int)($perPage ?? 20));
        $total      = max(0, (int)($total ?? 0));
        $pageCount  = max(1, (int)ceil($total / $perPage));

        $maxLinks = 4; // show at most 4 numbered links
        $half     = intdiv($maxLinks, 2);

        // compute sliding window [start..end]
        $start = max(1, $page - $half);
        $end   = min($pageCount, $start + $maxLinks - 1);
        if ($end - $start + 1 < $maxLinks) {
            $start = max(1, $end - $maxLinks + 1);
        }

        // helper to keep sort/dir in URLs
        if($forYear != 0) {
            function pageUrl($p, $sort, $dir) {
                return site_url('members?sort=' . urlencode($sort) . '&dir=' . urlencode($dir) . '&page=' . (int)$p);
            }
        }
        else {
            function pageUrl($p, $sort, $dir) {
                return site_url('all-members?sort=' . urlencode($sort) . '&dir=' . urlencode($dir) . '&page=' . (int)$p);
            }
        }

        ?>
        <nav aria-label="Page navigation" class="mt-3">
          <ul class="pagination">

            <!-- First / Prev -->
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page > 1 ? pageUrl(1, $sort, $dir) : '#' ?>" aria-label="First">&laquo;</a>
            </li>
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page > 1 ? pageUrl($page - 1, $sort, $dir) : '#' ?>" aria-label="Previous">&lsaquo;</a>
            </li>

            <!-- Numbered (max 4) -->
            <?php for ($i = $start; $i <= $end; $i++): ?>
              <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= pageUrl($i, $sort, $dir) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>

            <!-- Next / Last -->
            <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page < $pageCount ? pageUrl($page + 1, $sort, $dir) : '#' ?>" aria-label="Next">&rsaquo;</a>
            </li>
            <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page < $pageCount ? pageUrl($pageCount, $sort, $dir) : '#' ?>" aria-label="Last">&raquo;</a>
            </li>
          </ul>
        </nav>               
      </div>
    </div>
  </div>
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

              <div class="row mt-3">
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

    </div>    
  </div>    
</div>
  