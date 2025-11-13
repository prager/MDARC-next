<div class="modal fade" id="addFMems<?php echo $mem['id']; ?>" tabindex="-1" aria-labelledby="addFamMemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFamMemLabel">Add a Family Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url() . '/index.php/add-fam/'. $mem['id']; ?>" method="post">
      <div class="modal-body">
      <section class="px-2">
        <div class="row">
          <div class="col-lg-3 p-3">
            <div class="form-check pt-3">
              <label class="form-check-label" for="arrl"> ARRL Member</label>
              <input class="form-check-input" type="checkbox" name="arrl" />
            </div>
          </div>
          <div class="col-lg-4 p-3">
            <div class="form-check pt-3">
              <label class="form-check-label" for="arrl"> List in Directory OK </label>
              <input class="form-check-input" type="checkbox" name="ok_mem_dir" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-2">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name">
          </div>
          <div class="col-lg py-2">
              <label for="lname">Last Name</label>
              <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name">
          </div>
          <div class="col-lg py-2">
              <label for="callsign">Callsign</label>
              <input type="text" class="form-control" id="callsign" name="callsign" placeholder="Enter Callsign">
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 py-2">
            <label for="sel_lic">License Type</label>
            <select class="form-select" name="sel_lic">
              <?php
                foreach($lic as $license) {
                  if($license == 'Technician') { ?>
                    <option value="<?php echo $license; ?>" selected><?php echo $license; ?></option>
              <?php    }
                  else { ?>
                    <option value="<?php echo $license; ?>"><?php echo $license; ?></option>
              <?php }
                }
              ?>
            </select>
          </div>
          <div class="col-lg-6 py-2">
            <label for="sel_lic">Member Type</label>
            <select class="form-select" name="mem_types">
              <?php
                foreach($mem_types as $key => $type) {
                  if($key == 4) {?>
                    <option value="<?php echo $key; ?>" selected><?php echo $type; ?></option>
                  <?php }
                  elseif($key == 3) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
              <?php  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-2">
            <label for="w_phone">Cell Phone</label>
            <input type="text" class="form-control" id="w_phone" name="w_phone" placeholder="000-000-0000">
          </div>
          <div class="col-lg py-2">
            <label for="h_phone">Home Phone</label>
            <input type="text" class="form-control" id="h_phone" name="h_phone" placeholder="000-000-0000">
          </div>
          <div class="col-lg py-2">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com">
          </div>
        </div>
        <div class="row mb-1">
          <div class="col py-2">
              <label for="comment">Comments</label>
              <textarea
              class="form-control" id="comment" name="comment" rows="7" placeholder="Any Comment"></textarea>
          </div>
        </div>
      </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
