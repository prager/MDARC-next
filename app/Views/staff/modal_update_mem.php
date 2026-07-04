<?php
if (! function_exists('staff_format_date')) {
  function staff_format_date($value, string $format = 'm/d/Y'): string
  {
    if ($value === null || $value === '' || $value === 0 || $value === '0') {
      return '';
    }

    if (is_int($value) || ctype_digit((string) $value)) {
      return date($format, (int) $value);
    }

    $timestamp = strtotime((string) $value);

    return $timestamp === false ? '' : date($format, $timestamp);
  }
}
?>
<div class="modal fade" id="editMem<?= esc($m['id_members']) ?>" tabindex="-1" aria-labelledby="editMemLabel<?= esc($m['id_members']) ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMemLabel<?= esc($m['id_members']) ?>"><?= esc($m['fname'] ?? '') . ' ' .  esc($m['lname'] ?? '') . ' ' .  esc($m['callsign'] ?? ''). ' / ID: ' .  esc($m['id_members'] ?? '') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <section class="px-2">
        <div class="row">
          <div class="col-md-6 py-2">
            <label>Name</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['lname'] ?? '') ?>, <?= esc($m['fname'] ?? '') ?></p>
          </div>
          <div class="col-md-3 py-2">
            <label>Callsign</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['callsign'] ?? '') ?></p>
          </div>
          <div class="col-md-3 py-2">
            <label>Member ID</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['id_members'] ?? '') ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 py-2">
            <label>License Type</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['license'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Member Type</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['description'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Current Year</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['cur_year'] ?? '') ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 py-2">
            <label>Email</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['email'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Cell Phone</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['w_phone'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Home Phone</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['h_phone'] ?? '') ?></p>
          </div>
        </div>
        <div class="row py-2">
          <div class="col-lg">
            <label class="form-check-label">ARRL Mem</label>
            <p class="form-control-plaintext mb-0"><?= strtoupper(esc($m['arrl_mem'] ?? '')) === 'TRUE' ? 'Yes' : 'No' ?></p>
          </div>
          <div class="col-lg">
            <label class="form-check-label">Carrier Copy</label>
            <p class="form-control-plaintext mb-0"><?= strtoupper(esc($m['hard_news'] ?? '')) === 'TRUE' ? 'Yes' : 'No' ?></p>
          </div>
          <div class="col-lg">
            <label class="form-check-label">Dir Copy</label>
            <p class="form-control-plaintext mb-0"><?= strtoupper(esc($m['hard_dir'] ?? '')) === 'TRUE' ? 'Yes' : 'No' ?></p>
          </div>
          <div class="col-lg">
            <label class="form-check-label">Mem Card</label>
            <p class="form-control-plaintext mb-0"><?= strtoupper(esc($m['mem_card'] ?? '')) === 'TRUE' ? 'Yes' : 'No' ?></p>
          </div>
          <div class="col-lg">
            <label class="form-check-label">List OK</label>
            <p class="form-control-plaintext mb-0"><?= strtoupper(esc($m['ok_mem_dir'] ?? '')) === 'TRUE' ? 'Yes' : 'No' ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 py-2">
            <label>Member Since</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['mem_since'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Payment Date</label>
            <p class="form-control-plaintext mb-0"><?= esc(staff_format_date($m['paym_date'] ?? null)); ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Street</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['address'] ?? '') ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 py-2">
            <label>City</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['city'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>State</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['state'] ?? '') ?></p>
          </div>
          <div class="col-md-4 py-2">
            <label>Zip</label>
            <p class="form-control-plaintext mb-0"><?= esc($m['zip'] ?? '') ?></p>
          </div>
        </div>
        <div class="row mb-1">
          <div class="col py-2">
              <label for="comment">Comments</label>
              <p class="form-control-plaintext mb-0"><?= trim(esc($m['comment'] ?? '')) ?></p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col py-1">
            <?php if($m['silent_date'] == 0) { ?>
              <button type="button" class="btn btn-light btn-sm"><?php echo anchor('set-silent-key/' . $m['id_members'], 'Set Silent Key', 'class="text-decoration-none text-dark"')?></button>
          <?php } else {?>
            <p style="color: red">Silent Key on: <?= esc(staff_format_date($m['silent_date'] ?? null)); ?></p>
          <?php } ?>
          </div>
        </div>
      </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
