<div class="modal fade" id="delFamMem<?php echo $mem['id_members']; ?>" tabindex="-1" aria-labelledby="delFamMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="delFamMem<?php echo $mem['id_members']; ?>Label" class="modal-title">Member De-Activation!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Deactivate Member <strong><?php echo $mem['fname'] . ' ' . $mem['lname'] . ' ' . $mem['callsign']; ?>?</strong></p>
        <a href="<?php echo base_url() . 'index.php/member/rem-fam/'. $mem['id_members']; ?>" class="btn btn-danger"> Deactivate </a>
        <br>
      </div>
      <div class="modal-footer">&nbsp;
      </div>
    </div>
  </div>
</div>