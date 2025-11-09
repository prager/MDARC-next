<div class="modal fade" id="addFamMem" tabindex="-1" aria-labelledby="addFamMemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFamMemLabel">Add Family Member</h5> &nbsp;
        <small>(* marks required fields)</small>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    </form>
      <form action="<?php echo base_url() . '/index.php/add-fam-mem/'. $mem['id_members']; ?>" method="post">
      <div class="modal-body">
      <section class="px-2">
        <div class="row pt-2">
          <div class="col-lg-4 p-3">
            <div class="form-check">
              <label class="form-check-label" for="arrl"> ARRL Member </label>
              <input class="form-check-input" type="checkbox" name="arrl" />
            </div>
          </div>
          <div class="col-lg-4 p-3">
            <div class="form-check">
              <label class="form-check-label" for="dir_ok"> List in Directory OK </label>
              <input class="form-check-input" type="checkbox" name="dir_ok" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg py-2">
            <label for="fname">First Name *</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" required>
          </div>
          <div class="col-lg py-2">
              <label for="lname">Last Name *</label>
              <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" required>
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
                  if($license == 'SWL') { ?>
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
                foreach($member_types as $type) {
                  if($type['id_mem_types'] == 3) {?>
                    <option value="<?php echo $type['id_mem_types']; ?>" selected><?php echo $type['description']; ?></option>
                  <?php }
                  elseif($type['id_mem_types'] == 4) { ?>
                    <option value="<?php echo $type['id_mem_types']; ?>"><?php echo $type['description']; ?></option>
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
            <label for="email">Email *</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" required>
          </div>
        </div>
        <div class="row mb-1">
          <div class="col py-2">
              <label for="comment">Comments</label>
              <textarea
              class="form-control" id="comment" name="comment" rows="5" placeholder="Any Comment"></textarea>
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
