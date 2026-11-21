<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Crisp inline rounded SVG icons (Material Symbols Rounded aesthetic).
 */
function md3e_icon(string $name, string $class = 'md-icon'): string
{
    $paths = [
        'search'      => '<circle cx="11" cy="11" r="6.8" stroke="currentColor" stroke-width="2.2" fill="none"/><path d="M20 20l-3.8-3.8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        'sun'         => '<circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 2.5v2.2M12 19.3v2.2M4.9 4.9l1.6 1.6M17.5 17.5l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.9 19.1l1.6-1.6M17.5 6.5l1.6-1.6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'moon'        => '<path d="M20.2 14.6A8.5 8.5 0 0 1 9.4 3.8a8.5 8.5 0 1 0 10.8 10.8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>',
        'palette'     => '<path d="M12 3a9 9 0 1 0 0 18h1.5a2.5 2.5 0 0 0 0-5H13a1.5 1.5 0 0 1-1.5-1.5c0-.8.7-1.5 1.5-1.5h3a5 5 0 0 0 5-5 5 5 0 0 0-9-5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/><circle cx="7.5" cy="10.5" r="1.2" fill="currentColor"/><circle cx="10.5" cy="7.5" r="1.2" fill="currentColor"/><circle cx="15" cy="7.8" r="1.2" fill="currentColor"/>',
        'arrow-up'    => '<path d="M12 19V5M6 11l6-6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>',
        'arrow-left'  => '<path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>',
        'close'       => '<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        'calendar'    => '<rect x="3" y="5" width="18" height="16" rx="3" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'clock'       => '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'folder'      => '<path d="M3 7a2 2 0 0 1 2-2h4.2l2 2H19a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>',
        'tag'         => '<path d="M20 12.6 12.6 20a2 2 0 0 1-2.8 0L3 13.2V5a2 2 0 0 1 2-2h8.2L20 9.8a2 2 0 0 1 0 2.8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/><circle cx="7.5" cy="7.5" r="1.3" fill="currentColor"/>',
        'comment'     => '<path d="M4 6a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H9l-5 4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>',
        'spark'       => '<path d="M12 2.5c.8 4.6 4.9 8.7 9.5 9.5-4.6.8-8.7 4.9-9.5 9.5-.8-4.6-4.9-8.7-9.5-9.5 4.6-.8 8.7-4.9 9.5-9.5Z" fill="currentColor"/>',
        'article'     => '<rect x="4" y="3.5" width="16" height="17" rx="3" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 8.5h8M8 12.5h8M8 16.5h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'archive'     => '<rect x="3" y="4" width="18" height="4.5" rx="1.8" stroke="currentColor" stroke-width="2" fill="none"/><path d="M5 8.5V18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8.5M10 13h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'info'        => '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 11v5M12 8h.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        'toc'         => '<path d="M4 6h16M4 12h11M4 18h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        'home'        => '<path d="M4 10.5 12 4l8 6.5V19a1.5 1.5 0 0 1-1.5 1.5h-4.5V15a2 2 0 0 0-4 0v5.5H5.5A1.5 1.5 0 0 1 4 19v-8.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>',
        'rss'         => '<circle cx="6" cy="18" r="2" fill="currentColor"/><path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>',
        'link'        => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1.5 1.5M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1.5-1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>',
        'text-size'   => '<path d="M4 7V5h11v2M9.5 5v14M7 19h5M15 12h6M18 12v7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>',
        'admin'       => '<circle cx="12" cy="8.5" r="3.5" stroke="currentColor" stroke-width="2" fill="none"/><path d="M5 19.5c.8-3.4 3.5-5 7-5s6.2 1.6 7 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>',
        'external'    => '<path d="M14 5h5v5M19 5l-9 9M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>',
    ];

    $inner = $paths[$name] ?? $paths['spark'];
    return '<svg class="' . md3e_e($class) . '" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">' . $inner . '</svg>';
}
