<section id="learn" class="p-5">
  <div class="container">
    <div class="row pt-5">
      <div class="col offset-lg-1">
        <h2>Edit Data for: <?php echo $mem['fname'] . ' ' . $mem['lname']; ?></h2>
        <small>(* marks required fields)</small>
          <?php if($msg != NULL) {?>
            <?php $msg; ?>
          <?php }?>
      </div>
    </div>
    <form action="<?php echo base_url() . 'index.php/update-mem/'. $mem['id_members']; ?>" method="post">
      <div class="row pt-2">
        <div class="col-lg-3 offset-lg-1 pt-1">
          <div class="form-check">
            <label class="form-check-label" for="arrl"> Listing in Directory OK </label>
            <?php if(strtoupper($mem['dir_ok']) == 'TRUE') {?>
              <input class="form-check-input" type="checkbox" name="dir_ok" checked>
            <?php }
                  else { ?>
              <input class="form-check-input" type="checkbox" name="dir_ok">
            <?php } ?>
          </div>
        </div>
        <div class="col-lg-3 offset-lg-1 pt-1">
          <div class="form-check">
            <label class="form-check-label" for="arrl"> ARRL Member </label>
            <?php if(strtoupper($mem['arrl']) == 'TRUE') {?>
              <input class="form-check-input" type="checkbox" name="arrl" checked>
            <?php }
                  else { ?>
              <input class="form-check-input" type="checkbox" name="arrl">
            <?php } ?>
          </div>
        </div>
      </div>
    <div class="row pt-2">
      <div class="col-lg-4 offset-lg-1 pt-1">
        <label for="fname">First Name *</label>
        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $mem['fname']; ?>" required>
      </div>
    <div class="col-lg-4 pt-1">
      <label for="lname">Last Name *</label>
      <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $mem['lname']; ?>" required>
    </div>
  </div>
  <div class="row pt-2">
    <div class="col-lg-4 offset-lg-1 pt-1">
      <label for="sel_lic">License Type</label>
      <select class="form-select" name="sel_lic">
        <?php
          foreach($lic as $license) {
            if($license == $mem['license']) { ?>
              <option value="<?php echo $mem['license']; ?>" selected><?php echo $mem['license']; ?></option>
        <?php    }
            else { ?>
              <option value="<?php echo $license; ?>"><?php echo $license; ?></option>
        <?php }
          }
        ?>
      </select>
    </div>
    <div class="col-lg-4 pt-1">
      <?php if($errors['callsign'] == NULL) {?>
        <label for="callsign">Callsign</label>
        <input type="text" class="form-control" id="callsign" name="callsign" value="<?php echo $mem['callsign']; ?>">
      <?php }
      else {?>
        <label for="callsign">Callsign</label>
        <input type="text" class="form-control is-invalid" id="callsign" name="callsign" aria-describedby="callsignFeedback" value="<?php echo $mem['callsign']; ?>">
        <div id="callsignFeedback" class="invalid-feedback">
          <?php echo $errors['callsign']; ?>
        </div>
      <?php }?>
    </div>
  </div>
  <div class="row pt-2">
    <div class="col-lg-4 offset-lg-1 pt-1">
      <?php if($errors['cell'] == NULL) {?>
          <label for="w_phone">Cell Phone</label>
          <input type="text" class="form-control" id="w_phone" name="w_phone" value="<?php echo $mem['w_phone']; ?>">
      <?php }
      else {?>
          <label for="w_phone">Cell Phone</label>
          <input type="text" class="form-control is-invalid" id="w_phone" name="w_phone" aria-describedby="w_phoneFeedback">
          <div id="w_phoneFeedback" class="invalid-feedback">
            Please provide a valid phone number with 10 digits
          </div>
      <?php }?>
    </div>
    <div class="col-lg-4 pt-1">
      <?php if($errors['phone'] == NULL) {?>
          <label for="h_phone">Other Phone</label>
          <input type="text" class="form-control" id="h_phone" name="h_phone" value="<?php echo $mem['h_phone']; ?>">
      <?php }
      else {?>
          <label for="h_phone">Other Phone</label>
          <input type="text" class="form-control is-invalid" id="h_phone" name="h_phone" aria-describedby="h_phoneFeedback">
          <div id="h_phoneFeedback" class="invalid-feedback">
            Please provide a valid phone number with 10 digits
          </div>
      <?php }?>
    </div>
  </div>
  <div class="row pt-2">
    <div class="col-lg-4 offset-lg-1 pt-1">
      <?php if($errors['email'] == NULL) {?>
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $mem['email']; ?>" required>
      <?php }
      else {?>
        <label for="email">Email</label>
        <input type="email" class="form-control is-invalid" id="email" name="email" aria-describedby="h_phoneFeedback" required>
        <div id="emailFeedback" class="invalid-feedback">
          The email you provided is already in the database
        </div>
      <?php }?>
    </div>
  </div>
  <div class="row pt-2">
    <div class="col-lg-4 offset-lg-1 pt-1">
      <label for="address">Street *</label>
      <input type="text" class="form-control" name="address" value="<?php echo $mem['address']; ?>" required>
    </div>
  </div>
  <div class="row pt-2">
    <div class="col-lg-4 offset-lg-1 pt-1">
      <label for="city">City *</label>
      <input type="text" class="form-control" id="city" name="city" value="<?php echo $mem['city']; ?>" required>
    </div>
    <div class="col-lg-2 pt-1">
      <label for="callsign">State *</label>
      <select class="form-select" name="state" aria-label="Default select example">
        <?php
          foreach($states as $state) {
          if(esc($mem['state']) != 'N/A') {
            if($state == esc($mem['state'])) {?>
            <option selected value="<?php echo $state; ?>"><?php echo $state; ?></option>
          <?php }
            else { ?>
            <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
          <?php
              }
          next($states);
            }
          }?>
      </select>
    </div>
    <div class="col-lg-2 pt-1">
      <label for="zip">Zip *</label>
      <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $mem['zip']; ?>" required>
    </div>
  </div>
  <?php if($fam_arr['fam_flag']) {?>
    <div class="row pt-3">
      <div class="col-lg-5 offset-lg-1">
        <table class="table">
          <thead>
            <th scope="col" colspan="2">Family Members</th>
            <th scope="col">Delete</th>
          </thead>
          <tbody>
            <?php
              foreach($fam_arr['fam_mems'] as $fam_mem) { ?>
            <tr>
              <td><?php echo $fam_mem['mem_type']; ?></td>
              <td><a href="#" class="text-decoration-none" data-bs-toggle="modal"
                  data-bs-target="#editFaMem<?php echo $fam_mem['id_members']; ?>"><?php echo $fam_mem['fname'] . ' ' . $fam_mem['lname']; ?></a></td>

        <!--    Edit Family Member modal -->
          <div class="modal fade" id="editFaMem<?php echo $fam_mem['id_members']; ?>" tabindex="-1" aria-labelledby="editFamMemLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editFamMemLabel">Edit Family Member</h5> &nbsp; <small>(* marks required fields)</small>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
              </form>
                <form action="<?php echo base_url() . 'index.php/edit-fam-mem/'. $fam_mem['id_members']; ?>" method="post">
                <div class="modal-body">
                <section class="px-2">
                  <div class="row pt-2">
                    <div class="col-lg-4 p-3">
                      <div class="form-check">
                        <label class="form-check-label" for="arrl"> ARRL Member </label>
                        <?php if(strtoupper($fam_mem['arrl']) == 'TRUE') {?>
                          <input class="form-check-input" type="checkbox" name="arrl" checked>
                        <?php }
                              else { ?>
                          <input class="form-check-input" type="checkbox" name="arrl">
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-4 p-3">
                      <div class="form-check">
                        <label class="form-check-label" for="dir_ok"> List in Directory OK </label>
                        <?php if(strtoupper($fam_mem['ok_mem_dir']) == 'TRUE') {?>
                          <input class="form-check-input" type="checkbox" name="dir_ok" checked>
                        <?php }
                              else { ?>
                          <input class="form-check-input" type="checkbox" name="dir_ok">
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg py-2">
                      <label for="fname">First Name *</label>
                      <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fam_mem['fname']; ?>">
                    </div>
                    <div class="col-lg py-2">
                        <label for="lname">Last Name *</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $fam_mem['lname']; ?>">
                    </div>
                    <div class="col-lg py-2">
                        <label for="callsign">Callsign</label>
                        <input type="text" class="form-control" id="callsign" name="callsign" value="<?php echo $fam_mem['callsign']; ?>">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 py-2">
                      <label for="sel_lic">License Type</label>
                      <select class="form-select" name="sel_lic">
                        <?php
                          foreach($lic as $license) {
                            if($license == $fam_mem['license']) { ?>
                              <option value="<?php echo $fam_mem['license']; ?>" selected><?php echo $fam_mem['license']; ?></option>
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
                          if($fam_mem['id_mem_types'] == 3) { ?>
                            <option value="3" selected>Spouse</option>
                            <option value="4">Additional</option>
                        <?php  }
                          else { ?>
                              <option value="4" selected>Additional</option>
                              <option value="3">Spouse</option>
                        <?php }  ?>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg py-2">
                      <label for="w_phone">Cell Phone</label>
                      <input type="text" class="form-control" id="w_phone" name="w_phone" value="<?php echo $fam_mem['w_phone']; ?>">
                    </div>
                    <div class="col-lg py-2">
                      <label for="h_phone">Other Phone</label>
                      <input type="text" class="form-control" id="h_phone" name="h_phone" value="<?php echo $fam_mem['h_phone']; ?>">
                    </div>
                    <div class="col-lg py-2">
                      <label for="email">Email *</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?php echo $fam_mem['email']; ?>">
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col py-2">
                        <label for="comment">Comments</label>
                        <textarea
                        class="form-control" id="comment" name="comment" rows="5"><?php echo $fam_mem['comment']; ?></textarea>
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
          <!--    End of Edit Family Member modal -->

          <td><a href="#" class="text-decoration-none" data-bs-toggle="modal"
              data-bs-target="#delFaMem<?php echo $fam_mem['id_members']; ?>"><i class="bi bi-trash"></i></a></td>

     <!-- Delete Family Member modal -->
              <div class="modal fade" id="delFaMem<?php echo $fam_mem['id_members']; ?>" tabindex="-1" aria-labelledby="delFamMemLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 id="delFamMem<?php echo $fam_mem['id_members']; ?>Label" class="modal-title">Deleting Family Member!</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Delete Family Member <strong><?php echo $fam_mem['fname'] . ' ' . $fam_mem['lname'] . ' ' . $fam_mem['callsign']; ?>?</strong></p>
                      <a href="<?php echo base_url() . 'index.php/member/rem-fam/'. $fam_mem['id_members']; ?>" class="btn btn-danger"> Delete </a>
                      <br>
                    </div>
                    <div class="modal-footer">&nbsp;
                    </div>
                  </div>
                </div>
              </div>
        <!-- End of delete Family Member modal -->
              </tr>
                <?php  }?>
            </tbody>
          </table>
          <?php include 'mod_del_famem.php'; ?>
        </div>
      </div>
    <?php } ?>
    <div class="row pt-3 pb-5">
      <div class="col-lg-4 offset-lg-1">
        <p><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addFamMem">Add Family Member</a></p>
        <?php include 'modal_update_mem.php'; ?>
        <button type="submit" class="btn btn-primary">Save changes</button> &nbsp;
        <a href="<?php echo base_url(); ?>" class="btn btn-secondary text-decoration-none">Cancel</a>
      </div>
    </div>
    </form>
  </div>
</section>
