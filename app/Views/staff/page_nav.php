<div class="row">
              <div class="col">
              <?php
                // Inputs passed from controller:
                // $page (int), $perPage (int), $total (int), $sort (string), $dir (string)

                $page       = max(1, (int)($page ?? 1));
                $perPage    = max(1, (int)($perPage ?? 20));
                $total      = max(0, (int)($total ?? 0));
                $pageCount  = max(1, (int)ceil($total / $perPage));

                $maxLinks = 4; // show at most 4 numbered links
                $half     = intdiv($maxLinks, 2);

                // compute sliding window [start..end]
                $start = max(1, $page - $half);
                $end   = min($pageCount, $start + $maxLinks - 1);
                if ($end - $start + 1 < $maxLinks) {
                    $start = max(1, $end - $maxLinks + 1);
                }

                // helper to keep sort/dir in URLs
                if($forYear != 0) {
                    function pageUrl($p, $sort, $dir) {
                        return site_url('admin-members?sort=' . urlencode($sort) . '&dir=' . urlencode($dir) . '&page=' . (int)$p);
                    }
                }
                else {
                    function pageUrl($p, $sort, $dir) {
                        return site_url('admin-all-members?sort=' . urlencode($sort) . '&dir=' . urlencode($dir) . '&page=' . (int)$p);
                    }
                }

                ?>
                <nav aria-label="Page navigation" class="mt-3">
                  <ul class="pagination">

                    <!-- First / Prev -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page > 1 ? pageUrl(1, $sort, $dir) : '#' ?>" aria-label="First">&laquo;</a>
                    </li>
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page > 1 ? pageUrl($page - 1, $sort, $dir) : '#' ?>" aria-label="Previous">&lsaquo;</a>
                    </li>

                    <!-- Numbered (max 4) -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= pageUrl($i, $sort, $dir) ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <!-- Next / Last -->
                    <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page < $pageCount ? pageUrl($page + 1, $sort, $dir) : '#' ?>" aria-label="Next">&rsaquo;</a>
                    </li>
                    <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $page < $pageCount ? pageUrl($pageCount, $sort, $dir) : '#' ?>" aria-label="Last">&raquo;</a>
                    </li>
                  </ul>
                </nav>               
              </div>
            </div>