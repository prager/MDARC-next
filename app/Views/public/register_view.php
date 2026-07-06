<section id="learn" class="p-5">
  <div class="container">
    <form action="<?php echo base_url() . 'index.php/send-reg'; ?>" method="post">
    <div class="row pt-5 px-5">
      <div class="col-lg p-2">
        <h5>Register to Access MDARC Membership Portal</h5>
      </div>
    </div>
    <?php if($msg != '') {?>
      <div class="row p-2">
        <div class="col-lg-8 py-3">
          <p><?php echo $msg; ?></p>
        </div>
      </div>
    <?php }?>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="fname">First Name *</label>
        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>">
      </div>
      <div class="col-lg-4 p-2">
        <label for="lname">Last Name *</label>
        <input type="text" class="form-control" id="lname" name="lname"  value="<?php echo $lname; ?>">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="email">Email *</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
      </div>
      <div class="col-lg-4 p-2">
        <label for="callsign">Callsign</label>
        <input type="text" class="form-control" id="callsign" name="callsign"  value="<?php echo $callsign; ?>">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="phone">Cell Phone *</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="street">Street Address *</label>
        <input type="text" class="form-control" id="street" name="street" value="<?php echo $street; ?>">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="city">City *</label>
        <input type="text" class="form-control" id="city" name="city" value="<?php echo $city; ?>">
      </div>
      <div class="col-lg-2 p-2">
        <label for="callsign">State</label>
        <select class="form-select" name="state_cd" aria-label="Select State">
          <?php foreach($states as $code => $state) { ?>
            <option value="<?php echo $code; ?>" <?php echo ($code == $state_cd) ? 'selected' : ''; ?>><?php echo $state; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-lg-2 p-2">
        <label for="zip">Zip *</label>
        <input type="text" class="form-control" id="zip_cd" name="zip_cd" placeholder="Enter Zipcode" value="<?php echo $zip_cd; ?>">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="facebook">Facebook URL</label>
        <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Enter Facebook URL (if you have it)">
      </div>
      <div class="col-lg-4 p-2">
        <label for="twitter">Twitter URL</label>
        <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Enter Twitter URL (if you have it)">
      </div>
    </div>
    <div class="row px-5">
      <div class="col-lg-4 p-2">
        <label for="linkedin">LinkedIn URL</label>
        <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="Enter Linked URL (if you have it)">
      </div>
    </div>
    <div class="row px-5">
      <div class='col-lg px-2 pt-3'>
        <input class="btn btn-primary" type="submit" value=" Submit Your Registration"> &nbsp; <a href="<?php echo base_url(); ?>" class="text-decoration-none"> &nbsp; Cancel</a>
      </div>
    </div>
    </div>
  </form>
</section>
