<div class="modal fade" id="editSilent<?php echo $s['id_members']; ?>" tabindex="-1" aria-labelledby="editSilentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSilentLabel">Edit Silent Key</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url() . 'index.php/load-silent/' . $s['id_members']; ?>" method="post">
      <div class="modal-body">
        <div class="row align-items-center justify-content-between px-3">
          <div class="col">
            <p>Set date of Passing for Silent Key <strong>
              <?php echo $s['fname'] . ' ' . $s['lname'] . ' ' . $s['callsign']; ?>?</strong></p>
          </div>
        </div>
          <div class="row align-items-center justify-content-between px-3">
            <div class="col">
              <?php
              $s['silent_date'] == "No Date" ? $silent = date('Y-m-d', time()) : $silent = $s['silent_date'];
              ?>
              <label for="fname">Silent Key Date</label>
              <input type="date" class="form-control" id="silent_date" name="silent_date" value="<?php echo $silent; ?>">
            </div>
          </div>
          <div class="row align-items-center justify-content-between p-3">
            <div class="col">
              <button type="button" class="btn btn-light btn-sm"><?php echo anchor('unset-silent-key/' . $s['id_members'], 'Un-Set Silent Key', 'class="text-decoration-none text-dark"')?></button>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>

    </div>
  </div>
</div>
