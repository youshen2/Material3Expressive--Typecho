<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$showCategoryBar = ((string)md3e_opt($this->options, 'showCategoryBar') === '1');
$sidebarPos = (string)md3e_opt($this->options, 'sidebarPosition');

$kicker = _t('内容归档');
if ($this->is('category')) {
    $kicker = _t('分类专栏');
} elseif ($this->is('tag')) {
    $kicker = _t('标签集合');
} elseif ($this->is('search')) {
    $kicker = _t('站内搜索');
} elseif ($this->is('author')) {
    $kicker = _t('作者专栏');
}
?>

<main>
    <section class="md-archive-header">
        <div>
            <span class="md-archive-kicker">
                <?php echo md3e_icon('spark'); ?>
                <span><?php echo md3e_e($kicker); ?></span>
            </span>
            <h1 class="md-archive-title">
                <?php $this->archiveTitle([
                    'category' => _t('分类「%s」'),
                    'search'   => _t('搜索「%s」'),
                    'tag'      => _t('标签 #%s'),
                    'author'   => _t('%s 的文章'),
                    'date'     => _t('%s 归档'),
                ], '', ''); ?>
            </h1>
        </div>
        <a class="md-btn md-btn--filled" href="<?php $this->options->siteUrl(); ?>" style="flex-shrink:0;">
            <?php echo md3e_icon('arrow-left'); ?>
            <span><?php _e('全部文章'); ?></span>
        </a>
    </section>

    <?php if ($showCategoryBar): ?>
        <?php $siteStats = md3e_site_stats(); ?>
        <nav class="md-connected-group" aria-label="<?php _e('切换文章分类'); ?>">
            <a class="md-connected-btn" href="<?php $this->options->siteUrl(); ?>">
                <span><?php _e('全部文章'); ?></span>
                <span class="md-connected-count"><?php echo $siteStats['posts']; ?></span>
            </a>
            <?php \Widget\Metas\Category\Rows::alloc()->to($cats); ?>
            <?php while ($cats->next()): ?>
                <a class="md-connected-btn<?php if ($this->is('category', $cats->slug)): ?> is-selected<?php endif; ?>" href="<?php $cats->permalink(); ?>">
                    <span><?php $cats->name(); ?></span>
                    <span class="md-connected-count"><?php $cats->count(); ?></span>
                </a>
            <?php endwhile; ?>
        </nav>
    <?php endif; ?>

    <div class="md-blog-grid<?php echo $sidebarPos === 'none' ? ' md-blog-grid--nosidebar' : ''; ?>">
        <section>
            <?php if ($this->have()): ?>
                <div class="md-grouped-feed">
                    <?php while ($this->next()): ?>
                        <?php
                        $md3eIsLead = false;
                        include __DIR__ . '/post-card.php';
                        ?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="md-empty-stage">
                    <div class="md-404-emblem">?</div>
                    <h2 style="margin:0 0 8px;"><?php _e('没有找到匹配的文章'); ?></h2>
                    <p style="color:var(--md-on-surface-variant);margin:0 0 20px;"><?php _e('试试其他关键词，或回到首页浏览全部内容。'); ?></p>
                    <button type="button" class="md-btn md-btn--tonal" data-open-search><?php _e('重新搜索'); ?></button>
                </div>
            <?php endif; ?>

            <nav class="md-pagination" aria-label="<?php _e('分页导航'); ?>">
                <?php $this->pageNav('←', '→', 2, '…'); ?>
            </nav>
        </section>

        <?php if ($sidebarPos !== 'none'): ?>
            <?php $this->need('sidebar.php'); ?>
        <?php endif; ?>
    </div>
</main>

<?php $this->need('footer.php'); ?>
