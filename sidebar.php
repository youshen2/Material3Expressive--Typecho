<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$blocks = (array)md3e_opt($this->options, 'sidebarBlock');
$authorName = trim((string)md3e_opt($this->options, 'authorName')) ?: $this->options->title;
$authorRole = trim((string)md3e_opt($this->options, 'authorRole')) ?: _t('博主 · 独立创作者');
$authorBio = trim((string)md3e_opt($this->options, 'authorBio')) ?: $this->options->description;
$avatarUrl = trim((string)md3e_opt($this->options, 'avatarUrl'));
$socialLinks = md3e_social_links($this->options);
?>
<aside class="md-sidebar" role="complementary" aria-label="<?php _e('博客侧边栏'); ?>">
    <?php if (in_array('ShowAuthor', $blocks, true)): ?>
        <section class="md-author-mini">
            <div class="md-author-head">
                <div class="md-avatar-shape">
                    <?php if ($avatarUrl !== ''): ?>
                        <img src="<?php echo md3e_e($avatarUrl); ?>" alt="<?php echo md3e_e($authorName); ?>">
                    <?php else: ?>
                        <span><?php echo md3e_e(md3e_initial($authorName)); ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 class="md-author-name"><?php echo md3e_e($authorName); ?></h2>
                    <p class="md-author-sub"><?php echo md3e_e($authorRole); ?></p>
                </div>
            </div>
            <p class="md-author-bio"><?php echo md3e_e($authorBio); ?></p>
            <?php if (!empty($socialLinks)): ?>
                <div class="md-social-row">
                    <?php foreach ($socialLinks as $item): ?>
                        <a class="md-chip md-chip--tonal" href="<?php echo md3e_e($item['url']); ?>" target="_blank" rel="noopener noreferrer">
                            <span><?php echo md3e_e($item['label']); ?></span>
                            <?php echo md3e_icon('external'); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if (in_array('ShowCategory', $blocks, true)): ?>
        <section>
            <h3 class="md-side-section-label"><?php _e('文章分类'); ?></h3>
            <ul class="md-grouped-list">
                <?php \Widget\Metas\Category\Rows::alloc()->to($cats); ?>
                <?php while ($cats->next()): ?>
                    <li>
                        <a href="<?php $cats->permalink(); ?>">
                            <span><?php $cats->name(); ?></span>
                            <span class="md-count-pill"><?php $cats->count(); ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (in_array('ShowRecentPosts', $blocks, true)): ?>
        <section>
            <h3 class="md-side-section-label"><?php _e('最新文章'); ?></h3>
            <ul class="md-grouped-list">
                <?php \Widget\Contents\Post\Recent::alloc('pageSize=5')->to($recent); $rank = 1; ?>
                <?php while ($recent->next()): ?>
                    <li>
                        <a href="<?php $recent->permalink(); ?>" title="<?php $recent->title(); ?>" style="justify-content:flex-start;">
                            <span class="md-count-pill"><?php echo str_pad((string)$rank++, 2, '0', STR_PAD_LEFT); ?></span>
                            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php $recent->title(); ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (in_array('ShowTagCloud', $blocks, true)): ?>
        <?php \Widget\Metas\Tag\Cloud::alloc('sort=count&ignoreZeroCount=1&desc=1&limit=16')->to($tags); ?>
        <?php if ($tags->have()): ?>
            <section>
                <h3 class="md-side-section-label"><?php _e('标签胶囊'); ?></h3>
                <div class="md-tag-cloud">
                    <?php while ($tags->next()): ?>
                        <a class="md-chip" href="<?php $tags->permalink(); ?>"># <?php $tags->name(); ?></a>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (in_array('ShowRecentComments', $blocks, true)): ?>
        <?php \Widget\Comments\Recent::alloc('pageSize=4')->to($rc); ?>
        <?php if ($rc->have()): ?>
            <section>
                <h3 class="md-side-section-label"><?php _e('最近回复'); ?></h3>
                <ul class="md-grouped-list">
                    <?php while ($rc->next()): ?>
                        <li>
                            <a href="<?php $rc->permalink(); ?>" style="justify-content:flex-start;align-items:flex-start;">
                                <span class="md-mini-avatar"><?php echo md3e_e(md3e_initial($rc->author)); ?></span>
                                <span style="min-width:0;flex:1;">
                                    <span style="display:block;font-size:.83rem;font-weight:800;"><?php $rc->author(false); ?></span>
                                    <span class="md-comment-mini-text"><?php $rc->excerpt(36, '…'); ?></span>
                                </span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (in_array('ShowArchive', $blocks, true)): ?>
        <section>
            <h3 class="md-side-section-label"><?php _e('月度归档'); ?></h3>
            <ul class="md-grouped-list">
                <?php \Widget\Contents\Post\Date::alloc('type=month&format=Y年m月')
                    ->parse('<li><a href="{permalink}"><span>{date}</span><span class="md-count-pill">{count}</span></a></li>'); ?>
            </ul>
        </section>
    <?php endif; ?>
</aside>
