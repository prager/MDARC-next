<div class="modal fade" id="showMem<?php echo $mem['id_members']; ?>" tabindex="-1" aria-labelledby="showMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showMemLabel"><?php echo $mem['fname'] . ' ' . $mem['lname'] . ' ' . $mem['callsign'] . ' / ID: ' . $mem['id_members']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url() . '/index.php/edit-mem/'. $mem['id_members']; ?>" method="post">
      <div class="modal-body">
      <section class="px-2">
        <?php if($mem['id_mem_types'] == 3 || $mem['id_mem_types'] == 4) { ?>
        <div class="row">
          <div class="col-lg">
            <p>Family member of: <a href="<?php echo base_url() . '/index.php/show-mem/' . $mem['id_parent'];?>" class="text-decoration-none"><?php echo $mem['parent_fname'] . ' ' . $mem['parent_lname']; ?></a> / Member Id: <?php echo $mem['id_parent']; ?></p>
          </div>
        </div>
        <?php } ?>
        <div class="row">
          <div class="col-lg-6 py-1">
            <?php echo 'License: ' . $mem['license']; ?>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-1">
            <label for="w_phone">Cell Phone: </label><?php echo ' ' . $mem['w_phone']; ?>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-1">
            <label for="h_phone">Home Phone: </label><?php echo ' ' . $mem['h_phone']; ?>
          </div>
        </div>
        <div class="row">
            <div class="col-lg py-1">
              <label for="email">Email: </label><?php echo ' ' . $mem['email']; ?>
            </div>
        </div>
        <div class="row">
          <div class="col-lg py-1">
            <label for="mem_since">Member Since: </label><?php echo ' ' . $mem['mem_since']; ?>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-1">
            <label for="cur_year">Current Year: </label><?php echo ' ' . $mem['cur_year']; ?>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 py-2">
            <label for="address">Address: </label><br>
            <?php echo $mem['address'] . '<br>' . $mem['city'] . ', ' . $mem['state'] . ' ' . $mem['zip']; ?>
          </div>
        </div>
      </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
