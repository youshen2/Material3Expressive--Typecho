<?php
/**
 * Material Design 3 Expressive (MD3E) Theme Entrypoint
 *
 * Modular architecture:
 * - inc/helpers.php : Option defaults, tonal color generator, reading stats & markup helpers
 * - inc/icons.php   : Inline rounded SVG icon library
 * - inc/config.php  : Typecho Admin themeConfig() & themeFields()
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/icons.php';
require_once __DIR__ . '/inc/config.php';
