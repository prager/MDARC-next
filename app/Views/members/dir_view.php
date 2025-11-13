<div class="container mt-5" >

  <div class="d-flex justify-content-between align-items-center mb-3 pt-5">
    <h1 class="h4 mb-0">Member Directory</h1>
    <!-- <div>
      <a class="btn btn-sm btn-outline-primary <?= $orderBy ? 'active' : '' ?>"
         href="<?= current_url().'?byLastName=1' ?>">Sort by Last Name</a>
      <a class="btn btn-sm btn-outline-primary <?= !$orderBy ? 'active' : '' ?>"
         href="<?= current_url().'?byLastName=0' ?>">Sort by Callsign</a>
    </div> -->
  </div>

  <div class="list-group shadow-sm mb-5">
    <?php if (empty($rows)): ?>
      <div class="list-group-item py-4 text-center text-muted">No members found.</div>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <?php
          $indentClass = 'indent-' . (int)($r['indent_level'] ?? 0);
          $isFamily    = ($r['row_type'] ?? '') === 'family';
          // Build a display address safely
          $parts = array_filter([
            $r['address'] ?? '',
            trim(($r['city'] ?? '') . (empty($r['state']) ? '' : ', ' . $r['state'])),
            $r['zip'] ?? ''
          ]);
          $displayAddress = implode(' • ', $parts);
          // Contact chips (masked values arrive as '' per proc)
          $contacts = array_filter([
            !empty($r['email'])   ? 'Email: '  . esc($r['email'])   : null,
            !empty($r['w_phone']) ? 'Cell: '   . esc($r['w_phone']) : null,
            !empty($r['h_phone']) ? 'Other: '   . esc($r['h_phone']) : null,
          ]);
        ?>
        <div class="list-group-item dir-row <?= $isFamily ? 'family' : '' ?>">

          <div class="<?= $indentClass ?>">
            <div class="d-flex align-items-center justify-content-between">
              <div class="dir-name">
                <?= esc($r['display_name'] ?? ( ($r['lname'] ?? '').', '.($r['fname'] ?? '').' ('.($r['callsign'] ?? '').')' )) ?>
                <?php if ($isFamily): ?>
                  <span class="badge badge-family ms-2">Family</span>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($displayAddress): ?>
              <div class="muted small mt-1">
                <?= esc($displayAddress) ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($contacts)): ?>
              <div class="small mt-2">
                <?php foreach ($contacts as $i => $c): ?>
                  <span class="badge text-bg-light contact-item"><?= $c ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>
