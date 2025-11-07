<div class="modal fade" id="reset" tabindex="-1" aria-labelledby="resetLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reset Your Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <form action="<?php echo base_url() . '/index.php/load-pass' ?>" method="post">
        <div class="modal-body">
          <p class="lead">Enter your Email</p>
              <div class="mb-3">
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email"/>
              </div>
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary"> Submit </button>
      </form>
        </div>
    </div>
    </div>
</div>
