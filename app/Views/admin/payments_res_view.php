<!-- Updated 5 -->
<style>
    .table .invalid-payment > td {
        color: var(--bs-danger) !important;
    }
</style>
<section id="learn" class="py-5">
    <div class="container">
        <div class="row mt-5 pt-3">
            <div class="col-lg offset-lg-1">
            <h3 class="mb-3">Payments Report - Total for Period: <?php echo $total; ?> | Fees Total: <?php echo $total_fee; ?></h3>
            <p><small>Date From: <?php echo $dates['date_from']; ?> | Date To: <?php echo $dates['date_to']; ?> | <?php echo anchor('admin/download_pay_rep', 'Download Report', 'class="text-decoration-none"')?> | <?php echo anchor('admin/download_transactions', 'Download Transactions', 'class="text-decoration-none"')?> | <a href="<?php echo site_url('payment-report'); ?>" class="text-decoration-none">Select Dates</a></small></p>
            </div>
        </div>

        <?php if($msg!= '') { ?>
            <div class="row">
                <div class="col-lg-8 offset-lg-1 text-danger">
                    <?php echo $msg; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 offset-lg-1">
                    <hr>
                </div>
            </div>
        <?php } ?>
        
        <div class="row">
            <div class="col offset-lg-1">
                <table class="table table-stripped">
                    <thead>
                        <tr>
                            <th scope="col">ID Payments</th>
                            <th scope="col">ID Trans</th>
                            <th scope="col">ID Member</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Date</th>
                            <th scope="col">Payaction</th>
                            <th scope="col">Method</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($payments as $payment) { ?>
                            <?php if($payment['flag'] === 0) { ?>
                            <tr>
                                <td><a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $payment['id_payments']; ?>" class="text-decoration-none"><?php echo $payment['id_payments']; ?></a></td>
                                <td><?php echo $payment['id_trans']; ?></td>
                                <td><?php echo $payment['id_member']; ?></td>
                                <td><?php echo $payment['fname']; ?></td>
                                <td><?php echo $payment['lname']; ?></td>
                                <td><?php echo date("Y-m-d", $payment['paydate']); ?></td>
                                <td><?php echo $payment['payaction']; ?></td>
                                <td><?php echo $payment['mode']; ?></td>
                                <td><?php echo $payment['amount']; ?></td>
                                <td><?php echo $payment['fee']; ?></td>
                                <td><?php echo $payment['note']; ?></td>
                            </tr>                            
                            <?php } 
                                else {?>
                                <tr class="invalid-payment">
                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $payment['id_payments']; ?>" class="text-decoration-none"><?php echo $payment['id_payments']; ?></a></td>
                                    <td><?php echo $payment['id_trans']; ?></td>
                                    <td><?php echo $payment['id_member']; ?></td>
                                    <td><?php echo $payment['fname']; ?></td>
                                    <td><?php echo $payment['lname']; ?></td>
                                    <td><?php echo date("Y-m-d", $payment['paydate']); ?></td>
                                    <td><?php echo $payment['payaction']; ?></td>
                                    <td><?php echo $payment['mode']; ?></td>
                                    <td>$0.00</td>
                                    <td>$0.00</td>
                                    <td><?php echo $payment['note']; ?></td>
                                </tr> 

                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>

                <?php foreach($payments as $payment) { ?>
                    <div class="modal fade" id="editModal<?php echo $payment['id_payments']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $payment['id_payments']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?php echo site_url('edit-payment/' . $payment['id_payments']); ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel<?php echo $payment['id_payments']; ?>">Edit Payment ID <?php echo $payment['id_payments']; ?></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="note<?php echo $payment['id_payments']; ?>" class="form-label">Remark</label>
                                            <input class="form-control" type="text" placeholder="Note Text" name="note" id="note<?php echo $payment['id_payments']; ?>" value="<?php echo esc($payment['note']); ?>">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="radFlag" id="radFlagValid<?php echo $payment['id_payments']; ?>" value="valid" <?php echo $payment['flag'] === 0 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="radFlagValid<?php echo $payment['id_payments']; ?>">Valid</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="radFlag" id="radFlagNotValid<?php echo $payment['id_payments']; ?>" value="notvalid" <?php echo $payment['flag'] === 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="radFlagNotValid<?php echo $payment['id_payments']; ?>">Not Valid</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
