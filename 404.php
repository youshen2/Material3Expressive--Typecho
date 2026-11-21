<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<main>
    <section class="md-empty-stage">
        <div class="md-404-emblem" aria-hidden="true">404</div>
        <h1 style="margin:0 0 10px;font-family:var(--md-font-display);font-size:2rem;font-weight:800;letter-spacing:-0.03em;">
            <?php _e('页面没有找到'); ?>
        </h1>
        <p style="margin:0 0 24px;color:var(--md-on-surface-variant);">
            <?php _e('该文章或地址可能已移动、删除，或链接拼写有误。'); ?>
        </p>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:10px;">
            <a class="md-btn md-btn--filled" href="<?php $this->options->siteUrl(); ?>">
                <?php echo md3e_icon('home'); ?>
                <span><?php _e('返回文章列表'); ?></span>
            </a>
            <button type="button" class="md-btn md-btn--tonal" data-open-search>
                <?php echo md3e_icon('search'); ?>
                <span><?php _e('搜索文章'); ?></span>
            </button>
        </div>
    </section>
</main>

<?php $this->need('footer.php'); ?>
