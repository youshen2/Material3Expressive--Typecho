<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Typecho Admin -> Appearance -> Settings (themeConfig)
 */
function themeConfig($form)
{
    $defaults = md3e_defaults();
    $options = \Widget\Options::alloc();
    $themeUrl = rtrim($options->themeUrl('', 'md3e'), '/');

    $add = function (\Typecho\Widget\Helper\Form\Element $el, string $tab) use ($form) {
        $el->setAttribute('data-md3e-tab', $tab);
        $form->addInput($el);
    };

    $headerLayout = new \Typecho\Widget\Helper\Layout('div', ['class' => 'md3e-admin-root']);
    $headerHtml = '<link rel="stylesheet" href="' . md3e_e($themeUrl . '/assets/admin/settings.css') . '">'
        . '<div class="md3e-admin-tabs" role="tablist">'
        . '  <button type="button" class="md3e-tab-btn is-active" data-tab="appearance">色彩与动效</button>'
        . '  <button type="button" class="md3e-tab-btn" data-tab="brand">品牌与博主</button>'
        . '  <button type="button" class="md3e-tab-btn" data-tab="layout">首页与布局</button>'
        . '  <button type="button" class="md3e-tab-btn" data-tab="reading">阅读体验</button>'
        . '  <button type="button" class="md3e-tab-btn" data-tab="navigation">导航与页脚</button>'
        . '  <button type="button" class="md3e-tab-btn" data-tab="advanced">高级与备份</button>'
        . '</div>'
        . '<div class="md3e-tab-panel is-active" data-panel="appearance"><div class="md3e-panel-intro"><strong>MD3E 色彩与动效：</strong>配置站点默认色彩种子、自定义品牌主色、明暗策略与 Expressive 形态强度。</div></div>'
        . '<div class="md3e-tab-panel" data-panel="brand"><div class="md3e-panel-intro"><strong>品牌与博主信息：</strong>定制站点徽标、首页报头文案以及右侧紧凑博主名片与社交链接。</div></div>'
        . '<div class="md3e-tab-panel" data-panel="layout"><div class="md3e-panel-intro"><strong>首页与列表排版：</strong>控制首篇高亮导读卡、连接式分类筛选条、日期几何戳、摘要长度与侧边栏模块。</div></div>'
        . '<div class="md3e-tab-panel" data-panel="reading"><div class="md3e-panel-intro"><strong>文章与阅读体验：</strong>定制默认正文字号、右侧自动目录 TOC、底部浮动工具栏、文章元数据与文末版权声明。</div></div>'
        . '<div class="md3e-tab-panel" data-panel="navigation"><div class="md3e-panel-intro"><strong>导航栏与页脚：</strong>向左侧 Navigation Rail 追加自定义链接，并设置页脚文字与备案号。</div></div>'
        . '<div class="md3e-tab-panel" data-panel="advanced"><div class="md3e-panel-intro"><strong>高级代码与配置备份：</strong>注入自定义 CSS/统计脚本，或一键导出/导入 JSON 配置与恢复出厂默认。</div>'
        . '  <div class="md3e-panel-end"></div>'
        . '  <div class="md3e-backup-card">'
        . '    <h3>配置备份、导入与重置</h3>'
        . '    <div class="md3e-backup-actions">'
        . '      <button type="button" id="md3e-btn-export" class="md3e-admin-btn">导出当前配置 JSON</button>'
        . '      <button type="button" id="md3e-btn-import" class="md3e-admin-btn">从下方 JSON 回填表单</button>'
        . '      <button type="button" id="md3e-btn-reset" class="md3e-admin-btn md3e-admin-btn--danger">恢复默认配置</button>'
        . '    </div>'
        . '    <textarea id="md3e-backup-json" class="md3e-backup-textarea" placeholder="点击「导出当前配置 JSON」生成备份，或粘贴历史 JSON 后点击「回填表单」并保存设置…"></textarea>'
        . '    <p id="md3e-backup-msg" class="md3e-backup-msg"></p>'
        . '  </div>'
        . '</div>'
        . '<script id="md3e-defaults-json" type="application/json">' . json_encode($defaults, JSON_UNESCAPED_UNICODE) . '</script>'
        . '<script src="' . md3e_e($themeUrl . '/assets/admin/settings.js') . '" defer></script>';
    $headerLayout->html($headerHtml);
    $form->addItem($headerLayout);

    // TAB 1: 色彩与动效
    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'defaultPalette',
        [
            'berry'     => '<span class="md3e-swatch-dot" style="background:#9B2362;"></span>' . _t('莓果红 Berry（MD3E 旗舰）'),
            'violet'    => '<span class="md3e-swatch-dot" style="background:#5D3FD3;"></span>' . _t('电光紫 Iris'),
            'lime'      => '<span class="md3e-swatch-dot" style="background:#2D6B2E;"></span>' . _t('青苔绿 Botanical'),
            'tangerine' => '<span class="md3e-swatch-dot" style="background:#B03A16;"></span>' . _t('日光橘 Sunburst'),
            'cobalt'    => '<span class="md3e-swatch-dot" style="background:#1556C9;"></span>' . _t('钴海蓝 Cobalt'),
            'custom'    => '<span class="md3e-swatch-dot" style="background:conic-gradient(#9B2362,#5D3FD3,#1556C9,#2D6B2E,#B03A16,#9B2362);"></span>' . _t('自定义种子主色'),
        ],
        $defaults['defaultPalette'],
        _t('默认动态配色种子 (Color Seed)'),
        _t('决定整站 Tonal Surface、主按钮、高亮容器与徽章色阶；选择「自定义种子主色」时将基于下方十六进制色值自动推演浅/深色全套角色')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'customSeedColor',
        null,
        $defaults['customSeedColor'],
        _t('自定义种子主色（HEX）'),
        _t('格式如 #9B2362 或 #006A6A。开启「自定义种子主色」后生效，也会作为第 6 个自定义色卡出现在前台调色盘中')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'defaultThemeMode',
        [
            'system' => _t('跟随系统偏好'),
            'light'  => _t('默认浅色模式'),
            'dark'   => _t('默认深色模式'),
        ],
        $defaults['defaultThemeMode'],
        _t('默认明暗外观模式')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'allowVisitorPalette',
        [
            '1' => _t('允许访客自由切换配色（显示调色盘按钮）'),
            '0' => _t('锁定站长默认配色（隐藏前台调色盘）'),
        ],
        $defaults['allowVisitorPalette'],
        _t('前台动态调色盘权限'),
        _t('锁定后访客将始终看到你指定的品牌色，仅保留深浅色切换按钮')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'shapeStyle',
        [
            'expressive' => _t('完整 Expressive（6 种有机几何日期戳 + 活泼弹簧形变）'),
            'soft'       => _t('柔和现代（统一柔角胶囊 + 温和形变）'),
            'minimal'    => _t('克制极简（减弱旋转动画，专注文字）'),
        ],
        $defaults['shapeStyle'],
        _t('MD3E 形状与动效风格')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'enableWavy',
        [
            '1' => _t('开启波浪进度条与波浪分隔线（MD3E 标志元素）'),
            '0' => _t('使用简洁直线进度条与分隔线'),
        ],
        $defaults['enableWavy'],
        _t('波浪装饰与波浪阅读进度条')
    ), 'appearance');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'enableRipple',
        [
            '1' => _t('开启点击水波纹反馈'),
            '0' => _t('关闭点击水波纹'),
        ],
        $defaults['enableRipple'],
        _t('Material 点击水波纹 (Ripple)')
    ), 'appearance');

    // TAB 2: 品牌与博主
    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        $defaults['logoUrl'],
        _t('站点 Logo 图片 URL'),
        _t('留空则显示 MD3E 四叶草造型站点首字母徽标')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'faviconUrl',
        null,
        $defaults['faviconUrl'],
        _t('浏览器标签页图标 (Favicon URL)'),
        _t('可选。填入 .ico / .png / .svg 链接')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'mastheadTitle',
        null,
        $defaults['mastheadTitle'],
        _t('首页报头自定义主标题'),
        _t('留空则自动使用 Typecho「基本设置」中的站点名称')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'mastheadDesc',
        null,
        $defaults['mastheadDesc'],
        _t('首页报头简介'),
        _t('留空则自动使用 Typecho 站点描述')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showStatsPill',
        [
            '1' => _t('显示文章与评论数统计胶囊'),
            '0' => _t('隐藏统计胶囊'),
        ],
        $defaults['showStatsPill'],
        _t('首页报头统计胶囊')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'authorName',
        null,
        $defaults['authorName'],
        _t('博主昵称'),
        _t('展示在右侧紧凑博主名片中')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'authorRole',
        null,
        $defaults['authorRole'],
        _t('博主身份副标'),
        _t('例如：博主 · 独立创作者 / 全栈工程师')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'authorBio',
        null,
        $defaults['authorBio'],
        _t('博主简介')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'avatarUrl',
        null,
        $defaults['avatarUrl'],
        _t('博主头像 URL'),
        _t('留空则渲染 MD3E 9 瓣曲奇造型首字母头像')
    ), 'brand');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'socialLinks',
        null,
        $defaults['socialLinks'],
        _t('博主社交链接'),
        _t('每行一个，格式为：名称|链接地址（例如 GitHub|https://github.com/yourname）')
    ), 'brand');

    // TAB 3: 首页与布局
    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'leadPostStyle',
        [
            'highlight' => _t('首篇高饱和主色容器导读卡 + 其余分组文章流（推荐）'),
            'uniform'   => _t('全部文章统一为 Android 16 分组分段文章流'),
        ],
        $defaults['leadPostStyle'],
        _t('首页第一篇文章展示形态')
    ), 'layout');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showCategoryBar',
        [
            '1' => _t('显示 MD3E 连接式分类筛选按钮组'),
            '0' => _t('隐藏顶部分类筛选条'),
        ],
        $defaults['showCategoryBar'],
        _t('连接式分类筛选条 (Connected Button Group)')
    ), 'layout');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showDateStamp',
        [
            '1' => _t('显示文章左侧 MD3E 有机几何日期戳'),
            '0' => _t('隐藏日期戳（更紧凑的纯文字列表）'),
        ],
        $defaults['showDateStamp'],
        _t('文章列表几何日期戳')
    ), 'layout');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'excerptLength',
        null,
        $defaults['excerptLength'],
        _t('文章列表摘要最大字数'),
        _t('填写 40 ~ 300 之间的整数，默认 110 字（若文章单独填写了自定义字段「文章导语」，将优先显示导语）')
    ), 'layout');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'sidebarPosition',
        [
            'right' => _t('右侧紧凑侧边栏（双栏博客布局）'),
            'none'  => _t('隐藏侧边栏（单栏专注阅读流）'),
        ],
        $defaults['sidebarPosition'],
        _t('列表页侧边栏布局')
    ), 'layout');

    $sidebarBlock = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarBlock',
        [
            'ShowAuthor'         => _t('博主名片'),
            'ShowCategory'       => _t('分类分组列表'),
            'ShowRecentPosts'    => _t('最新文章列表'),
            'ShowTagCloud'       => _t('标签胶囊云'),
            'ShowRecentComments' => _t('最近回复'),
            'ShowArchive'        => _t('月度归档'),
        ],
        $defaults['sidebarBlock'],
        _t('右侧栏显示模块'),
        _t('勾选需要在侧边栏中呈现的分组模块')
    );
    $add($sidebarBlock->multiMode(), 'layout');

    // TAB 4: 阅读体验
    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'defaultFontSize',
        [
            'compact' => _t('紧凑（0.96rem）'),
            'normal'  => _t('标准（1.05rem，推荐）'),
            'large'   => _t('大号（1.16rem）'),
        ],
        $defaults['defaultFontSize'],
        _t('文章正文默认字号'),
        _t('访客仍可在文章底部浮动工具栏随时切换个人偏好的字号')
    ), 'reading');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showToc',
        [
            '1' => _t('开启右侧吸顶自动目录 TOC 与阅读进度百分比'),
            '0' => _t('关闭右侧目录（正文居中宽屏阅读）'),
        ],
        $defaults['showToc'],
        _t('文章自动目录 (TOC)')
    ), 'reading');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showFloatingToolbar',
        [
            '1' => _t('显示底部居中 MD3E 浮动阅读工具栏（字号 / 评论 / 分享 / 顶）'),
            '0' => _t('隐藏浮动阅读工具栏'),
        ],
        $defaults['showFloatingToolbar'],
        _t('文章页浮动阅读工具栏')
    ), 'reading');

    $showPostMeta = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'showPostMeta',
        [
            'author'      => _t('作者胶囊'),
            'date'        => _t('发布日期'),
            'readingTime' => _t('字数与预计阅读时长'),
            'comments'    => _t('评论计数锚点'),
            'tags'        => _t('文末标签胶囊'),
            'nearPosts'   => _t('上一篇 / 下一篇连接卡片'),
        ],
        $defaults['showPostMeta'],
        _t('文章页元信息与组件开关')
    );
    $add($showPostMeta->multiMode(), 'reading');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'copyrightNotice',
        null,
        $defaults['copyrightNotice'],
        _t('文末版权声明卡片'),
        _t('支持变量：{author} 作者、{title} 文章标题、{url} 文章链接；留空则不显示版权声明卡片')
    ), 'reading');

    // TAB 5: 导航与页脚
    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'railExtraLinks',
        null,
        $defaults['railExtraLinks'],
        _t('左侧导航栏附加链接'),
        _t('每行一个，格式为：短名称|链接地址（例如：友链|/links.html 或 GitHub|https://github.com）')
    ), 'navigation');

    $add(new \Typecho\Widget\Helper\Form\Element\Radio(
        'showAdminInRail',
        [
            '1' => _t('在左侧 Navigation Rail 中显示「登录 / 后台」入口'),
            '0' => _t('隐藏导航栏中的管理入口（页脚仍保留入口）'),
        ],
        $defaults['showAdminInRail'],
        _t('导航栏后台入口')
    ), 'navigation');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'footerText',
        null,
        $defaults['footerText'],
        _t('页脚自定义文字'),
        _t('显示在版权年份与站点名称之后')
    ), 'navigation');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'icpNumber',
        null,
        $defaults['icpNumber'],
        _t('ICP 备案号'),
        _t('可选。填写后自动在页脚展示备案链接')
    ), 'navigation');

    $add(new \Typecho\Widget\Helper\Form\Element\Text(
        'icpUrl',
        null,
        $defaults['icpUrl'],
        _t('备案查询跳转地址'),
        _t('默认 https://beian.miit.gov.cn/')
    ), 'navigation');

    // TAB 6: 高级与备份
    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customCss',
        null,
        $defaults['customCss'],
        _t('自定义附加 CSS'),
        _t('直接内联至 &lt;head&gt; 中的样式，无需写 &lt;style&gt; 标签')
    ), 'advanced');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customHeadCode',
        null,
        $defaults['customHeadCode'],
        _t('自定义 &lt;head&gt; 头部代码'),
        _t('可填入网站统计代码、自定义字体或 Meta 验证标签')
    ), 'advanced');

    $add(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customFooterCode',
        null,
        $defaults['customFooterCode'],
        _t('自定义 &lt;/body&gt; 前底部代码'),
        _t('可填入延迟加载脚本或第三方插件代码')
    ), 'advanced');
}

/**
 * Per-post custom fields in Typecho editor.
 */
function themeFields($layout)
{
    $cover = new \Typecho\Widget\Helper\Form\Element\Text(
        'cover',
        null,
        null,
        _t('文章封面图 URL'),
        _t('可选。填写后将在文章卡片侧边与正文顶部展示')
    );
    $layout->addItem($cover);

    $subtitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'subtitle',
        null,
        null,
        _t('文章导语 / 摘要'),
        _t('可选。优先展示在首页文章列表与正文标题下方')
    );
    $layout->addItem($subtitle);

    $badge = new \Typecho\Widget\Helper\Form\Element\Text(
        'badge',
        null,
        null,
        _t('文章徽章'),
        _t('可选。例如：精选、深度、置顶、手记')
    );
    $layout->addItem($badge);
}
