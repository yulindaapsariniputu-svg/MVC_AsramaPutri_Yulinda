<?php
$startRecord = $totalRecords > 0 ? $offset + 1 : 0;
$endRecord = min($offset + $perPage, $totalRecords);
$paginationQuery = ['controller' => $controller, 'action' => 'index', 'q' => $search];
$paginationUrl = function ($page) use ($paginationQuery) {
    return 'index.php?' . http_build_query(array_merge($paginationQuery, ['page' => $page]));
};
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
    <small class="text-muted">
        Menampilkan <?= $startRecord; ?>–<?= $endRecord; ?> dari <?= $totalRecords; ?> data
    </small>
    <nav aria-label="Navigasi halaman data">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : ''; ?>">
                <?php if ($currentPage > 1): ?>
                    <a class="page-link" href="<?= htmlspecialchars($paginationUrl($currentPage - 1), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Halaman sebelumnya">Sebelumnya</a>
                <?php else: ?>
                    <span class="page-link">Sebelumnya</span>
                <?php endif; ?>
            </li>
            <?php for ($page = max(1, $currentPage - 2); $page <= min($totalPages, $currentPage + 2); $page++): ?>
                <li class="page-item <?= $page === $currentPage ? 'active' : ''; ?>">
                    <?php if ($page === $currentPage): ?>
                        <span class="page-link" aria-current="page"><?= $page; ?></span>
                    <?php else: ?>
                        <a class="page-link" href="<?= htmlspecialchars($paginationUrl($page), ENT_QUOTES, 'UTF-8'); ?>"><?= $page; ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <?php if ($currentPage < $totalPages): ?>
                    <a class="page-link" href="<?= htmlspecialchars($paginationUrl($currentPage + 1), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Halaman berikutnya">Next</a>
                <?php else: ?>
                    <span class="page-link">Next</span>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
