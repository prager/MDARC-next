<div class="row">
              <div class="col">
              <?php
                // Inputs passed from controller:
                // $page (int), $perPage (int), $total (int), $sort (string), $dir (string)

                $page       = max(1, (int)($page ?? 1));
                $perPage    = max(1, (int)($perPage ?? 20));
                $total      = max(0, (int)($total ?? 0));
                $sort       = (string)($sort ?? 'lname');
                $dir        = (string)($dir ?? 'ASC');
                $pageCount  = max(1, (int)ceil($total / $perPage));

                $maxLinks = 4; // show at most 4 numbered links
                $half     = intdiv($maxLinks, 2);

                // compute sliding window [start..end]
                $start = max(1, $page - $half);
                $end   = min($pageCount, $start + $maxLinks - 1);
                if ($end - $start + 1 < $maxLinks) {
                    $start = max(1, $end - $maxLinks + 1);
                }

                $route = ((int)($forYear ?? 0) !== 0) ? 'staff-members' : 'staff-all-members';
                $pageUrl = static function ($p) use ($route, $sort, $dir) {
                    return site_url($route) . '?' . http_build_query([
                        'sort' => $sort,
                        'dir' => $dir,
                        'page' => (int) $p,
                    ]);
                };

                ?>
                <nav aria-label="Page navigation" class="mt-3">
                  <ul class="pagination">

                    <!-- First / Prev -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page > 1 ? $pageUrl(1) : '#' ?>" aria-label="First">&laquo;</a>
                    </li>
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page > 1 ? $pageUrl($page - 1) : '#' ?>" aria-label="Previous">&lsaquo;</a>
                    </li>

                    <!-- Numbered (max 4) -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pageUrl($i) ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <!-- Next / Last -->
                    <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page < $pageCount ? $pageUrl($page + 1) : '#' ?>" aria-label="Next">&rsaquo;</a>
                    </li>
                    <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page < $pageCount ? $pageUrl($pageCount) : '#' ?>" aria-label="Last">&raquo;</a>
                    </li>
                  </ul>
                </nav>               
              </div>
            </div>
