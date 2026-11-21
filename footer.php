<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$enableWavy = ((string)md3e_opt($this->options, 'enableWavy') === '1');
$allowVisitorPalette = ((string)md3e_opt($this->options, 'allowVisitorPalette') === '1');
$customSeedColor = md3e_safe_hex((string)md3e_opt($this->options, 'customSeedColor'));
$footerText = trim((string)md3e_opt($this->options, 'footerText'));
$icpNumber = trim((string)md3e_opt($this->options, 'icpNumber'));
$icpUrl = trim((string)md3e_opt($this->options, 'icpUrl')) ?: 'https://beian.miit.gov.cn/';
$customFooterCode = trim((string)md3e_opt($this->options, 'customFooterCode'));

$dockPages = [];
\Widget\Contents\Page\Rows::alloc()->to($dockWidget);
while ($dockWidget->next()) {
    $dockPages[] = [
        'slug'      => $dockWidget->slug,
        'title'     => $dockWidget->title,
        'permalink' => $dockWidget->permalink,
    ];
}
?>
            <?php echo md3e_wavy_divider('md-wavy-divider', $enableWavy); ?>
            <footer class="md-footer">
                <div>
                    <strong>&copy; <?php echo date('Y'); ?> <?php $this->options->title(); ?></strong>
                    <?php if ($footerText !== ''): ?>
                        <span style="margin-left:10px;opacity:.82;"><?php echo md3e_e($footerText); ?></span>
                    <?php endif; ?>
                    <?php if ($icpNumber !== ''): ?>
                        <a href="<?php echo md3e_e($icpUrl); ?>" target="_blank" rel="noopener noreferrer" style="margin-left:10px;opacity:.82;"><?php echo md3e_e($icpNumber); ?></a>
                    <?php endif; ?>
                </div>
                <div class="md-footer-links">
                    <a href="<?php $this->options->feedUrl(); ?>">RSS</a>
                    <?php if ($this->user->hasLogin()): ?>
                        <a href="<?php $this->options->adminUrl(); ?>"><?php _e('管理后台'); ?></a>
                    <?php else: ?>
                        <a href="<?php $this->options->adminUrl('login.php'); ?>"><?php _e('登录'); ?></a>
                    <?php endif; ?>
                    <a href="https://typecho.org" target="_blank" rel="noopener noreferrer">Typecho</a>
                </div>
            </footer>
        </div><!-- .md-stage-inner -->
    </div><!-- .md-stage -->
</div><!-- .md-app-shell -->

<!-- Mobile Floating MD3E Navigation Dock -->
<nav class="md-mobile-dock" aria-label="<?php _e('移动底部导航'); ?>">
    <a class="md-dock-item<?php if ($this->is('index') || $this->is('post') || $this->is('archive')): ?> is-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>">
        <?php echo md3e_icon('article'); ?>
        <span><?php _e('文章'); ?></span>
    </a>
    <?php foreach (array_slice($dockPages, 0, 2) as $dp): ?>
        <a class="md-dock-item<?php if ($this->is('page', $dp['slug'])): ?> is-active<?php endif; ?>" href="<?php echo md3e_e($dp['permalink']); ?>">
            <?php echo md3e_icon($dp['slug'] === 'archives' ? 'archive' : 'info'); ?>
            <span><?php echo md3e_e($dp['title']); ?></span>
        </a>
    <?php endforeach; ?>
    <?php if ($allowVisitorPalette): ?>
        <button type="button" class="md-dock-item" data-toggle-palette aria-label="<?php _e('配色'); ?>">
            <?php echo md3e_icon('palette'); ?>
        </button>
    <?php endif; ?>
</nav>

