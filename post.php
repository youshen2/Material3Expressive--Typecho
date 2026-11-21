<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$showToc = ((string)md3e_opt($this->options, 'showToc') === '1');
$showToolbar = ((string)md3e_opt($this->options, 'showFloatingToolbar') === '1');
$enableWavy = ((string)md3e_opt($this->options, 'enableWavy') === '1');
$postMeta = (array)md3e_opt($this->options, 'showPostMeta');
$copyrightTpl = trim((string)md3e_opt($this->options, 'copyrightNotice'));

$stats = md3e_reading_stats((string)$this->content);
$cover = trim((string)($this->fields->cover ?? ''));
$subtitle = trim((string)($this->fields->subtitle ?? ''));
$badge = trim((string)($this->fields->badge ?? ''));
$categories = $this->categories;
$firstCat = !empty($categories) ? $categories[0] : null;
?>

<main class="md-blog-grid <?php echo $showToc ? 'md-blog-grid--post' : 'md-blog-grid--single'; ?>" style="padding-top:8px;">
    <div>
        <article class="md-article-sheet" itemscope itemtype="http://schema.org/BlogPosting">
            <nav class="md-breadcrumb" aria-label="<?php _e('面包屑导航'); ?>">
                <a href="<?php $this->options->siteUrl(); ?>"><?php _e('文章'); ?></a>
                <span>/</span>
                <?php if ($firstCat): ?>
                    <a href="<?php echo md3e_e($firstCat['permalink']); ?>"><?php echo md3e_e($firstCat['name']); ?></a>
                    <span>/</span>
                <?php endif; ?>
                <span><?php _e('正文'); ?></span>
                <?php if ($badge !== ''): ?>
                    <span class="md-tonal-badge md-tonal-badge--accent"><?php echo md3e_e($badge); ?></span>
                <?php endif; ?>
            </nav>

            <h1 class="md-article-title" itemprop="name headline"><?php $this->title(); ?></h1>

            <?php if ($subtitle !== ''): ?>
                <p class="md-article-subtitle"><?php echo md3e_e($subtitle); ?></p>
            <?php endif; ?>

            <div class="md-meta-row">
                <?php if (in_array('author', $postMeta, true)): ?>
                    <span class="md-meta-pill" itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <span itemprop="name"><?php $this->author(); ?></span>
                    </span>
                <?php endif; ?>
                <?php if (in_array('date', $postMeta, true)): ?>
                    <span class="md-meta-pill">
                        <?php echo md3e_icon('calendar'); ?>
                        <time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('Y年m月d日'); ?></time>
                    </span>
                <?php endif; ?>
                <?php if (in_array('readingTime', $postMeta, true)): ?>
                    <span class="md-meta-pill">
                        <?php echo md3e_icon('clock'); ?>
                        <span><?php echo $stats['minutes']; ?> 分钟阅读 · <?php echo number_format($stats['words']); ?> 字</span>
                    </span>
                <?php endif; ?>
                <?php if (in_array('comments', $postMeta, true)): ?>
                    <a class="md-meta-pill" href="#comments">
                        <?php echo md3e_icon('comment'); ?>
                        <span><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <?php echo md3e_wavy_divider('md-wavy-divider', $enableWavy); ?>

            <?php if ($cover !== ''): ?>
                <figure class="md-article-cover">
                    <img src="<?php echo md3e_e($cover); ?>" alt="<?php $this->title(); ?>">
                </figure>
            <?php endif; ?>

            <div class="md-prose" itemprop="articleBody">
                <?php $this->content(); ?>
            </div>

            <?php if ($copyrightTpl !== ''):
                $copyrightText = strtr($copyrightTpl, [
                    '{author}' => (string)$this->author->screenName,
                    '{title}'  => (string)$this->title,
                    '{url}'    => (string)$this->permalink,
                ]);
            ?>
                <div class="md-copyright-card">
                    <span class="md-copyright-label"><?php echo md3e_icon('info'); ?><span><?php _e('版权声明'); ?></span></span>
                    <p style="margin:6px 0 0;"><?php echo md3e_e($copyrightText); ?></p>
                </div>
            <?php endif; ?>

            <?php if (in_array('tags', $postMeta, true) && !empty($this->tags)): ?>
                <footer class="md-article-tags" itemprop="keywords">
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:800;color:var(--md-on-surface-variant);margin-right:4px;">
                        <?php echo md3e_icon('tag'); ?>
                        <span><?php _e('标签'); ?></span>
                    </span>
                    <?php foreach ($this->tags as $tag): ?>
                        <a class="md-chip md-chip--tonal" href="<?php echo md3e_e($tag['permalink']); ?>">
                            # <?php echo md3e_e($tag['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </footer>
            <?php endif; ?>
        </article>

        <?php if (in_array('nearPosts', $postMeta, true)): ?>
            <nav class="md-post-near" aria-label="<?php _e('相邻文章'); ?>">
                <div class="md-near-card">
                    <span class="md-near-label">
                        <?php echo md3e_icon('arrow-left'); ?>
                        <span><?php _e('上一篇'); ?></span>
                    </span>
                    <div class="md-near-title"><?php $this->thePrev('%s', _t('已经是第一篇')); ?></div>
                </div>
                <div class="md-near-card md-near-card--next">
                    <span class="md-near-label">
                        <span><?php _e('下一篇'); ?></span>
                        <?php echo md3e_icon('arrow-right'); ?>
                    </span>
                    <div class="md-near-title"><?php $this->theNext('%s', _t('已经是最新一篇')); ?></div>
                </div>
            </nav>
        <?php endif; ?>

        <?php $this->need('comments.php'); ?>
    </div>

    <?php if ($showToc): ?>
        <aside class="md-post-aside" aria-label="<?php _e('文章目录'); ?>">
            <section id="md-toc-card" class="md-toc-card">
                <div class="md-toc-head">
                    <h2 class="md-side-section-label" style="margin:0;display:flex;align-items:center;gap:6px;">
                        <?php echo md3e_icon('toc'); ?>
                        <span><?php _e('文章目录'); ?></span>
                    </h2>
                    <span id="md-toc-pct" class="md-toc-pct">0%</span>
                </div>
                <ul id="md-toc-list" class="md-toc-list"></ul>
            </section>

            <a class="md-btn md-btn--tonal" href="<?php $this->options->siteUrl(); ?>" style="width:100%;">
                <?php echo md3e_icon('arrow-left'); ?>
                <span><?php _e('返回文章列表'); ?></span>
            </a>
        </aside>
    <?php endif; ?>
