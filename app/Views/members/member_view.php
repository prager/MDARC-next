<section id="learn" class="p-5">
    <div class="container">
      <div class="row pt-5">
        <div class="col offset-lg-3">
          <h3 class="fw-bold"><span class="text-warning">MDARC Member: </span><?php echo $primary['fname'] . ' ' . $primary['lname']; ?></h3>
        </div>
      </div>
      <div class="row pt-2 pb-5 mb-5">
        <div class="col-lg-3 offset-lg-1 bg-light pt-3 mb-3">
          <table class="table table-borderless table-hover border">
            <tr class="border-bottom">
              <td><a href="<?php echo base_url() . 'index.php/pers-data'; ?>" class="text-decoration-none text-body nav-link"> <i class="bi bi-person"></i> Edit Your Data </a></td>
            </tr>
            <tr class="border-bottom">
              <td><a href="<?php echo base_url() . 'index.php/show-update'; ?>" class="text-decoration-none text-body nav-link"> <i class="bi bi-gear"></i> Settings</a></td>
            </tr>
            <tr class="border-bottom">
              <!-- <td><a href="#" data-bs-toggle="modal" data-bs-target="#goPay" class="text-decoration-none text-body nav-link"><i class="bi bi-clipboard-check"></i> Renew Membership</a></td> -->
              <?php if(ENVIRONMENT === 'development') { ?>
                <td><a href="https://pay-test.jlkconsulting.info/" class="text-decoration-none text-body nav-link"><i class="bi bi-clipboard-check"></i> Renew Membership</a></td>
                <?php } else { ?>
                <td><a href="https://pay-v1b.jlkconsulting.info/index.php/mdarc" class="text-decoration-none text-body nav-link"><i class="bi bi-clipboard-check"></i> Renew Membership</a></td>
              <?php } ?>
            </tr>
            <tr class="border-bottom">
              <!-- <td><a href="#" data-bs-toggle="modal" data-bs-target="#goPay" class="text-decoration-none text-body nav-link"><i class="bi bi-clipboard-check"></i> Renew Membership</a></td> -->
              <td><a href="<?php echo base_url() . 'index.php/mem_print-dir/1'; ?>" class="text-decoration-none text-body nav-link"><i class="bi bi-book"></i> Directory by Last Name</a></td>
            </tr>
            <tr class="border-bottom">
              <!-- <td><a href="#" data-bs-toggle="modal" data-bs-target="#goPay" class="text-decoration-none text-body nav-link"><i class="bi bi-clipboard-check"></i> Renew Membership</a></td> -->
              <td><a href="<?php echo base_url() . 'index.php/mem_print-dir/0'; ?>" class="text-decoration-none text-body nav-link"><i class="bi bi-book"></i> Directory by Callsign</a></td>
            </tr>

            <!-- Renewal modal -->
            <div class="modal fade" id="goPay" tabindex="-1" aria-labelledby="goPayLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form action="<?php echo base_url() . 'index.php/go-pay/' . $primary['id_members']; ?>" method="post">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="goPayLabel">Renew Membership for <?php echo $primary['fname'] . ' ' . $primary['lname'] . ' ' . $primary['callsign']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col">
                      <h4 class="mb-2">Fees to be charged</h4>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                      <input type="checkbox" id="membershipbox" name="membershipbox" value="" checked disabled>
                        <label for="membershipbox"> Membership Fee: $45.00</label>
                        <input type="hidden" name="mdarc-mem" value="renewal">
                      </div>
                      
                    </div>
                    <div class="row mt-3">
                      <div class="col">
                        <input type="checkbox" id="carrier" name="carrier" value="add_carrier">
                        <label for="carrier"> Hardcopy of The Carrier $18.00</label>
                      </div>
                    </div>
                    <div class="row m-3">
                      <div class="col">
                        <hr>
                      </div>
                    </div>
                    <div class="row mt-3 mb-1">
                      <div class="col">
                        <h4>Additional donations</h4>
                      </div>
                    </div>
                    <div class="row mb-5">
                      <!-- <div class="col-lg-6">
                        <label for="repeater">Repeater</label>
                        <input type="text" class="form-control" name="repeater" value="$0.00">
                      </div> -->
                      <div class="col-lg-8">
                        <label for="mdarc_donation">Donate to MDARC</label>
                        <input type="text" class="form-control" name="mdarc_donation" value="$0.00">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Process Payment</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
            <!-- End of Renewal modal -->

            <!-- <tr class="border-bottom">
              <td><a href="#" data-bs-toggle="modal" data-bs-target="#goDonate" class="text-decoration-none text-body nav-link"><i class="bi bi-cash"></i> Donate</a></td>
            </tr> -->
            <!-- Donate Modal -->
            <div class="modal fade" id="goDonate" tabindex="-1" aria-labelledby="goDonateLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form action="<?php echo base_url() . 'index.php/go-pay/' . $primary['id_members']; ?>" method="post">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="goDonateLabel">Donate to MDARC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <div class="row mt-3 mb-3">
                      <div class="col">
                        <h4>Donation for</h4>
                        <p>Minimum total donation $5.00</p>
                      </div>
                    </div>
                    <div class="row mb-5">
                      <!-- <div class="col-lg-6">
                        <label for="repeater">Repeater</label>
                        <input type="text" class="form-control" name="repeater" value="$5.00">
                      </div> -->
                      <div class="col-lg-6">
                        <label for="mdarc_donation">MDARC</label>
                        <input type="text" class="form-control" name="mdarc_donation" value="$5.00">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Process Donation</button>
                  </div>
                </div>
                </form>
              </div>
            </div>
            <!-- End of Donate Modal -->
          </table>

            <?php if($fam_arr['fam_flag']) {?>
            <table class="table table-borderless border pt-5">
                  <tr><td><i class="bi bi-people"></i> Family Members:</td></tr>
                  <?php
                      foreach($fam_arr['fam_mems'] as $mem) {
                        echo '<tr><td>' . $mem['mem_type'] . ' - ' . $mem['fname'] . ' ' . $mem['lname'] . '</td></tr>';
                    } ?>
            </table>
            <?php  } ?>
            <table class="table table-borderless table-hover border">
              <tr class="border-bottom">
                <td><a href="<?php echo base_url() . 'index.php/logout'; ?>" class="text-decoration-none text-body nav-link"><i class="bi bi-box-arrow-up-right"></i> Logout</a></td>
              </tr>
            </table>
        </div>
        <div class="col-lg-6 bg-light p-3 mb-3">
            <?php if($msg != NULL) {?>
            <?php echo $msg; ?>
          <?php }?>
            <p class="lead">Callsign: <?php echo $primary['callsign'] . ' / ' . 'Member ID: ' . $primary['id_members']; ?></p>
            <p class="lead">Membership Type: <?php echo $primary['mem_type']; ?></p>
            <?php if($primary['cur_year'] < date('Y', time())) {?>
              <p class="lead text-danger fw-bold">Current Year: <?php echo $primary['cur_year']; ?> --> Not Current!</p>
            <?php }
            else { ?>
              <p class="lead">Current Year: <?php echo $primary['cur_year']; ?></p>
            <?php }?>
            <?php if($primary['cur_year'] < date('Y', time())) {?>
              <p class="lead text-danger fw-bold">Last Payment: <?php echo $primary['pay_date']; ?> --> Payment Overdue!</p>
              <p>Click on <a href="https://pay-v1b.jlkconsulting.info/index.php/mdarc" class="text-decoration-none">Membership Renewal</a> link to renew your membership.</p>
            <?php }
            else { ?>
              <p class="lead">Last Payment: <?php echo $primary['pay_date']; ?></p>
            <?php }?>
            <p class="lead">ARRL Member:
              <?php if(strtoupper($primary['arrl']) == 'TRUE') {?>
                <label class="lead">YES &nbsp; </label>
                <input class="form-check-input" type="checkbox" name="arrl" checked disabled>
              <?php }
                    else { ?>
                <label class="lead">NO &nbsp; </label>
                <input class="form-check-input" type="checkbox" name="arrl" disabled>
              <?php } ?>
            </p>
            <p class="lead">Directory Listing:
              <?php if(strtoupper($primary['dir_ok']) == 'TRUE') {?>
                <label class="lead">YES &nbsp; </label>
                <input class="form-check-input" type="checkbox" name="arrl" checked disabled>
              <?php }
                    else { ?>
                <label class="lead">NO &nbsp; </label>
                <input class="form-check-input" type="checkbox" name="arrl" disabled>
              <?php } ?>
            </p>
            <p class="lead">Email: <?php echo $primary['email']; ?></p>
            <p class="lead">Cell Phone: <?php echo $primary['w_phone'] . ' / Other Phone: ' . $primary['h_phone']; ?></p>
            <p class="lead">Address:<br> <?php echo $primary['address'] . ', ' . $primary['city'] . ', ' . $primary['state'] . ' ' . $primary['zip']; ?></p>
        </div>
      </div>
  </div>
</section>
