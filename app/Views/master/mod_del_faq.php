<div class="modal fade" id="delFaq<?php echo $faq['id']; ?>" tabindex="-1" aria-labelledby="delMemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="delMem<?php echo $faq['id']; ?>Label" class="modal-title">Delete FAQ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Delete FAQ: <strong><?php echo substr($faq['theq'], 0, 35) . '...'; ?>?</strong></p>
        <a href="<?php echo base_url() . 'index.php/delete-faq/'. $faq['id']; ?>" class="btn btn-danger"> Delete! </a>
        <br>
      </div>
      <div class="modal-footer">&nbsp;
      </div>
    </div>
  </div>
</div>