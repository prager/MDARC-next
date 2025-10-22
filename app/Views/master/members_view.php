

<div class="container mt-5 pt-4">
    <div class="row">
    <h4 class="mb-3">Current Members</h4>
        <div class="col">

            <?php
            // Helper function to toggle direction
            function sortLink($column, $label, $sort, $dir) {
                $newDir = ($sort === $column && $dir === 'asc') ? 'desc' : 'asc';
                return '<a href="?sort='.$column.'&dir='.$newDir.'" class="btn btn-sm btn-outline-primary me-1">'
                        . ucfirst($label)
                        . ($sort === $column ? ' ('.strtoupper($dir).')' : '')
                        . '</a>';
            }
            ?>

            <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                <th>
                    <a href="?sort=lname&dir=<?= ($sort === 'lname' && $dir === 'desc') ? 'asc' : 'desc' ?>" class="text-dark text-decoration-none">
                        Name
                        <?php if ($sort === 'm.lname'): ?>
                            <i class="bi bi-arrow-<?= $dir === 'asc' ? 'down' : 'up' ?>"></i>
                        <?php else: ?>
                            <i class="bi bi-arrow-down-up text-muted"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th>
                    <a href="?sort=callsign&dir=<?= ($sort === 'callsign' && $dir === 'desc') ? 'asc' : 'desc' ?>" class="text-dark text-decoration-none">
                        Callsign
                        <?php if ($sort === 'm.callsign'): ?>
                            <i class="bi bi-arrow-<?= $dir === 'asc' ? 'down' : 'up' ?>"></i>
                        <?php else: ?>
                            <i class="bi bi-arrow-down-up text-muted"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th>
                    <a href="?sort=email&dir=<?= ($sort === 'email' && $dir === 'desc') ? 'asc' : 'desc' ?>" class="text-dark text-decoration-none">
                        Email
                        <?php if ($sort === 'm.email'): ?>
                            <i class="bi bi-arrow-<?= $dir === 'asc' ? 'down' : 'up' ?>"></i>
                        <?php else: ?>
                            <i class="bi bi-arrow-down-up text-muted"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th>Phone</th>
                <th>Pay Date</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($records)): ?>
                <?php foreach ($records as $row): ?>
                <tr>
                    <td><?= esc($row['lname']. ', ' .$row['fname'] ) ?></td>
                    <td><?= esc($row['callsign']) ?></td>
                    <td><?= esc($row['email']) ?></td>
                    <td><?= esc($row['h_phone']) ?></td>
                    <td><?= esc(date('Y-m-d', $row['paym_date'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No records found.</td></tr>
            <?php endif; ?>
            </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <div class="py-3">
            <div class="my-pager" style=""><?= $pager->links('default', 'default_full') ?></div>
        </div>
    </div>    
  </div>    
</div>
  
