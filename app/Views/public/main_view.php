<section class="bg-light text-dark text-center text-sm-start">
  <!-- Updated 1 -->
    <div class="container">
      <?php if(strlen($msg) > 0) {?>
      <div class="row">
        <div class="col">
          <?php echo $msg; ?>
        </div>
      </div>
    <?php }?>
    <div class="d-sm-flex align-items-center justify-content-between pb-1">
      <div class="col-lg-6">
        <!-- <h1>MDARC <span class="text-warning">Memberships</span> - <a href="<?php echo base_url() . 'index.php/add-mem/new-member'; ?>" class="text-decoration-none">Join Us</a>  </h1> -->
        <h1>MDARC <span class="text-warning">Memberships</span> - <a href="https://www.mdarc.org/about-us/joinrenewupdate" class="text-decoration-none" target="_blank">Join Us</a>  </h1>
        <?php if(ENVIRONMENT === 'development') { ?>
        <p class="mt-xs-5"><small><span class="fw-bold text-danger">This is a Membership Portal for MDARC members - dev version!</span> For more info click <a href="<?php echo base_url(); ?>index.php/faqs" class="text-decoration-none">here</a></small></p>
        <?php } ?>
          <form action="<?php echo base_url() . 'index.php/login' ?>" method="post">
            <div class="col-lg-10 mb-3">
                <label for="user" class="form-label">Username</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Enter Username"/>
            </div>
            <div class="col-lg-10 mb-4">
                <label for="pass" class="form-label"> Password</label>
                <input type="password" class="form-control" id="pass"  name="pass" placeholder="Enter Password"/>
            </div>
            <div class="col-lg-10 mb-3">
              <button type="submit" class="btn btn-primary"> Member Login </button>
              <p><small>Lost username and/or password? Click <a href="#" data-bs-toggle="modal" class="text-decoration-none" data-bs-target="#resetModal">here</a></small></p>
            </div>
          </form>
      </div>
      <div class="col">
        <img class="img-fluid d-none d-lg-block" src="/img/mdarc-logo.png" alt="">
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= site_url('request-password-reset') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="resetModalLabel">Recover Username or Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Enter the email address associated with your MDARC Member Portal account.</p>
          <label class="form-label" for="resetEmail">Email</label>
          <input class="form-control" id="resetEmail" name="email" type="email" autocomplete="email" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Recovery Email</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--Signup for user access-->
<section class="bg-primary text-light p-5">
    <div class="container">
      <form action="<?php echo base_url() . 'index.php/register' ?>" method="post">
        <div class="d-md-flex justify-content-between align-items-center">
            <h3 class="mb-3 ms-3 mb-md-0">Sign up for user access</h3>
            <div class="col-lg-6 offset-col-lg-3">
              <div class="input-group news-input">
                  <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your MDARC Email">
                  <button class="btn btn-dark" type="submit" id="button-addon2">Submit</button>
              </div>
            </div>
        </div>
      </form>
    </div>
</section>
<section>
    <div class="container">
        <div class="row align-items-center justify-content-between py-3">
          <div class="col-lg-6">
            <h2>Mount Diablo Amateur Radio Club</h2>
                <p class="lead">
                    <a href="http://mdarc.org" class="text-decoration-none" target="_blank">The Mount Diablo Amateur Radio Club (MDARC)</a> was founded on January 9, 1947.
                </p>
                <p>
                  MDARC provides a wide variety of programs and activities for its members. These include hosting and staffing PACIFICON (ARRL Pacific Division convention) each October, bringing in great speakers and offering ham radio classes to the public.
                </p>
          </div>
          <div class="col-lg-6">
          <gmp-map
            center="37.943574,-122.076102"
            zoom="10"
            map-id="DEMO_MAP_ID"
            style="height: 300px">

            <gmp-advanced-marker
              position="37.943574,-122.076102"
              title="Pleasant Hill, CA"
            ></gmp-advanced-marker>
          </gmp-map>
          <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_SZN9vBFgQg_-adkGBNvhZ4zbUMc3J7o&libraries=maps,marker&v=beta" defer></script>
          </div>
    </div>
</section>
