

<div class="container mt-5 pt-4">
    <div class="row">
    <h4 class="mb-3">Current Members</h4>
        <div class="col">
        <div class="table-responsive shadow-sm">
    <table class="table table-striped align-middle">
      <thead class="table-light">
      <tr>
        <?php
          // Helper to flip sort direction
          function nextDir($cur) { return $cur === 'ASC' ? 'DESC' : 'ASC'; }

          $columns = [
              'id_members'  => 'ID',
              'fname' => 'First Name',
              'lname'  => 'Last Name',
              'email'      => 'Email',
              'phone'      => 'Phone',
          ];
        ?>

        <?php foreach ($columns as $col => $label): 
            $arrow = ($sort === $col)
                ? ($dir === 'ASC' ? '↑' : '↓')
                : '';
            $link = site_url('members?sort=' . $col . '&dir=' . nextDir($dir));
        ?>
          <th>
            <a href="<?= $link ?>"><?= esc($label) ?> <?= $arrow ?></a>
          </th>
        <?php endforeach; ?>
      </tr>
      </thead>

      <tbody>
      <?php foreach ($members as $m): ?>
        <tr>
          <td><?= esc($m['id_members']) ?></td>
          <td><?= esc($m['fname'] ?? '') ?></td>
          <td><?= esc($m['lname'] ?? '') ?></td>
          <td><?= esc($m['email'] ?? '') ?></td>
          <td><?= esc($m['phone'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
        </div>

        <!-- Pagination links -->
        <div class="py-3">
            <div class="my-pager" style=""><?= $pager->links('members', 'default_full') ?></div>
        </div>
    </div>    
  </div>    
</div>
  
