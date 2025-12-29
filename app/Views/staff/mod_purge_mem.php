<div class="modal fade" id="purgeMem<?= esc($m['id_members']) ?>" tabindex="-1" aria-labelledby="purgeMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="purgeMem<?= esc($m['id_members']) ?>Label" class="modal-title">Permanent Purging Member out of DB!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Purge this Member Out of DB: <strong><?php echo esc($m['fname']) . ' ' . esc($m['lname']) . ' ' . esc($m['callsign']); ?> (?)</strong></p>
        <a href="<?php echo base_url() . 'index.php/purge-mem/'. esc($m['id_members']); ?>" class="btn btn-danger"> Purge Member! </a>
        <br>
      </div>
      <div class="modal-footer">&nbsp;
      </div>
    </div>
  </div>
</div>