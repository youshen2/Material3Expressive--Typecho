/**
 * Material Design 3 Expressive (MD3E) Blog Interactions
 * Zero dependencies · Vanilla ES6+
 */
(function () {
  'use strict';

  const html = document.documentElement;
  const STORAGE_THEME = 'md3e_theme_mode';
  const STORAGE_PALETTE = 'md3e_color_palette';
  const STORAGE_FONT = 'md3e_prose_scale';

  /* ------------------------------------------------------------------------
     1. Snackbar Toast
     ------------------------------------------------------------------------ */
  const snackbar = document.getElementById('md-snackbar');
  let snackTimer = null;

  function showToast(msg) {
    if (!snackbar) return;
    snackbar.textContent = msg;
    snackbar.classList.add('is-visible');
    clearTimeout(snackTimer);
    snackTimer = setTimeout(() => {
      snackbar.classList.remove('is-visible');
    }, 2200);
  }

  /* ------------------------------------------------------------------------
     2. Dynamic Seed Palette & Dark/Light Mode Controller
     ------------------------------------------------------------------------ */
  function syncControls() {
    const currentTheme = html.getAttribute('data-theme') || 'light';
    const currentPalette = html.getAttribute('data-palette') || 'berry';

    document.querySelectorAll('[data-set-palette]').forEach((btn) => {
      btn.classList.toggle('is-active', btn.getAttribute('data-set-palette') === currentPalette);
    });

    document.querySelectorAll('[data-set-theme]').forEach((btn) => {
      btn.classList.toggle('is-active', btn.getAttribute('data-set-theme') === currentTheme);
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
      const sun = btn.querySelector('.icon-sun');
      const moon = btn.querySelector('.icon-moon');
      if (sun && moon) {
        sun.style.display = currentTheme === 'dark' ? 'inline-block' : 'none';
        moon.style.display = currentTheme === 'dark' ? 'none' : 'inline-block';
      }
    });
  }

  function withInstantThemeSwitch(fn) {
    html.classList.add('is-theme-switching');
    fn();
    void html.offsetWidth;
    requestAnimationFrame(() => {
      html.classList.remove('is-theme-switching');
    });
  }

  function applyTheme(mode) {
    withInstantThemeSwitch(() => {
      html.setAttribute('data-theme', mode);
    });
    try { localStorage.setItem(STORAGE_THEME, mode); } catch (e) {}
    syncControls();
  }

  function applyPalette(palette) {
    withInstantThemeSwitch(() => {
      html.setAttribute('data-palette', palette);
    });
    try { localStorage.setItem(STORAGE_PALETTE, palette); } catch (e) {}
    syncControls();
  }

  /* Click-anywhere on post card while preserving inner links & text selection */
  document.addEventListener('click', (e) => {
    const card = e.target.closest('[data-card-url]');
    if (!card) return;
    if (e.target.closest('a, button')) return;
    const sel = window.getSelection ? window.getSelection().toString() : '';
    if (sel.length > 0) return;
    const url = card.getAttribute('data-card-url');
    if (url) {
      if (e.metaKey || e.ctrlKey) {
        window.open(url, '_blank', 'noopener');
      } else {
        window.location.href = url;
      }
    }
  });

  document.addEventListener('click', (e) => {
    const pBtn = e.target.closest('[data-set-palette]');
    if (pBtn) {
      applyPalette(pBtn.getAttribute('data-set-palette'));
      return;
    }

    const mBtn = e.target.closest('[data-set-theme]');
    if (mBtn) {
      applyTheme(mBtn.getAttribute('data-set-theme'));
      return;
    }

    const tBtn = e.target.closest('[data-theme-toggle]');
    if (tBtn) {
      applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    }
  });

  /* ------------------------------------------------------------------------
     3. Palette Sheet Toggle
     ------------------------------------------------------------------------ */
  const paletteSheet = document.getElementById('md-palette-sheet');
  const paletteTriggers = document.querySelectorAll('[data-toggle-palette]');

  if (paletteSheet && paletteTriggers.length) {
    paletteTriggers.forEach((trigger) => {
      trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const open = paletteSheet.classList.toggle('is-open');
        paletteTriggers.forEach((t) => t.setAttribute('aria-expanded', open ? 'true' : 'false'));
      });
    });

    document.addEventListener('click', (e) => {
      if (!paletteSheet.contains(e.target) && !e.target.closest('[data-toggle-palette]')) {
        paletteSheet.classList.remove('is-open');
        paletteTriggers.forEach((t) => t.setAttribute('aria-expanded', 'false'));
      }
    });
  }

  /* ------------------------------------------------------------------------
     4. Tactile Material Ripple Effect (respects Admin enableRipple setting)
     ------------------------------------------------------------------------ */
  document.addEventListener('pointerdown', (e) => {
    if (html.getAttribute('data-ripple') === '0') return;
    const target = e.target.closest('.md-btn, .md-connected-btn, .md-chip, .md-rail-fab, .md-toolbar-btn, .md-split-main, .md-split-end');
    if (!target) return;

    const rect = target.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const wave = document.createElement('span');
    wave.className = 'md-ripple-wave';
    wave.style.width = wave.style.height = size + 'px';
    wave.style.left = e.clientX - rect.left - size / 2 + 'px';
    wave.style.top = e.clientY - rect.top - size / 2 + 'px';

    if (getComputedStyle(target).position === 'static') {
      target.style.position = 'relative';
    }
    target.style.overflow = 'hidden';
    target.appendChild(wave);
    setTimeout(() => wave.remove(), 560);
  });

  /* ------------------------------------------------------------------------
     5. Search Dialog (Cmd/Ctrl + K)
     ------------------------------------------------------------------------ */
  const searchScrim = document.getElementById('md-search-scrim');
  const searchInput = document.getElementById('md-search-input');

  function openSearch() {
    if (!searchScrim) return;
    searchScrim.classList.add('is-open');
    setTimeout(() => searchInput && searchInput.focus(), 60);
  }

  function closeSearch() {
    if (searchScrim) searchScrim.classList.remove('is-open');
  }

  document.querySelectorAll('[data-open-search]').forEach((btn) => {
    btn.addEventListener('click', openSearch);
  });

  if (searchScrim) {
    searchScrim.addEventListener('click', (e) => {
      if (e.target === searchScrim) closeSearch();
    });
  }

  document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (searchScrim && searchScrim.classList.contains('is-open')) {
        closeSearch();
      } else {
        openSearch();
      }
    } else if (e.key === 'Escape') {
      closeSearch();
      closeLightbox();
      if (paletteSheet) paletteSheet.classList.remove('is-open');
    }
  });

  /* ------------------------------------------------------------------------
     6. Wavy Reading Progress Bar & Accurate Scroll-based TOC Spy
     ------------------------------------------------------------------------ */
  const progressFill = document.getElementById('md-reading-progress');
  const tocPct = document.getElementById('md-toc-pct');
  const topFab = document.getElementById('md-top-fab');
  const proseEl = document.querySelector('.md-prose');
  const tocList = document.getElementById('md-toc-list');
  const tocCard = document.getElementById('md-toc-card');
  let tocItems = [];

  if (proseEl && tocList) {
    const headings = Array.from(proseEl.querySelectorAll('h2, h3'));
    if (headings.length === 0 && tocCard) {
      tocCard.style.display = 'none';
    } else {
      headings.forEach((h, idx) => {
        if (!h.id) h.id = 'section-' + (idx + 1);
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = '#' + h.id;
        a.textContent = h.textContent.trim();
        a.className = 'md-toc-link ' + (h.tagName === 'H3' ? 'md-toc-link--h3' : 'md-toc-link--h2');
        li.appendChild(a);
        tocList.appendChild(li);
        tocItems.push({ heading: h, link: a });
      });
    }
  }

  function updateReadingState() {
    const y = window.scrollY || document.documentElement.scrollTop;

    if (topFab) {
      topFab.classList.toggle('is-visible', y > 320);
    }

    if (progressFill || tocPct) {
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const pct = docHeight > 0 ? Math.min(100, Math.max(0, (y / docHeight) * 100)) : 0;
      if (progressFill) progressFill.style.width = pct + '%';
      if (tocPct) tocPct.textContent = Math.round(pct) + '%';
    }

    if (tocItems.length > 0) {
      const offset = 120;
      let activeIdx = 0;
      for (let i = 0; i < tocItems.length; i++) {
        if (tocItems[i].heading.getBoundingClientRect().top <= offset) {
          activeIdx = i;
        } else {
          break;
        }
      }
      tocItems.forEach((item, i) => {
        item.link.classList.toggle('is-active', i === activeIdx);
      });
    }
  }

  window.addEventListener('scroll', updateReadingState, { passive: true });
  updateReadingState();

  /* ------------------------------------------------------------------------
     7. Floating Reading Toolbar Actions (Font Scale, Share Link, Scroll Top)
     ------------------------------------------------------------------------ */
  const fontPresets = [
    { key: 'normal', size: '1.05rem', label: '标准' },
    { key: 'large', size: '1.16rem', label: '大号' },
    { key: 'compact', size: '0.96rem', label: '紧凑' },
  ];
  const defaultFontKey = html.getAttribute('data-default-font') || 'normal';
  let fontIdx = Math.max(0, fontPresets.findIndex((p) => p.key === defaultFontKey));
  try {
    const savedFontRaw = localStorage.getItem(STORAGE_FONT);
    if (savedFontRaw !== null) {
      const savedFont = parseInt(savedFontRaw, 10);
      if (!isNaN(savedFont) && fontPresets[savedFont]) {
        fontIdx = savedFont;
      }
    }
  } catch (e) {}

  function applyFontScale() {
    const preset = fontPresets[fontIdx];
    html.style.setProperty('--md-prose-size', preset.size);
    document.querySelectorAll('[data-font-label]').forEach((el) => {
      el.textContent = preset.label;
    });
  }
  applyFontScale();

  document.querySelectorAll('[data-font-scale]').forEach((btn) => {
    btn.addEventListener('click', () => {
      fontIdx = (fontIdx + 1) % fontPresets.length;
      applyFontScale();
      try { localStorage.setItem(STORAGE_FONT, String(fontIdx)); } catch (e) {}
      showToast('正文字号：' + fontPresets[fontIdx].label);
    });
  });

  document.querySelectorAll('[data-copy-link]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const url = window.location.href;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => showToast('文章链接已复制')).catch(() => {});
      } else {
        showToast('请手动复制地址栏链接');
      }
    });
  });

  document.querySelectorAll('[data-scroll-top]').forEach((btn) => {
    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  /* ------------------------------------------------------------------------
     8. Enhanced Code Blocks (Language Detection + Copy Button)
     ------------------------------------------------------------------------ */
  if (proseEl) {
    proseEl.querySelectorAll('pre').forEach((pre) => {
      if (pre.closest('.md-code-block')) return;
      const code = pre.querySelector('code');
      let lang = 'CODE';
      if (code && code.className) {
        const cleaned = code.className.trim();
        const langMatch = cleaned.match(/(?:language-|lang-)([a-zA-Z0-9_+#-]+)/);
        if (langMatch) {
          lang = langMatch[1].toUpperCase();
        } else if (/^[a-zA-Z0-9_+#-]+$/.test(cleaned)) {
          lang = cleaned.toUpperCase();
        }
      }

      const wrapper = document.createElement('div');
      wrapper.className = 'md-code-block';

      const header = document.createElement('div');
      header.className = 'md-code-header';
      header.innerHTML =
        '<span>' + lang + '</span>' +
        '<button type="button" class="md-code-copy">复制代码</button>';

      pre.parentNode.insertBefore(wrapper, pre);
      wrapper.appendChild(header);
      wrapper.appendChild(pre);

      const copyBtn = header.querySelector('.md-code-copy');
      copyBtn.addEventListener('click', () => {
        const text = code ? code.textContent : pre.textContent;
        const done = () => {
          copyBtn.textContent = '已复制';
          copyBtn.classList.add('is-copied');
          showToast('代码已复制到剪贴板');
          setTimeout(() => {
            copyBtn.textContent = '复制代码';
            copyBtn.classList.remove('is-copied');
          }, 1800);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(done).catch(() => {});
        } else {
          const ta = document.createElement('textarea');
          ta.value = text;
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          ta.remove();
          done();
        }
      });
    });
  }

  /* ------------------------------------------------------------------------
     9. Image Lightbox
     ------------------------------------------------------------------------ */
  const lightbox = document.getElementById('md-lightbox');
  const lightboxImg = document.getElementById('md-lightbox-img');

  function closeLightbox() {
    if (lightbox) lightbox.classList.remove('is-open');
    if (lightboxImg) lightboxImg.removeAttribute('src');
  }

  if (lightbox && lightboxImg) {
    document.querySelectorAll('.md-prose img, .md-article-cover img').forEach((img) => {
      img.addEventListener('click', () => {
        lightboxImg.src = img.src;
        lightboxImg.alt = img.alt || '';
        lightbox.classList.add('is-open');
      });
    });

    lightbox.addEventListener('click', closeLightbox);
  }

  syncControls();
})();
