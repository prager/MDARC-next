<section id="learn" class="p-5">
<div class="container">
    <div class="row">&nbsp;</div>
    <div class="row">
      <div class="col-lg-12">
      <h4>Search Result</h4>
      <p><?php echo $msg; ?></p>
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Cur Yr</th>
            <th>Mem Type</th>
            <th>Callsign</th>
            <th>Lic</th>
            <th>Pay Dt</th>
            <th>Mem Since</th>
            <th>Email</th>
            <th>Deactivate</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($mems as $key => $mem) {?>
          <tr>
            <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editMem<?php echo $mem['id']; ?>"><?php echo $mem['lname'] . ', ' . $mem['fname']; ?></a></td>
            <?php include 'modal_update_mem.php'; ?>
            <td><?php echo $mem['cur_year']; ?></td>
             <td>
               <?php if ($mem['id_mem_types'] == 2){ ?>
                       <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#fMems<?php echo $mem['id']; ?>"><?php echo $mem['mem_type']; ?></a> </td>
                       <?php include 'modal_fams.php'; ?>
               <?php }
                     elseif($mem['id_mem_types'] == 1) {?>
                       <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addFMems<?php echo $mem['id']; ?>"><?php echo $mem['mem_type']; ?></a></td>
                       <?php include 'modal_add_fams.php'; ?>
               <?php }
                     elseif(($mem['id_mem_types'] == 3) || ($mem['id_mem_types'] == 4)) {?>
                       <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addSpouse<?php echo $mem['id']; ?>"><?php echo $mem['mem_type']; ?></a></td>
                       <?php include 'mod_add_spouse.php'; ?>
               <?php }
                     else {?>
                       <?php echo $mem['mem_type']; ?></td>
               <?php } ?>
             </td>
             <td><?php echo $mem['callsign']; ?></td>
             <td><?php echo $mem['license']; ?></td>
             <td><?php echo $mem['pay_date']; ?></td>
             <td><?php echo $mem['mem_since']; ?></td>
             <td><?php
             if(strlen($mem['email']) > 30) {
               echo substr($mem['email'], 0, 30) . '...';
             }
             else {
               echo $mem['email'];
             }?>
           </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#delMem<?php echo $mem['id']; ?>"><i class="bi bi-trash"></i></a>
              <?php include 'mod_del_mem.php'; ?>
            </td>
          </tr>
        <?php }?>
        </tbody>
      </table>
      </div>
    </div>
</section>
