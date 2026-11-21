<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Custom MD3E threaded comment renderer
 */
function threadedComments($comments, $options)
{
    $isAuthor = ($comments->authorId && $comments->authorId == $comments->ownerId);
    $itemClass = 'md-comment-item';
    if ($comments->levels > 0) {
        $itemClass .= ' comment-child';
    } else {
        $itemClass .= ' comment-parent';
    }
    if ($isAuthor) {
        $itemClass .= ' comment-by-author';
    }
?>
    <li id="<?php $comments->theId(); ?>" class="<?php echo $itemClass; ?>" itemscope itemtype="http://schema.org/UserComments">
        <div class="md-comment-card">
            <div class="md-comment-header">
                <div class="md-comment-author-wrap">
                    <span class="md-comment-avatar" aria-hidden="true">
                        <?php echo md3e_e(md3e_initial($comments->author)); ?>
                    </span>
                    <div>
                        <span class="md-comment-author" itemprop="creator">
                            <?php $comments->author(); ?>
                            <?php if ($isAuthor): ?>
                                <span class="md-author-tag"><?php _e('博主'); ?></span>
                            <?php endif; ?>
                        </span>
                        <time class="md-comment-time" datetime="<?php $comments->date('c'); ?>" itemprop="commentTime">
                            <?php $comments->date('Y-m-d H:i'); ?>
                            <?php if ('approved' !== $comments->status): ?>
                                · <em><?php _e('审核中'); ?></em>
                            <?php endif; ?>
                        </time>
                    </div>
                </div>

                <div class="md-comment-reply">
                    <?php $comments->reply(_t('回复')); ?>
                </div>
            </div>

            <div class="md-comment-content" itemprop="commentText">
                <?php $comments->content(); ?>
            </div>
        </div>

        <?php if ($comments->children): ?>
            <div class="md-comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php endif; ?>
    </li>
<?php
}
?>

<section id="comments" class="md-comments-section" aria-label="<?php _e('评论区'); ?>">
    <?php $this->comments()->to($comments); ?>

    <h2 class="md-comments-heading">
        <?php echo md3e_icon('comment'); ?>
        <span><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('已有 %d 条评论')); ?></span>
    </h2>

    <?php if ($comments->have()): ?>
        <?php $comments->listComments(['before' => '<ol class="md-comment-list">', 'after' => '</ol>']); ?>
        <div class="md-pagination" style="margin-top:0;margin-bottom:24px;">
            <?php $comments->pageNav('←', '→'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="md-respond">
            <div class="md-respond-head">
                <h3 id="response" class="md-respond-title"><?php _e('留下你的想法'); ?></h3>
                <div class="cancel-comment-reply">
                    <?php $comments->cancelReply(_t('取消回复')); ?>
                </div>
            </div>

            <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form" role="form">
                <?php if ($this->user->hasLogin()): ?>
                    <p style="margin:0 0 14px;font-size:.9rem;color:var(--md-on-surface-variant);">
                        <?php _e('当前登录'); ?>：
                        <a href="<?php $this->options->profileUrl(); ?>" style="font-weight:700;"><?php $this->user->screenName(); ?></a>
                        · <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a>
                    </p>
                <?php else: ?>
                    <div class="md-form-grid">
                        <div class="md-field">
                            <label for="author"><?php _e('称呼 *'); ?></label>
                            <input type="text" name="author" id="author" class="md-input"
                                   value="<?php $this->remember('author'); ?>" placeholder="<?php _e('怎么称呼你'); ?>" required>
                        </div>
                        <div class="md-field">
                            <label for="mail"><?php _e('邮箱'); ?><?php if ($this->options->commentsRequireMail): ?> *<?php endif; ?></label>
                            <input type="email" name="mail" id="mail" class="md-input"
                                   value="<?php $this->remember('mail'); ?>" placeholder="name@example.com"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                        </div>
                        <div class="md-field">
                            <label for="url"><?php _e('网站'); ?><?php if ($this->options->commentsRequireUrl): ?> *<?php endif; ?></label>
                            <input type="url" name="url" id="url" class="md-input"
                                   value="<?php $this->remember('url'); ?>" placeholder="https://"<?php if ($this->options->commentsRequireUrl): ?> required<?php endif; ?>>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="md-field">
                    <label for="textarea"><?php _e('评论内容 *'); ?></label>
                    <textarea name="text" id="textarea" class="md-textarea" placeholder="<?php _e('写点什么与大家分享吧…'); ?>" required><?php $this->remember('text'); ?></textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="md-btn md-btn--filled">
                        <?php echo md3e_icon('spark'); ?>
                        <span><?php _e('发布评论'); ?></span>
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p style="margin:0;color:var(--md-on-surface-variant);"><?php _e('本文评论区已关闭。'); ?></p>
    <?php endif; ?>
</section>
