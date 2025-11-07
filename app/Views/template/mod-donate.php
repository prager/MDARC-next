<!-- Donate Modal -->
<div class="modal fade" id="goDonate" tabindex="-1" aria-labelledby="goDonateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo base_url() . '/index.php/go-pay/0'; ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="goDonateLabel">Donate to MDARC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row mt-3 mb-3">
                    <div class="col">
                    <h4>Donation Amount to MDARC:</h4>
                    <small>Minimum total donation $5.00</small>
                    </div>
                </div>
                <div class="row mb-5">                    
                    <div class="col-lg-8">
                    <label for="mdarc_donation">Enter Donation Amount</label>
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