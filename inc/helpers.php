<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Single source of truth for all MD3E theme option defaults.
 */
function md3e_defaults(): array
{
    return [
        // Tab 1: Appearance & Motion
        'defaultPalette'      => 'berry',
        'customSeedColor'     => '#9B2362',
        'defaultThemeMode'    => 'system',
        'allowVisitorPalette' => '1',
        'shapeStyle'          => 'expressive',
        'enableWavy'          => '1',
        'enableRipple'        => '1',

        // Tab 2: Brand & Author
        'logoUrl'             => '',
        'faviconUrl'          => '',
        'mastheadTitle'       => '',
        'mastheadDesc'        => '',
        'showStatsPill'       => '1',
        'authorName'          => 'Luoxe',
        'authorRole'          => '博主 · 独立创作者',
        'authorBio'           => '热爱交互设计、前端工程与极简系统构建，在这里记录技术与设计切片。',
        'avatarUrl'           => '',
        'socialLinks'         => "GitHub|https://github.com\nRSS|/feed/",

        // Tab 3: Home & List Layout
        'leadPostStyle'       => 'highlight',
        'showCategoryBar'     => '1',
        'showDateStamp'       => '1',
        'excerptLength'       => '110',
        'sidebarPosition'     => 'right',
        'sidebarBlock'        => ['ShowAuthor', 'ShowCategory', 'ShowTagCloud', 'ShowRecentComments'],

        // Tab 4: Reading Experience
        'defaultFontSize'     => 'normal',
        'showToc'             => '1',
        'showFloatingToolbar' => '1',
        'showPostMeta'        => ['author', 'date', 'readingTime', 'comments', 'tags', 'nearPosts'],
        'copyrightNotice'     => '本文由 {author} 原创发布，转载请注明出处：{title}（{url}）。',

        // Tab 5: Navigation & Footer
        'railExtraLinks'      => '',
        'showAdminInRail'     => '1',
        'footerText'          => 'Material 3 Expressive · Typecho 1.3',
        'icpNumber'           => '',
        'icpUrl'              => 'https://beian.miit.gov.cn/',

        // Tab 6: Advanced Custom Code
        'customCss'           => '',
        'customHeadCode'      => '',
        'customFooterCode'    => '',
    ];
}

/**
 * Safely read a theme option with automatic fallback to md3e_defaults().
 */
function md3e_opt($options, string $key, $overrideDefault = null)
{
    $defaults = md3e_defaults();
    $default = ($overrideDefault !== null) ? $overrideDefault : ($defaults[$key] ?? '');

    if (is_object($options) && isset($options->{$key})) {
        $val = $options->{$key};
        if (is_array($default)) {
            return is_array($val) ? $val : $default;
        }
        if ($val !== null) {
            return $val;
        }
    }
    return $default;
}

/**
 * Escape HTML special characters safely.
 */
