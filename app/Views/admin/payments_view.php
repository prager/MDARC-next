<!-- Updated 1 -->
<section id="learn" class="py-5">
    <div class="container">
        <div class="row align-items-center justify-content-between mt-5">
            <div class="col-md-4">
                <img src="/img/progr.svg" class="img-fluid w-75 d-none d-md-block" alt="">
            </div>
            <?php
                $timestamp = time();
                $dateTimeObj = new DateTime();
                $dateTimeObj->setTimestamp($timestamp);
                $dateTime = strval($dateTimeObj->format('Y-m-d'));
                $dateTimeFrom = strval(date('Y-m-d', strtotime("-1 months", strtotime($dateTime))));
            ?>
            <div class="col-md offset-md-1">
              <h3 class="mb-3">Select Dates for Payment Report</h3>

              <!-- <p>Still to do...<?php echo $dateTimeFrom; ?></p> -->
               
            <form action="<?php echo site_url('proc-payments-report'); ?>" method="post">
                <div class="row">
                    <div class="col-lg-6 py-2">
                        <label for="date_from">Report Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $dateTimeFrom; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 py-2">
                        <label for="date_to">Report Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $dateTime; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col p-3">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        
    </div>
</section>
