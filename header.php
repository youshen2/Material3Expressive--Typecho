<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$defaultPalette = (string)md3e_opt($this->options, 'defaultPalette');
$customSeedColor = md3e_safe_hex((string)md3e_opt($this->options, 'customSeedColor'));
$defaultMode = (string)md3e_opt($this->options, 'defaultThemeMode');
$allowVisitorPalette = ((string)md3e_opt($this->options, 'allowVisitorPalette') === '1');
$shapeStyle = (string)md3e_opt($this->options, 'shapeStyle');
$enableWavy = ((string)md3e_opt($this->options, 'enableWavy') === '1');
$enableRipple = ((string)md3e_opt($this->options, 'enableRipple') === '1');
$defaultFontSize = (string)md3e_opt($this->options, 'defaultFontSize');
$faviconUrl = trim((string)md3e_opt($this->options, 'faviconUrl'));
$logoUrl = trim((string)md3e_opt($this->options, 'logoUrl'));
$showAdminInRail = ((string)md3e_opt($this->options, 'showAdminInRail') === '1');
$railExtraLinks = md3e_parse_links((string)md3e_opt($this->options, 'railExtraLinks'));
$customCss = trim((string)md3e_opt($this->options, 'customCss'));
$customHeadCode = trim((string)md3e_opt($this->options, 'customHeadCode'));

