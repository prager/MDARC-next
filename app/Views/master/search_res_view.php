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
                  <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editMem<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>
                  <?php include 'modal_update_mem.php'; ?>
                  <td><?= esc($m['cur_year'] ?? '') ?></td>
                  <td><?= esc($m['callsign'] ?? '') ?></td>
                  <td>
                    <?php if (!empty($m['parent_primary'])): ?>
                    <a href="#"
                      class="parent-link text-decoration-none"
                      data-id="<?= esc($m['parent_primary']) ?>"
                      data-bs-toggle="modal"
                      data-bs-target="#parentModal">
                      <?= esc($m['type_description']) ?>
                    </a>
                    <?php else: ?>
                        <?= esc($m['type_description']) ?>
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
        <div class="modal fade" id="parentModal" tabindex="-1" aria-labelledby="parentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="parentModalLabel">Parent Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="parent-loading" class="text-muted">Loading…</div>
                    <dl class="row mb-0 d-none" id="parent-data">
                        <dt class="col-sm-4">ID</dt><dd class="col-sm-8" id="p-id"></dd>
                        <dt class="col-sm-4">First Name</dt><dd class="col-sm-8" id="p-fname"></dd>
                        <dt class="col-sm-4">Last Name</dt><dd class="col-sm-8" id="p-lname"></dd>
                        <dt class="col-sm-4">Email</dt><dd class="col-sm-8" id="p-email"></dd>
                    </dl>
                <div id="parent-error" class="alert alert-danger d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>
