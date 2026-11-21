<?php
/**
 * 归档时间轴
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<main class="md-blog-grid md-blog-grid--single" style="padding-top:8px;">
    <article class="md-article-sheet">
        <nav class="md-breadcrumb" aria-label="<?php _e('面包屑导航'); ?>">
            <a href="<?php $this->options->siteUrl(); ?>"><?php _e('文章'); ?></a>
            <span>/</span>
            <span><?php $this->title(); ?></span>
        </nav>

        <h1 class="md-article-title"><?php $this->title(); ?></h1>
        <p class="md-article-subtitle"><?php _e('按年份整理的全部博客文章时间轴。'); ?></p>

        <?php echo md3e_wavy_divider('md-wavy-divider', (string)md3e_opt($this->options, 'enableWavy') === '1'); ?>

        <div>
            <?php
            \Widget\Contents\Post\Recent::alloc('pageSize=500')->to($archives);
            $currentYear = null;
            $opened = false;
            while ($archives->next()):
                $year = date('Y', $archives->created);
                if ($year !== $currentYear):
                    if ($opened) {
                        echo '</ul>';
                    }
                    $currentYear = $year;
                    $opened = true;
                    echo '<div class="md-timeline-year">' . md3e_icon('calendar') . '<span>' . md3e_e($year) . ' 年</span></div>';
                    echo '<ul class="md-timeline-list">';
                endif;
                ?>
                <li class="md-timeline-item">
                    <a href="<?php $archives->permalink(); ?>">
                        <span class="md-timeline-date"><?php echo date('m-d', $archives->created); ?></span>
                        <span style="font-weight:700;"><?php $archives->title(); ?></span>
                    </a>
                </li>
            <?php endwhile;
            if ($opened) {
                echo '</ul>';
            }
            ?>
        </div>
    </article>
</main>

<?php $this->need('footer.php'); ?>
