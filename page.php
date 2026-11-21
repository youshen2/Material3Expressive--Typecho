<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$subtitle = trim((string)($this->fields->subtitle ?? ''));
?>

<main class="md-blog-grid md-blog-grid--single" style="padding-top:8px;">
    <div>
        <article class="md-article-sheet" itemscope itemtype="http://schema.org/WebPage">
            <nav class="md-breadcrumb" aria-label="<?php _e('面包屑导航'); ?>">
                <a href="<?php $this->options->siteUrl(); ?>"><?php _e('文章'); ?></a>
                <span>/</span>
                <span><?php $this->title(); ?></span>
            </nav>

            <h1 class="md-article-title" itemprop="name headline"><?php $this->title(); ?></h1>
            <?php if ($subtitle !== ''): ?>
                <p class="md-article-subtitle"><?php echo md3e_e($subtitle); ?></p>
            <?php endif; ?>

            <?php echo md3e_wavy_divider('md-wavy-divider', (string)md3e_opt($this->options, 'enableWavy') === '1'); ?>

            <div class="md-prose" itemprop="mainContentOfPage">
                <?php $this->content(); ?>
            </div>
        </article>

        <?php $this->need('comments.php'); ?>
    </div>
</main>

<?php $this->need('footer.php'); ?>
