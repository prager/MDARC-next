
<div class="container mt-5 pt-4">
  <div class="row">
    <div class="col">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">Listed in DB</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Deactivated</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">Mailing The Carrier</button>
        </li>
      </ul>

      <!-- Tab content -->
      <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="home" role="tabpanel">

          <!-- Put the main members here -->
          <div class="row">
            <div class="col">
                <?php if($forYear != 0 && $forYear != 99) { ?>
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
                                'paym_date' => 'Pay Date',
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
                            <?php if($col != 'parent_primary' && $col != 'mem_since' && $col != 'paym_date'&& $col != 'license' && $col != 'pl1') {?>
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
                            <td><?php echo date('m/d/Y', $m['paym_date']); ?></td>
                            <td class="text-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#delMem<?= esc($m['id_members']) ?>"><i class="bi bi-trash"></i></a>
                                <?php include 'mod_del_mem.php'; ?>
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
              <?php include 'page_nav.php'; ?>
              <?php include 'mod_family.php'; ?>  

          <!-- End of first tab -->
        </div>
        <div class="tab-pane fade mb-3" id="profile" role="tabpanel">
           <!-- Start of second tab -->
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
                      <th>License</th>
                      <th>Email</th>
                      <th>Purge</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if (!empty($deact)): ?>
                    <?php foreach ($deact as $m): ?>
                      <tr>
                        <td><?= esc($m['id_members'] ?? $m['id_member'] ?? '') ?></td>
                        <td>
                          <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#unDelMem<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>
                        </td>
                        <?php include 'mod_undel_mem.php'; ?>
                        <td><?= esc($m['cur_year'] ?? '') ?></td>
                        <td><?= esc($m['callsign'] ?? '') ?></td>
                        <td><?= esc($m['license'] ?? '') ?></td>
                        <td><?= esc($m['email'] ?? '') ?></td>
                        <td class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#purgeMem<?= esc($m['id_members']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                        <?php include 'mod_purge_mem.php'; ?>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No rows to display.</td></tr>
                  <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>                             
          </div>        
           <!-- End of second tab -->
        </div>
        <div class="tab-pane fade mb-3" id="contact" role="tabpanel">          
          <!-- Start of third tab -->
          <div class="card shadow-sm">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Address</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Zip</th>
                      <th>Email</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if (!empty($carr)): ?>
                    <?php foreach ($carr as $m): ?>
                      <tr>
                        <td><?= esc($m['id_members'] ?? $m['id_member'] ?? '') ?></td>
                        <td><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></td>
                        <td><?= esc($m['address'] ?? '') ?></td>
                        <td><?= esc($m['city'] ?? '') ?></td>
                        <td><?= esc($m['state'] ?? '') ?></td>
                        <td><?= esc($m['zip'] ?? '') ?></td>
                        <td><?= esc($m['email'] ?? '') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No rows to display.</td></tr>
                  <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>                             
          </div>  
          <!-- End of third tab -->

        </div>
      </div>
    </div>
  </div>      
</div>
  