<div class="modal fade" id="resetUsr<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="resetUsr" aria-hidden="true">
  <div class="modal-dialog">
  <form action="<?php echo base_url() . 'index.php/reset-user/'. $user['id']; ?>" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resetUsr">Reset Username & Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p-3">
          <div class="col">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>">
          </div>
        </div>
        <div class="row p-3">
          <div class="col">
            <label for="pass">Password</label>
            <input type="password" class="form-control" id="pass" name="pass">
          </div>
        </div>
        <div class="row p-3">
          <div class="col">
            <label for="pass2">Password</label>
            <input type="password" class="form-control" id="pass2" name="pass2">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </form>
  </div>
</div>
