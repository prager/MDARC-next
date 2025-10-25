<?php
// Limit number of numeric page links
$maxLinks = 4;

$totalPages = $pager->getPageCount();
$current    = $pager->getCurrentPage();
$start      = max(1, $current - floor($maxLinks / 2));
$end        = min($totalPages, $start + $maxLinks - 1);

if ($end - $start + 1 < $maxLinks) {
    $start = max(1, $end - $maxLinks + 1);
}
?>

<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">

    <!-- First & Previous -->
    <?php if ($pager->hasPrevious()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="First">&laquo;</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">&lsaquo;</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
      <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
    <?php endif; ?>

    <!-- Page numbers (limited to 4) -->
    <?php for ($i = $start; $i <= $end; $i++): ?>
      <li class="page-item <?= $i === $current ? 'active' : '' ?>">
        <a class="page-link" href="<?= $pager->getPageURI($i) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next & Last -->
    <?php if ($pager->hasNext()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Next">&rsaquo;</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Last">&raquo;</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
      <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
    <?php endif; ?>
  </ul>
</nav>