</main>

<?php if ($showToolbar): ?>
<div class="md-floating-toolbar" role="toolbar" aria-label="<?php _e('阅读工具栏'); ?>">
    <a class="md-toolbar-btn md-toolbar-home" href="<?php $this->options->siteUrl(); ?>" title="<?php _e('返回文章列表'); ?>" aria-label="<?php _e('返回文章列表'); ?>">
        <?php echo md3e_icon('home'); ?>
    </a>
    <button type="button" class="md-toolbar-btn" data-font-scale title="<?php _e('切换正文字号'); ?>">
        <?php echo md3e_icon('text-size'); ?>
        <span data-font-label>标准</span>
    </button>
    <a class="md-toolbar-btn" href="#comments" title="<?php _e('跳至评论'); ?>">
        <?php echo md3e_icon('comment'); ?>
        <span><?php $this->commentsNum('0', '1', '%d'); ?></span>
    </a>
    <button type="button" class="md-toolbar-btn" data-copy-link title="<?php _e('复制文章链接'); ?>">
        <?php echo md3e_icon('link'); ?>
        <span><?php _e('分享'); ?></span>
    </button>
    <button type="button" class="md-toolbar-btn md-toolbar-btn--primary" data-scroll-top title="<?php _e('返回顶部'); ?>" aria-label="<?php _e('返回顶部'); ?>">
        <?php echo md3e_icon('arrow-up'); ?>
    </button>
</div>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
