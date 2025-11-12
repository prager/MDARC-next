<section id="learn" class="p-5">
  <div class="container">
    <div class="row pt-5 px-5">
      <div class="col-lg p-2">
        <h5>Your Account Settings</h5>
      </div>
    </div>
    <?php if($msg != '') {?>
      <div class="row px-5">
        <div class="col-lg-8">
          <?php echo $msg; ?>
        </div>
      </div>
    <?php }?>
    <form action="<?php echo base_url() . 'index.php/do-update'; ?>" method="post">
      <div class="row px-5">
        <div class="col-lg-4">
          <label for="lname">Your Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter Your Username" title="Enter your username" value="<?php echo $user['username']; ?>" required>
        </div>
      </div>
      <div class="row px-5 pt-3">
        <div class="col-lg-4">
          <label for="lname">Your Password</label>
          <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password" title="Passwords must contain at least: 12 characters, 2 uppercase, 2 lowercase, 2 numbers and 2 special characters such as ()!@#$%^&*()\-_+." required>
        </div>
      </div>
      <div class="row px-5 pt-3">
        <div class="col-lg-4">
          <label for="lname">Re-Enter Password</label>
          <input type="password" class="form-control" id="pass2" name="pass2" placeholder="Re-Enter Password" title="Passwords must contain at least: 12 characters, 2 uppercase, 2 lowercase, 2 numbers and 2 special characters such as ()!@#$%^&*()\-_+." required>
        </div>
      </div>
    <div class="row px-5">
      <div class='col-lg px-3 pt-4'>
        <input class="btn btn-primary" type="submit" value=" Update Your Account ">
        <a href="<?php echo base_url(); ?>" class="btn btn-secondary ms-2 ps-3">  Cancel &nbsp;<i class="bi bi-x-octagon"></i></a>
      </div>
    </div>
    </form>
    </div>
</section>