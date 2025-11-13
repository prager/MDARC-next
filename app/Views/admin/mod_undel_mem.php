<div class="modal fade" id="unDelMem<?= esc($m['id_members']) ?>" tabindex="-1" aria-labelledby="unDelMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="unDelMem<?= esc($m['id_members']) ?>Label" class="modal-title">Member Activation!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Activate Member <strong><?php echo  esc($m['fname']) . ' ' .  esc($m['lname']) . ' ' .  esc($m['callsign']); ?>?</strong></p>
        <a href="<?php echo base_url() . 'index.php/un-delete-mem/'. esc($m['id_members']); ?>" class="btn btn-primary"> Activate </a>
        <br>
      </div>
      <div class="modal-footer">&nbsp;
      </div>
    </div>
  </div>
</div>