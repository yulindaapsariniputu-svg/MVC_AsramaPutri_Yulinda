<form method="get" action="index.php" class="row g-2 align-items-center mb-3">
    <input type="hidden" name="controller" value="<?= htmlspecialchars($controller, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="action" value="index">
    <div class="col-sm-8 col-md-6 col-lg-5">
        <label for="search-query" class="visually-hidden">Cari data</label>
        <input id="search-query" type="search" name="q" class="form-control"
               placeholder="<?= htmlspecialchars($searchPlaceholder, ENT_QUOTES, 'UTF-8'); ?>"
               value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if ($search !== ''): ?>
            <a class="btn btn-outline-secondary" href="index.php?controller=<?= htmlspecialchars($controller, ENT_QUOTES, 'UTF-8'); ?>&amp;action=index">Reset</a>
        <?php endif; ?>
    </div>
</form>