<?php if ($allowVisitorPalette): ?>
<!-- Dynamic MD3E Color Seed Palette Sheet -->
<div id="md-palette-sheet" class="md-palette-sheet" role="region" aria-label="<?php _e('MD3E 动态配色'); ?>">
    <p class="md-sheet-label"><?php _e('MD3E 色彩种子 (Dynamic Seed)'); ?></p>
    <div class="md-seed-list">
        <button type="button" class="md-seed-btn" data-set-palette="berry">
            <span class="md-seed-swatch" style="background:#9B2362;"></span>
            <span>莓果红</span>
        </button>
        <button type="button" class="md-seed-btn" data-set-palette="violet">
            <span class="md-seed-swatch" style="background:#5D3FD3;"></span>
            <span>电光紫</span>
        </button>
        <button type="button" class="md-seed-btn" data-set-palette="lime">
            <span class="md-seed-swatch" style="background:#2D6B2E;"></span>
            <span>青苔绿</span>
        </button>
        <button type="button" class="md-seed-btn" data-set-palette="tangerine">
            <span class="md-seed-swatch" style="background:#B03A16;"></span>
            <span>日光橘</span>
        </button>
        <button type="button" class="md-seed-btn" data-set-palette="cobalt">
            <span class="md-seed-swatch" style="background:#1556C9;"></span>
            <span>钴海蓝</span>
        </button>
        <button type="button" class="md-seed-btn" data-set-palette="custom">
            <span class="md-seed-swatch" style="background:<?php echo md3e_e($customSeedColor); ?>;"></span>
            <span>自定义</span>
        </button>
    </div>

    <p class="md-sheet-label"><?php _e('外观模式'); ?></p>
    <div class="md-mode-segmented">
        <button type="button" class="md-mode-btn" data-set-theme="light">
            <?php echo md3e_icon('sun'); ?>
            <span>浅色</span>
        </button>
        <button type="button" class="md-mode-btn" data-set-theme="dark">
            <?php echo md3e_icon('moon'); ?>
            <span>深色</span>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Cmd/Ctrl + K Expressive Search Dialog -->
<div id="md-search-scrim" class="md-scrim" role="dialog" aria-modal="true" aria-label="<?php _e('站内搜索'); ?>">
    <div class="md-dialog">
        <form class="md-search-form" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
            <?php echo md3e_icon('search'); ?>
            <input id="md-search-input" type="text" name="s" placeholder="<?php _e('搜索文章标题、关键词或标签…'); ?>" autocomplete="off" required>
            <button type="submit" class="md-btn md-btn--filled" style="padding:8px 18px;"><?php _e('搜索'); ?></button>
        </form>
        <div class="md-search-quick">
            <span style="font-size:.76rem;font-weight:800;color:var(--md-on-surface-variant);margin-right:4px;"><?php _e('快速浏览'); ?></span>
            <?php \Widget\Metas\Category\Rows::alloc()->to($searchCats); ?>
            <?php while ($searchCats->next()): ?>
                <a class="md-chip md-chip--tonal" href="<?php $searchCats->permalink(); ?>"><?php $searchCats->name(); ?></a>
            <?php endwhile; ?>
        </div>
        <div class="md-search-tips">
            <span><?php _e('Enter 搜索'); ?></span>
            <span>ESC 关闭</span>
        </div>
    </div>
</div>

<?php if (!$this->is('post')): ?>
<button id="md-top-fab" type="button" class="md-top-fab" data-scroll-top title="<?php _e('返回顶部'); ?>" aria-label="<?php _e('返回顶部'); ?>">
    <?php echo md3e_icon('arrow-up'); ?>
</button>
<?php endif; ?>

<!-- Lightbox (no empty src attribute) -->
<div id="md-lightbox" class="md-scrim" aria-hidden="true">
    <img id="md-lightbox-img" class="md-lightbox-img" alt="">
</div>

<!-- MD3E Snackbar Toast -->
<div id="md-snackbar" class="md-snackbar" role="status" aria-live="polite"></div>

<script src="<?php $this->options->themeUrl('assets/js/md3e.js'); ?>" defer></script>
<?php if ($customFooterCode !== ''): ?>
    <?php echo $customFooterCode; ?>
<?php endif; ?>
<?php $this->footer(); ?>
</body>
</html>
