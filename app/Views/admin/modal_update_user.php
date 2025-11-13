<div class="modal fade" id="editUser<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form action="<?php echo base_url() . 'index.php/load-admin/'. $user['id']; ?>" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $user['fname']; ?>">
          </div>
          <div class="col">
            <label for="lname">Last Name Name</label>
            <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $user['lname']; ?>">
          </div>
          <div class="col">
            <label for="lname">Callsign</label>
            <input type="text" class="form-control" id="callsign" name="callsign" value="<?php echo $user['callsign']; ?>">
          </div>
        </div>
        <div class="row pt-3">
          <div class="col">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
          </div>
          <div class="col">
            <label for="phone">Cell Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
          </div>
        </div>
        <div class="row pt-3">
          <div class="col">
            <label for="facebook">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo $user['facebook']; ?>">
          </div>
          <div class="col">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo $user['twitter']; ?>">
          </div>
          <div class="col">
            <label for="linkedin">Linkedin</label>
            <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo $user['linkedin']; ?>">
          </div>
        </div>
        <div class="row pt-3">
          <div class="col-lg-6">
            <label for="street">Address</label>
            <input type="text" class="form-control" id="street" name="street" value="<?php echo $user['street']; ?>">
          </div>
        </div>
        <div class="row py-3">
          <div class="col">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="<?php echo $user['city']; ?>">
          </div>
          <div class="col">
            <label for="callsign">State</label>
            <select class="form-select" name="state" aria-label="Select State">
              <?php
                foreach ($states as $key => $state) {
                  if($user['state'] != NULL) {
                    if($state == $states[trim($user['state'])]) {?>
                    <option selected value="<?php echo key($states); ?>"><?php echo $state; ?></option>
                  <?php }
                    else { ?>
                    <option value="<?php echo key($states); ?>"><?php echo $state; ?></option>
                  <?php
                      }
                    }
                    else{ ?>
                      <option value="<?php echo key($states); ?>"><?php echo $state; ?></option>
                <?php    }
                  }?>
            </select>
          </div>
          <div class="col">
            <label for="zip">Zip Code</label>
            <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $user['zip']; ?>">
          </div>
        </div>
        <div class="row pb-5">
          <div class="col-lg-6">
            <label for="callsign">User Role</label>
            <select class="form-select" name="usr_type" aria-label="Select User Type">
              <option selected value="<?php echo $user['id_user_type']; ?>"><?php echo $usr_types[$user['id_user_type']]; ?></option>
              <?php
                //unset($usr_types[$user['id_user_type']]);
                foreach ($usr_types as $key => $type) { ?>
                  <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
                <?php }?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit Update</button>
      </div>
    </div>
  </form>
  </div>
</div>
