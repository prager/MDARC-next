<!--Staff Section-->
<section id="learn" class="p-5">
    <div class="container">
        <div class="row">&nbsp;</div>
        <div class="row px-5">
            <div class="col">
              <h4><?php echo date('Y', time()); ?> Member Directory per Callsign</h4>
                <small>Total members count: <?php echo $dir_cnt; ?></small>
                <hr>
                <p style="font-size: 14px;"><span style="font-weight: bold;"> Call Sign First Name Last Name</span> (License Class), E-Mail Address<br>
                Name, (Spouse Name, Spouse Call Sign); Address; Phone Numbers</p>
            </div>
        </div>
        <div class="row px-5">
          <div class="col-md">
            <?php foreach($callsigns as $mem) {?>
            <p style="font-size: 14px;">  <?php
            $strshow = '<span style="font-weight: bold;">' . $mem['callsign'] . ' ' . $mem['fname'] . ' ' . $mem['lname'] . '</span>' . ' (' . $mem['license'] . ')';

            strtolower($mem['email_unlisted']) != 'true' ? $strshow .= ' ' . strtolower($mem['email']) : $strshow .= '';
            //$mem['email_unlisted'] == 'False' ? $strshow .= '</span>' . ' ' . strtolower(mailto($mem['email'])) : $strshow .= '</span>';
            echo $strshow; ?><br>
            <span style="font-style: italic;">
            <?php
            $strshow =  $mem['address'] . ', ' . $mem['city'] . ', ' . $mem['state'] . ' ' .  $mem['zip'];

            if ($mem['w_phone'] != '000-000-0000') {
              strtolower($mem['cell_unlisted']) != 'true' ? $strshow .= '; cell: ' . $mem['w_phone'] : $strshow .= '';
            }

            if ($mem['h_phone'] != '000-000-0000') {
              strtolower($mem['phone_unlisted']) != 'true' ? $strshow .= '; phone: ' . $mem['h_phone'] : $strshow .= '';
            }
            echo $strshow; ?>
            <?php }?>
          </div>
        </div>
    </div>
</section>
