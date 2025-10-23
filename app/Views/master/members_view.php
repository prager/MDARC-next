

<div class="container mt-5 pt-4">
    <div class="row">
    <h4 class="mb-3">Current Members</h4>
        <div class="col">
        <div class="table-responsive shadow-sm">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-light">
      <tr>
        <?php
          // Helper to flip sort direction
          function nextDir($cur) { return $cur === 'ASC' ? 'DESC' : 'ASC'; }

          $columns = [
              'id_members'  => 'ID',
              'lname'  => 'Name',
              'email'      => 'Email',
              'callsign'      => 'Callsign',
              'cur_year'      => 'Current Year',
              'parent_primary'      => 'Parent ID',
          ];
        ?>

        <?php foreach ($columns as $col => $label): 
            $arrow = ($sort === $col)
                ? ($dir === 'ASC' ? '↑' : '↓')
                : '';
            $link = site_url('members?sort=' . $col . '&dir=' . nextDir($dir));
        ?>
          <th>
          <?= esc($label) ?>
          <a href="<?= $link ?>" class="ms-1 text-decoration-none fs-3 fw-bold" style="color: black;"><?= $arrow ?: '↕' ?></a>   
          </th>
        <?php endforeach; ?>
      </tr>
      </thead>

      <tbody>
      <?php foreach ($members as $m): ?>
        <tr>
          <td><?= esc($m['id_members']) ?></td>
          <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#detailsModal"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a></td>
          <td><?= esc($m['email'] ?? '') ?></td>
          <td><?= esc($m['callsign'] ?? '') ?></td>
          <td><?= esc($m['cur_year'] ?? '') ?></td>
          <td>
          <?php if (!empty($m['parent_primary'])): ?>
              <a href="#"
                 class="parent-link text-decoration-none"
                 data-id="<?= esc($m['parent_primary']) ?>"
                 data-bs-toggle="modal"
                 data-bs-target="#parentModal">
                <?= esc($m['parent_primary']) ?>
              </a>
            <?php else: ?>
              0
            <?php endif; ?>
          </td>
          <!-- Modal -->
          <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailsModalLabel">Primary Member ID: <?= esc($m['id_members']) ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="col offset-lg-1">
                        Name: <?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?><br />
                        Email: <?= esc($m['email'] ?? '') ?> <br />
                        Callsign: <?= esc($m['callsign'] ?? '') ?><br />
                        Address: <br /> <?= esc($m['address'] ?? '') ?><br />
                        <?= esc($m['city'] ?? ''). ', ' . esc($m['state'] ?? '') . ' ' . esc($m['zip'] ?? '') ?>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
          </div>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
        </div>

        <!-- Pagination links -->
        <div class="py-3">
            <div class="my-pager" style=""><?= $pager->links('members', 'default_full') ?></div>
        </div>

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
  
