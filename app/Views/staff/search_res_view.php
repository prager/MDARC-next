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
                <!-- <th>Deactivate</th> -->
              </tr>
            </thead>
            <tbody>
            <?php if (!empty($rows)): ?>
              <?php foreach ($rows as $m): ?>
                <tr>
                  <td><?= esc($m['id_members'] ?? $m['id_member'] ?? '') ?></td>
                  <td>
                    <?php if($m['id_users'] != 1) { ?>
                      <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editMem<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>
                    <?php } else { ?>
                      <?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?>
                    <?php } ?>
                  </td>
                  <?php include 'modal_update_mem.php'; ?>
                  <td><?= esc($m['cur_year'] ?? '') ?></td>
                  <td><?= esc($m['callsign'] ?? '') ?></td>
                  <td>
                    <?php
                    if($m['id_users'] != 1) {
                      $parentId = 0;
                      if ((int)($m['id_mem_types'] ?? 0) === 2) {
                        $parentId = (int)($m['id_members'] ?? 0);
                      } elseif (!empty($m['parent_primary'])) {
                        $parentId = (int)$m['parent_primary'];
                      }

                      if ($parentId > 0): ?>
                        <a href="#"
                          class="family-link text-decoration-none"
                          data-parent-id="<?= esc($parentId) ?>"
                          data-bs-toggle="modal"
                          data-bs-target="#parentModal">
                          <?= esc($m['type_description'] ?? $m['description'] ?? '') ?>
                        </a>
                      <?php else: ?>
                        <?= esc($m['type_description'] ?? $m['description'] ?? '') ?>
                      <?php endif;
                    } else { ?>
                      <?= esc($m['type_description'] ?? $m['description'] ?? '') ?>
                    <?php } ?>
                  </td>
                  <td><?= esc($m['license'] ?? '') ?></td>
                  <td><?= esc($m['email'] ?? '') ?></td>
                  <!-- <td class="text-center">
                    <?php if($m['id_users'] != 1) { ?>
                      <a href="#" data-bs-toggle="modal" data-bs-target="#delMem<?= esc($m['id_members']) ?>"><i class="bi bi-trash"></i></a>
                      <?php include 'mod_del_mem.php'; ?>
                    <?php } else { ?>
                      <i class="bi bi-trash"></i>
                    <?php } ?>
                  </td> -->
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center py-4 text-muted">No rows to display.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php include 'mod_family.php'; ?>
  </div>
</section>
