<!--Staff Section-->
<section id="learn" class="p-5">
    <div class="container">
        <div class="row">&nbsp;</div>
        <div class="row px-5">
            <div class="col">
              <h4><?php echo date('Y', time()); ?> Member Directory</h4>
                <small>Total members count: <?php echo $dir_cnt; ?></small>
                <hr>
                <p style="font-size: 14px;"><span style="font-weight: bold;"> Name, Call Sign, (License Class), Spouse Name, Spouse Call Sign</span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; E-Mail<br>
                Address, Phone Numbers</p>
            </div>
        </div>
        <div class="row px-5">
          <div class="col-md">
            <?php foreach($dir as $mem) {?>
            <p style="font-size: 14px;"><span style="font-weight: bold;">  <?php
            $strshow = '';
            if((strlen($mem['spouse_name']) > 0) && (strlen($mem['spouse_call']) > 0)) {
              $strshow = $mem['lname'] . ', ' . $mem['fname'] . ' ' . $mem['callsign'] . ' (' .
              $mem['license'] . '), ' . $mem['spouse_name'] . ' (' . $mem['spouse_call'] . ')';
            }
            elseif((strlen($mem['spouse_name']) > 0) && (strlen($mem['spouse_call']) == 0)) {
              $strshow = $mem['lname'] . ', ' . $mem['fname'] . ' ' . $mem['callsign'] . ' (' .
              $mem['license'] . '), ' . $mem['spouse_name'];
            }
            elseif(strlen($mem['spouse_name']) == 0) {
              $strshow = $mem['lname'] . ', ' . $mem['fname'] . ' ' . $mem['callsign'] . ' (' .
              $mem['license'] . ')';
            }
            strtolower($mem['email_unlisted']) != 'true' ? $strshow .= '</span>' . ' ' . strtolower($mem['email']) : $strshow .= '</span>';
            //$mem['email_unlisted'] == 'False' ? $strshow .= '</span>' . ' ' . strtolower(mailto($mem['email'])) : $strshow .= '</span>';
            echo $strshow; ?><br></span>
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
            </span>
            <?php }?>
          </div>
        </div>
    </div>
</section>
