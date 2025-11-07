<!-- Modal -->
<div class="modal fade" id="renewMem" tabindex="-1" aria-labelledby="renewMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renewMemLabel">Renew your MDARC Membership</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url() . '/index.php/renew' ?>" method="post">
        <div class="modal-body">
            <p class="lead">Enter your MDARC Email</p>
                <div class="mb-3">
                    <label for="email" class="col-form-label">Enter your Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email"/>
                </div>
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary"> Submit </button>
        </form>
        </div>
    </form>
    </div>
  </div>
</div>
