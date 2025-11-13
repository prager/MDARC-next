<div class="modal fade" id="manPayment<?= esc($m['id_members']) ?>" tabindex="-1" aria-labelledby="manPaymentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="<?php echo base_url() . 'index.php/man-payment/'. esc($m['id_members']); ?>" method="post">
      <div class="modal-header">
        <h5 id="manPayment<?= esc($m['id_members']) ?>Label" class="modal-title">Process Manual Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?php
        $timestamp = time();
        $dateTimeObj = new DateTime();
        $dateTimeObj->setTimestamp($timestamp);
        $dateTime = strval($dateTimeObj->format('Y-m-d'));
      ?>
      <div class="modal-body">
        <div class="row my-3">
            <div class="col">
                <label for="pay_date">Payment Date</label>
                <input type="date" class="form-control" id="pay_date" name="pay_date" value="<?php echo $dateTime; ?>">
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
            <label for="amount">Membership Amount</label>
            $<input type="text" class="form-control" id="amount" name="amount" value="<?php echo number_format($mem_cost, 2, '.', ''); ?>">
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
            <label for="amount">Donation - MDARC (must be more than $5.00)</label>
            $<input type="text" class="form-control" id="donation" name="donation" value="0.00">
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
            <label for="amount">Donation - Repeater (must be more than $5.00)</label>
            $<input type="text" class="form-control" id="don_rep" name="don_rep" value="0.00">
            </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="carrier" name="carrier" value="carrier">
                <label class="form-check-label" for="carrier">
                  Include hardcopy of The Carrier
                </label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Process Payment</button>
      </div>
    </form>
    </div>
  </div>
</div>