// Collect standalone pages once for rail & mobile dock
$navPages = [];
\Widget\Contents\Page\Rows::alloc()->to($pagesWidget);
while ($pagesWidget->next()) {
    $navPages[] = [
        'slug'      => $pagesWidget->slug,
        'title'     => $pagesWidget->title,
        'permalink' => $pagesWidget->permalink,
    ];
}
?>
<!DOCTYPE html>
<html lang="zh-CN"
      data-palette="<?php echo md3e_e($defaultPalette); ?>"
      data-theme="light"
      data-shape="<?php echo md3e_e($shapeStyle); ?>"
      data-ripple="<?php echo $enableRipple ? '1' : '0'; ?>"
      data-default-font="<?php echo md3e_e($defaultFontSize); ?>">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <?php if ($faviconUrl !== ''): ?>
        <link rel="icon" href="<?php echo md3e_e($faviconUrl); ?>">
    <?php endif; ?>
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s'),
            'search'   => _t('搜索 %s'),
            'tag'      => _t('标签 %s'),
            'author'   => _t('%s 的文章'),
            'date'     => _t('归档 %s'),
        ], '', ' · '); ?><?php $this->options->title(); ?></title>

    <script>
    (function () {
        var root = document.documentElement;
        var defPalette = <?php echo json_encode($defaultPalette); ?>;
        var defMode = <?php echo json_encode($defaultMode); ?>;
        var allowVisitor = <?php echo $allowVisitorPalette ? 'true' : 'false'; ?>;
        try {
            var savedPalette = allowVisitor ? localStorage.getItem('md3e_color_palette') : null;
            var savedTheme = localStorage.getItem('md3e_theme_mode');
            root.setAttribute('data-palette', savedPalette || defPalette);
            if (savedTheme === 'dark' || savedTheme === 'light') {
                root.setAttribute('data-theme', savedTheme);
            } else if (defMode === 'dark' || defMode === 'light') {
                root.setAttribute('data-theme', defMode);
            } else {
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                root.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            }
        } catch (e) {}
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=JetBrains+Mono:wght@500;700&family=Noto+Sans+SC:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/md3e.css'); ?>">
    <style><?php echo md3e_custom_palette_css($customSeedColor); ?></style>
    <?php if ($customCss !== ''): ?>
        <style><?php echo $customCss; ?></style>
    <?php endif; ?>
    <?php if ($customHeadCode !== ''): ?>
        <?php echo $customHeadCode; ?>
    <?php endif; ?>
    <?php $this->header(); ?>
</head>
<body>

<!-- Material 3 Expressive 6-Shape Organic ClipPath Library -->
<svg width="0" height="0" style="position:absolute;pointer-events:none;" aria-hidden="true">
    <defs>
        <clipPath id="md3e-shape-clover" clipPathUnits="objectBoundingBox">
            <path d="M0.5,0 C0.69,0 0.77,0.12 0.82,0.18 C0.88,0.23 1,0.31 1,0.5 C1,0.69 0.88,0.77 0.82,0.82 C0.77,0.88 0.69,1 0.5,1 C0.31,1 0.23,0.88 0.18,0.82 C0.12,0.77 0,0.69 0,0.5 C0,0.31 0.12,0.23 0.18,0.18 C0.23,0.12 0.31,0 0.5,0 Z"/>
        </clipPath>
        <clipPath id="md3e-shape-cookie9" clipPathUnits="objectBoundingBox">
            <path d="M0.50,0.01 C0.58,0.01 0.62,0.09 0.69,0.12 C0.76,0.15 0.85,0.12 0.89,0.18 C0.94,0.24 0.90,0.33 0.93,0.40 C0.96,0.46 1.00,0.50 0.99,0.57 C0.98,0.65 0.91,0.68 0.88,0.75 C0.85,0.82 0.87,0.90 0.81,0.94 C0.75,0.98 0.66,0.92 0.59,0.95 C0.52,0.98 0.48,1.00 0.41,0.99 C0.33,0.98 0.31,0.90 0.24,0.87 C0.17,0.84 0.09,0.86 0.05,0.80 C0.01,0.74 0.07,0.65 0.04,0.58 C0.01,0.51 0.00,0.46 0.01,0.39 C0.03,0.31 0.11,0.29 0.14,0.22 C0.17,0.15 0.15,0.07 0.21,0.04 C0.28,0.00 0.36,0.07 0.43,0.04 C0.46,0.02 0.48,0.01 0.50,0.01 Z"/>
        </clipPath>
        <clipPath id="md3e-shape-sunny" clipPathUnits="objectBoundingBox">
            <path d="M0.50,0.00 C0.57,0.00 0.60,0.11 0.66,0.13 C0.72,0.15 0.81,0.08 0.86,0.14 C0.92,0.19 0.85,0.28 0.87,0.34 C0.89,0.40 1.00,0.43 1.00,0.50 C1.00,0.57 0.89,0.60 0.87,0.66 C0.85,0.72 0.92,0.81 0.86,0.86 C0.81,0.92 0.72,0.85 0.66,0.87 C0.60,0.89 0.57,1.00 0.50,1.00 C0.43,1.00 0.40,0.89 0.34,0.87 C0.28,0.85 0.19,0.92 0.14,0.86 C0.08,0.81 0.15,0.72 0.13,0.66 C0.11,0.60 0.00,0.57 0.00,0.50 C0.00,0.43 0.11,0.40 0.13,0.34 C0.15,0.28 0.08,0.19 0.14,0.14 C0.19,0.08 0.28,0.15 0.34,0.13 C0.40,0.11 0.43,0.00 0.50,0.00 Z"/>
        </clipPath>
        <clipPath id="md3e-shape-arch" clipPathUnits="objectBoundingBox">
            <path d="M0.02,0.50 C0.02,0.22 0.23,0.02 0.50,0.02 C0.77,0.02 0.98,0.22 0.98,0.50 L0.98,0.86 C0.98,0.94 0.92,0.98 0.84,0.98 L0.16,0.98 C0.08,0.98 0.02,0.94 0.02,0.86 Z"/>
        </clipPath>
        <clipPath id="md3e-shape-gem" clipPathUnits="objectBoundingBox">
            <path d="M0.50,0.02 C0.62,0.02 0.98,0.38 0.98,0.50 C0.98,0.62 0.62,0.98 0.50,0.98 C0.38,0.98 0.02,0.62 0.02,0.50 C0.02,0.38 0.38,0.02 0.50,0.02 Z"/>
        </clipPath>
        <clipPath id="md3e-shape-flower" clipPathUnits="objectBoundingBox">
            <path d="M0.50,0.02 C0.61,0.02 0.64,0.13 0.72,0.15 C0.80,0.17 0.90,0.12 0.95,0.22 C0.99,0.32 0.91,0.40 0.94,0.50 C0.91,0.60 0.99,0.68 0.95,0.78 C0.90,0.88 0.80,0.83 0.72,0.85 C0.64,0.87 0.61,0.98 0.50,0.98 C0.39,0.98 0.36,0.87 0.28,0.85 C0.20,0.83 0.10,0.88 0.05,0.78 C0.01,0.68 0.09,0.60 0.06,0.50 C0.09,0.40 0.01,0.32 0.05,0.22 C0.10,0.12 0.20,0.17 0.28,0.15 C0.36,0.13 0.39,0.02 0.50,0.02 Z"/>
        </clipPath>
    </defs>
</svg>

<?php if ($this->is('post') || $this->is('page')): ?>
<div class="md-wavy-progress<?php echo $enableWavy ? '' : ' is-linear'; ?>" aria-hidden="true">
    <div id="md-reading-progress" class="md-wavy-progress-fill">
        <?php if ($enableWavy): ?>
            <svg viewBox="0 0 1200 10" preserveAspectRatio="none">
                <path d="M0,5 Q15,1 30,5 T60,5 T90,5 T120,5 T150,5 T180,5 T210,5 T240,5 T270,5 T300,5 T330,5 T360,5 T390,5 T420,5 T450,5 T480,5 T510,5 T540,5 T570,5 T600,5 T630,5 T660,5 T690,5 T720,5 T750,5 T780,5 T810,5 T840,5 T870,5 T900,5 T930,5 T960,5 T990,5 T1020,5 T1050,5 T1080,5 T1110,5 T1140,5 T1170,5 T1200,5"
                      fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"/>
            </svg>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="md-app-shell">
    <!-- Persistent Material 3 Navigation Rail (Desktop) -->
    <aside class="md-nav-rail" aria-label="<?php _e('主导航栏'); ?>">
        <a class="md-rail-brand" href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title(); ?>">
            <?php if ($logoUrl !== ''): ?>
                <img src="<?php echo md3e_e($logoUrl); ?>" alt="<?php $this->options->title(); ?>">
            <?php else: ?>
                <span><?php echo md3e_e(md3e_initial($this->options->title)); ?></span>
            <?php endif; ?>
        </a>

        <button type="button" class="md-rail-fab" data-open-search title="<?php _e('搜索文章 (⌘K)'); ?>" aria-label="<?php _e('搜索文章'); ?>">
            <?php echo md3e_icon('search'); ?>
        </button>

        <nav class="md-rail-nav">
            <a class="md-rail-item<?php if ($this->is('index') || $this->is('post') || $this->is('archive')): ?> is-active<?php endif; ?>"
               href="<?php $this->options->siteUrl(); ?>">
                <span class="md-rail-pill"><?php echo md3e_icon('article'); ?></span>
                <span><?php _e('文章'); ?></span>
            </a>

            <?php foreach ($navPages as $p):
                $icon = ($p['slug'] === 'archives') ? 'archive' : 'info';
            ?>
                <a class="md-rail-item<?php if ($this->is('page', $p['slug'])): ?> is-active<?php endif; ?>"
                   href="<?php echo md3e_e($p['permalink']); ?>">
                    <span class="md-rail-pill"><?php echo md3e_icon($icon); ?></span>
                    <span><?php echo md3e_e($p['title']); ?></span>
                </a>
            <?php endforeach; ?>

            <?php foreach ($railExtraLinks as $ext):
                $isExternal = (strpos($ext['url'], 'http://') === 0 || strpos($ext['url'], 'https://') === 0);
            ?>
                <a class="md-rail-item"
                   href="<?php echo md3e_e($ext['url']); ?>"<?php echo $isExternal ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <span class="md-rail-pill"><?php echo md3e_icon($isExternal ? 'external' : 'link'); ?></span>
                    <span><?php echo md3e_e($ext['label']); ?></span>
                </a>
            <?php endforeach; ?>

            <?php if ($showAdminInRail): ?>
                <a class="md-rail-item" href="<?php echo $this->user->hasLogin() ? md3e_e($this->options->adminUrl) : md3e_e($this->options->adminUrl . 'login.php'); ?>">
                    <span class="md-rail-pill"><?php echo md3e_icon('admin'); ?></span>
                    <span><?php echo $this->user->hasLogin() ? _t('后台') : _t('登录'); ?></span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="md-rail-footer">
            <button type="button" class="md-rail-icon-btn" data-theme-toggle title="<?php _e('切换深浅色'); ?>" aria-label="<?php _e('切换深浅色'); ?>">
                <span class="icon-sun" style="display:none;"><?php echo md3e_icon('sun'); ?></span>
                <span class="icon-moon"><?php echo md3e_icon('moon'); ?></span>
            </button>

            <?php if ($allowVisitorPalette): ?>
                <button type="button" class="md-rail-icon-btn" data-toggle-palette aria-expanded="false" aria-controls="md-palette-sheet" title="<?php _e('切换 MD3E 动态配色'); ?>" aria-label="<?php _e('切换动态配色'); ?>">
                    <?php echo md3e_icon('palette'); ?>
                </button>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Blog Stage -->
    <div class="md-stage">
        <div class="md-stage-inner">
            <!-- Mobile Compact Header -->
            <div class="md-mobile-bar">
                <a class="md-mobile-brand" href="<?php $this->options->siteUrl(); ?>">
                    <span class="md-rail-brand" style="width:36px;height:36px;font-size:.95rem;"><?php echo md3e_e(md3e_initial($this->options->title)); ?></span>
                    <span><?php $this->options->title(); ?></span>
                </a>
                <div style="display:flex;gap:8px;">
                    <button type="button" class="md-rail-icon-btn" data-theme-toggle aria-label="<?php _e('切换深浅色'); ?>">
                        <span class="icon-sun" style="display:none;"><?php echo md3e_icon('sun'); ?></span>
                        <span class="icon-moon"><?php echo md3e_icon('moon'); ?></span>
                    </button>
                    <button type="button" class="md-rail-icon-btn" data-open-search aria-label="<?php _e('搜索'); ?>">
                        <?php echo md3e_icon('search'); ?>
                    </button>
                </div>
            </div>
