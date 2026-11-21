<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/** @var \Widget\Archive $this */
$isLead = !empty($md3eIsLead);
$showDateStamp = ((string)md3e_opt($this->options, 'showDateStamp') === '1');
$excerptLen = (int)md3e_opt($this->options, 'excerptLength', 110);
if ($excerptLen < 40) $excerptLen = 110;

$cover = trim((string)($this->fields->cover ?? ''));
$badge = trim((string)($this->fields->badge ?? ''));
$stats = md3e_reading_stats((string)$this->content);
$shapeClass = md3e_shape_class((int)$this->cid);
$toneClass = md3e_tone_class((int)$this->cid);
$categories = $this->categories;
$firstCat = !empty($categories) ? $categories[0] : null;
$day = date('d', $this->created);
$month = date('n月', $this->created);
?>
<?php if ($isLead): ?>
<article class="md-lead-post<?php echo $showDateStamp ? '' : ' md-lead-post--nostamp'; ?>" data-card-url="<?php $this->permalink(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
    <?php if ($showDateStamp): ?>
        <a class="md-shape-stamp <?php echo md3e_e($shapeClass); ?>" href="<?php $this->permalink(); ?>" aria-label="<?php $this->date('Y-m-d'); ?>">
            <span class="md-stamp-day"><?php echo md3e_e($day); ?></span>
            <span class="md-stamp-mon"><?php echo md3e_e($month); ?></span>
        </a>
    <?php endif; ?>

    <div>
        <div class="md-row-meta">
            <?php if ($badge !== ''): ?>
                <span class="md-tonal-badge md-tonal-badge--surface"><?php echo md3e_icon('spark'); ?><span><?php echo md3e_e($badge); ?></span></span>
            <?php else: ?>
                <span class="md-tonal-badge md-tonal-badge--surface"><?php _e('最新文章'); ?></span>
            <?php endif; ?>

            <?php if ($firstCat): ?>
                <a class="md-tonal-badge" href="<?php echo md3e_e($firstCat['permalink']); ?>">
                    <?php echo md3e_e($firstCat['name']); ?>
                </a>
            <?php endif; ?>

            <span style="font-size:.8rem;font-weight:700;opacity:.76;">
                <?php echo $stats['minutes']; ?> 分钟 · <?php echo number_format($stats['words']); ?> 字
            </span>
        </div>

        <h2 class="md-lead-title" itemprop="name headline">
            <a itemprop="url" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
        </h2>

        <p class="md-lead-excerpt" itemprop="description">
            <?php echo md3e_e(md3e_excerpt($this, min(300, $excerptLen + 25))); ?>
        </p>

        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
            <div class="md-split-btn">
                <a class="md-split-main" href="<?php $this->permalink(); ?>"><?php _e('阅读全文'); ?></a>
                <a class="md-split-end" href="<?php $this->permalink(); ?>" aria-label="<?php _e('进入正文'); ?>">
                    <?php echo md3e_icon('arrow-right'); ?>
                </a>
            </div>
            <span class="md-metric" style="font-size:.82rem;font-weight:700;opacity:.82;">
                <?php echo md3e_icon('comment'); ?>
                <span><?php $this->commentsNum('0 评论', '1 评论', '%d 评论'); ?></span>
            </span>
        </div>
    </div>

    <?php if ($cover !== ''): ?>
        <a class="md-lead-thumb" href="<?php $this->permalink(); ?>">
            <img src="<?php echo md3e_e($cover); ?>" alt="<?php $this->title(); ?>" loading="lazy">
        </a>
    <?php endif; ?>
</article>
<?php else: ?>
<article class="md-post-row<?php echo $showDateStamp ? '' : ' md-post-row--nostamp'; ?>" data-card-url="<?php $this->permalink(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
    <?php if ($showDateStamp): ?>
        <a class="md-shape-stamp <?php echo md3e_e($shapeClass . ' ' . $toneClass); ?>" href="<?php $this->permalink(); ?>" aria-label="<?php $this->date('Y-m-d'); ?>">
            <span class="md-stamp-day"><?php echo md3e_e($day); ?></span>
            <span class="md-stamp-mon"><?php echo md3e_e($month); ?></span>
        </a>
    <?php endif; ?>

    <div>
        <div class="md-row-meta">
            <?php if ($firstCat): ?>
                <a class="md-tonal-badge" href="<?php echo md3e_e($firstCat['permalink']); ?>">
                    <?php echo md3e_e($firstCat['name']); ?>
                </a>
            <?php endif; ?>
            <?php if ($badge !== ''): ?>
                <span class="md-tonal-badge md-tonal-badge--accent"><?php echo md3e_e($badge); ?></span>
            <?php endif; ?>
            <time style="font-size:.78rem;font-weight:600;color:var(--md-on-surface-variant);" datetime="<?php $this->date('c'); ?>" itemprop="datePublished">
                <?php $this->date('Y-m-d'); ?>
            </time>
        </div>

        <h2 class="md-row-title" itemprop="name headline">
            <a itemprop="url" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
        </h2>

        <p class="md-row-excerpt" itemprop="description">
            <?php echo md3e_e(md3e_excerpt($this, $excerptLen)); ?>
        </p>

        <div class="md-row-foot">
            <span class="md-metric">
                <?php echo md3e_icon('clock'); ?>
                <span><?php echo $stats['minutes']; ?> min · <?php echo number_format($stats['words']); ?> 字</span>
            </span>
            <span class="md-metric">
                <?php echo md3e_icon('comment'); ?>
                <span><?php $this->commentsNum('0', '1', '%d'); ?></span>
            </span>
            <?php if (!empty($this->tags)): ?>
                <span style="opacity:.75;">
                    <?php
                    $shown = array_slice($this->tags, 0, 2);
                    foreach ($shown as $tg) {
                        echo '<span style="margin-right:8px;">#' . md3e_e($tg['name']) . '</span>';
                    }
                    ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($cover !== ''): ?>
        <a class="md-row-thumb" href="<?php $this->permalink(); ?>">
            <img src="<?php echo md3e_e($cover); ?>" alt="<?php $this->title(); ?>" loading="lazy">
        </a>
    <?php else: ?>
        <a class="md-row-go" href="<?php $this->permalink(); ?>" aria-label="<?php _e('阅读文章'); ?>">
            <?php echo md3e_icon('arrow-right'); ?>
        </a>
    <?php endif; ?>
</article>
<?php endif; ?>
