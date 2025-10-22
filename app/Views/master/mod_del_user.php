<div class="modal fade" id="delUsr<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form action="<?php echo base_url() . '/index.php/reset-user/'. $user['id']; ?>" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete User ?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row py-3">
          <div class="col">
            Delete user <strong><?php echo $user['fname'] . ' ' . $user['lname'] . ' Id: ' . $user['id']; ?>?</strong>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="<?php echo base_url() . 'index.php/delete-user/'. $user['id']; ?>" class="btn btn-danger"><i class="bi bi-trash"></i> Delete User </a>
      </div>
    </div>
  </form>
  </div>
</div>
