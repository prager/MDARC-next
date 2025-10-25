

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
              'lname'  => 'Name',
              'email'      => 'Email',
              'callsign'      => 'Callsign',
              'cur_year'      => 'Current Year',
              'parent_primary'      => 'Mem Type',
          ];
        ?>

        <?php foreach ($columns as $col => $label):
            $arrow    = ($sort === $col) ? ($dir === 'ASC' ? '↑' : '↓') : '↕';
            $arrowDir = ($sort === $col) ? nextDir($dir) : 'ASC';
            $link     = site_url('members?sort=' . $col . '&dir=' . $arrowDir . '&page=' . (int)($page));
        ?>
          <th>
          <?= esc($label) ?>
          <?php if($col != 'parent_primary' && $label != 'Current Year') {?>
            <a href="<?= $link ?>" class="ms-1 text-decoration-none fs-4 fw-bold" style="color: black;"><?= $arrow ?: '↕' ?></a>
          <?php } ?>
          </th>
        <?php endforeach; ?>
      </tr>
      </thead>

      <tbody>
      <?php foreach ($members as $m): ?>
        <tr>
          <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#detailsModal<?= esc($m['id_members']) ?>"><?= esc($m['lname'] ?? '') . ', ' .  esc($m['fname'] ?? '') ?></a>

        <!-- Modal -->
        <div class="modal fade" id="detailsModal<?= esc($m['id_members']) ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= esc($m['id_members']) ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailsModalLabel<?= esc($m['id_members']) ?>">Primary Member ID: <?= esc($m['id_members']) ?></h1>
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
        <!-- End of Modal -->

        </td>
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
                <?= esc($m['description']) ?>
              </a>
            <?php else: ?>
                <?= esc($m['description']) ?>
            <?php endif; ?>
          </td>
          
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
        </div>

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
function pageUrl($p, $sort, $dir) {
    return site_url('members?sort=' . urlencode($sort) . '&dir=' . urlencode($dir) . '&page=' . (int)$p);
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
  