function md3e_e(?string $str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and normalize a 6-digit hex color string.
 */
function md3e_safe_hex(?string $hex, string $fallback = '#9B2362'): string
{
    $hex = trim((string)$hex);
    if (preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
        return strtoupper($hex);
    }
    return $fallback;
}

/**
 * Generate full Light & Dark MD3E tonal palette CSS from any custom seed color.
 */
function md3e_custom_palette_css(string $seed): string
{
    $s = md3e_safe_hex($seed);
    return <<<CSS
[data-palette="custom"][data-theme="light"] {
  --md-primary: {$s};
  --md-on-primary: #FFFFFF;
  --md-primary-container: color-mix(in srgb, {$s} 18%, #FFFFFF);
  --md-on-primary-container: color-mix(in srgb, {$s} 82%, #000000);
  --md-secondary: color-mix(in srgb, {$s} 45%, #555555);
  --md-on-secondary: #FFFFFF;
  --md-secondary-container: color-mix(in srgb, {$s} 13%, #F6F4F7);
  --md-on-secondary-container: color-mix(in srgb, {$s} 60%, #151515);
  --md-tertiary: color-mix(in srgb, {$s} 55%, #C97A00);
  --md-on-tertiary: #FFFFFF;
  --md-tertiary-container: color-mix(in srgb, {$s} 12%, #FFF0D4);
  --md-on-tertiary-container: #2B1700;
  --md-surface: color-mix(in srgb, {$s} 5%, #F8F7FA);
  --md-surface-container-lowest: #FFFFFF;
  --md-surface-container-low: color-mix(in srgb, {$s} 3%, #FFFFFF);
  --md-surface-container: color-mix(in srgb, {$s} 8%, #F4F2F6);
  --md-surface-container-high: color-mix(in srgb, {$s} 11%, #EFECEF);
  --md-surface-container-highest: color-mix(in srgb, {$s} 14%, #E8E5EA);
  --md-on-surface: #1E1B1F;
  --md-on-surface-variant: #4E4750;
  --md-outline: #7F7681;
  --md-outline-variant: color-mix(in srgb, {$s} 18%, #CFCBD2);
  --md-inverse-surface: #332F35;
  --md-inverse-on-surface: #F6F1F8;
}
[data-palette="custom"][data-theme="dark"] {
  --md-primary: color-mix(in srgb, {$s} 48%, #FFFFFF);
  --md-on-primary: color-mix(in srgb, {$s} 88%, #000000);
  --md-primary-container: color-mix(in srgb, {$s} 74%, #000000);
  --md-on-primary-container: color-mix(in srgb, {$s} 22%, #FFFFFF);
  --md-secondary: color-mix(in srgb, {$s} 28%, #D8D4DE);
  --md-on-secondary: #26222B;
  --md-secondary-container: color-mix(in srgb, {$s} 32%, #2C2932);
  --md-on-secondary-container: color-mix(in srgb, {$s} 18%, #F5F2F8);
  --md-tertiary: #F8BD68;
  --md-on-tertiary: #442B00;
  --md-tertiary-container: #613F00;
  --md-on-tertiary-container: #FFDFB5;
  --md-surface: color-mix(in srgb, {$s} 6%, #111014);
  --md-surface-container-lowest: #0C0B0E;
  --md-surface-container-low: color-mix(in srgb, {$s} 8%, #18161C);
  --md-surface-container: color-mix(in srgb, {$s} 10%, #1F1C23);
  --md-surface-container-high: color-mix(in srgb, {$s} 13%, #29252E);
  --md-surface-container-highest: color-mix(in srgb, {$s} 16%, #34303A);
  --md-on-surface: #EAE4EE;
  --md-on-surface-variant: #CBC3D0;
  --md-outline: #948C99;
  --md-outline-variant: #4B4550;
  --md-inverse-surface: #EAE4EE;
  --md-inverse-on-surface: #332F35;
}
CSS;
}

/**
 * Count CJK characters + Latin words and estimate reading duration.
 */
function md3e_reading_stats(string $content): array
{
    $plain = trim(strip_tags($content));
    $plain = preg_replace('/\s+/u', ' ', $plain);

    preg_match_all('/[\x{4e00}-\x{9fff}\x{3400}-\x{4dbf}\x{3040}-\x{30ff}\x{ac00}-\x{d7af}]/u', $plain, $cjkMatches);
    $cjkCount = count($cjkMatches[0]);

    $withoutCjk = preg_replace('/[\x{4e00}-\x{9fff}\x{3400}-\x{4dbf}\x{3040}-\x{30ff}\x{ac00}-\x{d7af}]/u', ' ', $plain);
    preg_match_all('/[A-Za-z0-9]+/u', $withoutCjk, $wordMatches);
    $latinCount = count($wordMatches[0]);

    $total = $cjkCount + $latinCount;
    $minutes = max(1, (int)ceil($total / 320));

    return ['words' => $total, 'minutes' => $minutes];
}

/**
 * Generate a clean plain-text excerpt from a post (prefers custom subtitle field).
 */
function md3e_excerpt($post, int $length = 110): string
{
    if (!empty($post->fields->subtitle)) {
        return trim((string)$post->fields->subtitle);
    }

    $length = max(40, min(300, $length));
    $text = strip_tags((string)$post->content);
    $text = preg_replace('/\s+/u', ' ', trim($text));
    if (mb_strlen($text, 'UTF-8') > $length) {
        return mb_substr($text, 0, $length, 'UTF-8') . '…';
    }
    return $text;
}

/**
 * Fetch cached site-wide counts for published posts, categories, and comments.
 */
function md3e_site_stats(): array
{
    static $stats = null;
    if ($stats !== null) {
        return $stats;
    }

    try {
        $db = \Typecho\Db::get();
        $posts = $db->fetchObject(
            $db->select(['COUNT(cid)' => 'num'])
                ->from('table.contents')
                ->where('type = ?', 'post')
                ->where('status = ?', 'publish')
        );
        $categories = $db->fetchObject(
            $db->select(['COUNT(mid)' => 'num'])
                ->from('table.metas')
                ->where('type = ?', 'category')
        );
        $comments = $db->fetchObject(
            $db->select(['COUNT(coid)' => 'num'])
                ->from('table.comments')
                ->where('status = ?', 'approved')
        );
        $stats = [
            'posts'      => (int)($posts->num ?? 0),
            'categories' => (int)($categories->num ?? 0),
            'comments'   => (int)($comments->num ?? 0),
        ];
    } catch (\Throwable $e) {
        $stats = ['posts' => 0, 'categories' => 0, 'comments' => 0];
    }

    return $stats;
}

/**
 * Parse newline-separated "Label|URL" pairs.
 */
function md3e_parse_links(?string $raw): array
{
    $raw = trim((string)$raw);
    if ($raw === '') {
        return [];
    }

    $links = [];
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '|') === false) {
            continue;
        }
        [$label, $url] = array_map('trim', explode('|', $line, 2));
        if ($label !== '' && $url !== '') {
            $links[] = ['label' => $label, 'url' => $url];
        }
    }
    return $links;
}

/**
 * Return configured social links for the author sidebar card.
 */
function md3e_social_links($options): array
{
    return md3e_parse_links((string)md3e_opt($options, 'socialLinks'));
}

/**
 * Cycle through 6 Material 3 Expressive organic shapes for post date stamps.
 */
function md3e_shape_class(int $cid): string
{
    $shapes = [
        'shape-cookie9',
        'shape-clover',
        'shape-sunny',
        'shape-arch',
        'shape-gem',
        'shape-flower',
    ];
    return $shapes[$cid % count($shapes)];
}

/**
 * Cycle tonal container roles for post date stamps.
 */
function md3e_tone_class(int $cid): string
{
    $tones = ['tone-primary', 'tone-tertiary', 'tone-secondary'];
    return $tones[$cid % count($tones)];
}

/**
 * Extract uppercase initial / first character for monogram avatars.
 */
function md3e_initial(?string $name): string
{
    $name = trim((string)$name);
    if ($name === '') {
        return 'M';
    }
    return mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
}

/**
 * Render an MD3E wavy (or linear fallback) horizontal divider.
 */
function md3e_wavy_divider(string $class = 'md-wavy-divider', bool $enabled = true): string
{
    if (!$enabled) {
        return '<div class="' . md3e_e($class) . ' is-linear" aria-hidden="true"></div>';
    }
    return '<div class="' . md3e_e($class) . '" aria-hidden="true">'
        . '<svg viewBox="0 0 240 12" preserveAspectRatio="none">'
        . '<path d="M0,6 Q10,1 20,6 T40,6 T60,6 T80,6 T100,6 T120,6 T140,6 T160,6 T180,6 T200,6 T220,6 T240,6" '
        . 'fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>'
        . '</svg></div>';
}
