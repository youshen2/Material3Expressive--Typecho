<?php
/**
 * Material Design 3 Expressive (MD3E) 博客主题。以文章阅读流为核心，配备完整的 6 标签页后台外观设置、动态/自定义配色种子、连接式分类按钮组、Android 16 分组分段文章流、6 种 MD3E 有机几何日期戳、波浪阅读进度条与浮动阅读工具栏。
 *
 * @package Material 3 Expressive (MD3E)
 * @author 爅峫（洛汐聚合体 / youshen2）
 * @version 2.2.0
 * @link https://m3.material.io
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$siteStats = md3e_site_stats();
$isFirstPage = ($this->_currentPage <= 1);
$mastheadTitle = trim((string)md3e_opt($this->options, 'mastheadTitle')) ?: $this->options->title;
$mastheadDesc = trim((string)md3e_opt($this->options, 'mastheadDesc')) ?: $this->options->description;
$showStatsPill = ((string)md3e_opt($this->options, 'showStatsPill') === '1');
$showCategoryBar = ((string)md3e_opt($this->options, 'showCategoryBar') === '1');
$leadPostStyle = (string)md3e_opt($this->options, 'leadPostStyle');
$sidebarPos = (string)md3e_opt($this->options, 'sidebarPosition');
$enableWavy = ((string)md3e_opt($this->options, 'enableWavy') === '1');
?>

<main>
    <!-- Compact Blog-First Masthead -->
    <header class="md-masthead">
        <div>
            <h1 class="md-masthead-title"><?php echo md3e_e($mastheadTitle); ?></h1>
            <?php if ($mastheadDesc !== ''): ?>
                <p class="md-masthead-desc"><?php echo md3e_e($mastheadDesc); ?></p>
            <?php endif; ?>
            <?php echo md3e_wavy_divider('md-wavy-divider md-wavy-divider--accent', $enableWavy); ?>
        </div>

        <div class="md-masthead-tools">
            <button type="button" class="md-search-pill" data-open-search>
                <?php echo md3e_icon('search'); ?>
                <span><?php _e('搜索文章…'); ?></span>
                <span class="md-kbd">⌘K</span>
            </button>
            <?php if ($showStatsPill): ?>
                <span class="md-stat-pill" title="<?php _e('全站内容统计'); ?>">
                    <?php echo $siteStats['posts']; ?> 篇 · <?php echo $siteStats['comments']; ?> 评
                </span>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($showCategoryBar): ?>
        <nav class="md-connected-group" aria-label="<?php _e('按分类筛选文章'); ?>">
            <a class="md-connected-btn is-selected" href="<?php $this->options->siteUrl(); ?>">
                <span><?php _e('全部文章'); ?></span>
                <span class="md-connected-count"><?php echo $siteStats['posts']; ?></span>
            </a>
            <?php \Widget\Metas\Category\Rows::alloc()->to($cats); ?>
            <?php while ($cats->next()): ?>
                <a class="md-connected-btn" href="<?php $cats->permalink(); ?>">
                    <span><?php $cats->name(); ?></span>
                    <span class="md-connected-count"><?php $cats->count(); ?></span>
                </a>
            <?php endwhile; ?>
        </nav>
    <?php endif; ?>

    <div class="md-blog-grid<?php echo $sidebarPos === 'none' ? ' md-blog-grid--nosidebar' : ''; ?>">
        <section aria-label="<?php _e('最新博文'); ?>">
            <?php if ($this->have()): ?>
                <?php
                $idx = 0;
                $openedGroup = false;
                while ($this->next()):
                    $md3eIsLead = ($leadPostStyle === 'highlight' && $isFirstPage && $idx === 0);
                    if (!$md3eIsLead && !$openedGroup) {
                        echo '<div class="md-grouped-feed">';
                        $openedGroup = true;
                    }
                    include __DIR__ . '/post-card.php';
                    $idx++;
                endwhile;
                if ($openedGroup) {
                    echo '</div>';
                }
                ?>
            <?php else: ?>
                <div class="md-empty-stage">
                    <div class="md-404-emblem">0</div>
                    <h2 style="margin:0 0 8px;"><?php _e('还没有发布文章'); ?></h2>
                    <p style="color:var(--md-on-surface-variant);margin:0 0 20px;"><?php _e('前往 Typecho 后台写下第一篇博客吧。'); ?></p>
                    <a class="md-btn md-btn--filled" href="<?php $this->options->adminUrl(); ?>"><?php _e('进入后台'); ?></a>
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
