(function () {
  'use strict';

  const TAB_KEY = 'md3e_admin_active_tab';

  function initTabs() {
    const tabBtns = Array.from(document.querySelectorAll('.md3e-tab-btn'));
    const panels = Array.from(document.querySelectorAll('.md3e-tab-panel'));
    if (!tabBtns.length || !panels.length) return;

    // Move Typecho form option <ul> items into their matching MD3E tab panel
    const panelMap = {};
    panels.forEach((p) => {
      panelMap[p.getAttribute('data-panel')] = p;
    });

    document.querySelectorAll('ul.typecho-option[data-md3e-tab]').forEach((ul) => {
      const target = panelMap[ul.getAttribute('data-md3e-tab')];
      if (target) {
        const anchor = target.querySelector('.md3e-panel-end');
        if (anchor) {
          target.insertBefore(ul, anchor);
        } else {
          target.appendChild(ul);
        }
      }
    });

    function activate(tabId) {
      tabBtns.forEach((btn) => {
        btn.classList.toggle('is-active', btn.getAttribute('data-tab') === tabId);
      });
      panels.forEach((panel) => {
        panel.classList.toggle('is-active', panel.getAttribute('data-panel') === tabId);
      });
      try { sessionStorage.setItem(TAB_KEY, tabId); } catch (e) {}
    }

    tabBtns.forEach((btn) => {
      btn.addEventListener('click', () => activate(btn.getAttribute('data-tab')));
    });

    let initial = tabBtns[0].getAttribute('data-tab');
    try {
      const saved = sessionStorage.getItem(TAB_KEY);
      if (saved && panels.some((p) => p.getAttribute('data-panel') === saved)) {
        initial = saved;
      }
    } catch (e) {}
    activate(initial);
  }

  function initColorPickerSync() {
    const hexInput = document.querySelector('input[name="customSeedColor"]');
    if (!hexInput) return;

    const wrap = document.createElement('div');
    wrap.className = 'md3e-color-picker-wrap';

    const picker = document.createElement('input');
    picker.type = 'color';
    picker.value = /^#[0-9a-fA-F]{6}$/.test(hexInput.value.trim()) ? hexInput.value.trim() : '#9B2362';
    picker.setAttribute('aria-label', '选择自定义种子主色');

    const hint = document.createElement('span');
    hint.style.fontSize = '0.82rem';
    hint.style.color = '#6d5c64';
    hint.textContent = '点击左侧色块实时拾色（将自动勾选「自定义种子主色」）';

    hexInput.parentNode.insertBefore(wrap, hexInput.nextSibling);
    wrap.appendChild(picker);
    wrap.appendChild(hint);

    picker.addEventListener('input', () => {
      hexInput.value = picker.value.toUpperCase();
      const customRadio = document.querySelector('input[name="defaultPalette"][value="custom"]');
      if (customRadio) customRadio.checked = true;
    });

    hexInput.addEventListener('input', () => {
      if (/^#[0-9a-fA-F]{6}$/.test(hexInput.value.trim())) {
        picker.value = hexInput.value.trim();
      }
    });
  }

  function initBackupRestore() {
    const form = document.querySelector('form[action*="themes-edit"]');
    const area = document.getElementById('md3e-backup-json');
    const msg = document.getElementById('md3e-backup-msg');
    const exportBtn = document.getElementById('md3e-btn-export');
    const importBtn = document.getElementById('md3e-btn-import');
    const resetBtn = document.getElementById('md3e-btn-reset');
    if (!form || !area) return;

    function say(text) {
      if (msg) msg.textContent = text;
    }

    function collectFormData() {
      const data = {};
      const elements = form.querySelectorAll('input[name], textarea[name], select[name]');
      elements.forEach((el) => {
        const rawName = el.name;
        if (!rawName || rawName === '_') return;
        if (el.type === 'radio') {
          if (el.checked) data[rawName] = el.value;
        } else if (el.type === 'checkbox') {
          const cleanName = rawName.replace(/\[\]$/, '');
          if (!Array.isArray(data[cleanName])) data[cleanName] = [];
          if (el.checked) data[cleanName].push(el.value);
        } else {
          data[rawName] = el.value;
        }
      });
      return data;
    }

    function applyFormData(data) {
      Object.keys(data).forEach((key) => {
        const val = data[key];
        const radios = form.querySelectorAll('input[type="radio"][name="' + key + '"]');
        if (radios.length) {
          radios.forEach((r) => { r.checked = (r.value === String(val)); });
          return;
        }
        const checkboxes = form.querySelectorAll('input[type="checkbox"][name="' + key + '[]"]');
        if (checkboxes.length) {
          const list = Array.isArray(val) ? val : [];
          checkboxes.forEach((cb) => { cb.checked = list.includes(cb.value); });
          return;
        }
        const field = form.querySelector('[name="' + key + '"]');
        if (field) {
          field.value = val == null ? '' : String(val);
          field.dispatchEvent(new Event('input', { bubbles: true }));
        }
      });
    }

    if (exportBtn) {
      exportBtn.addEventListener('click', () => {
        const json = JSON.stringify(collectFormData(), null, 2);
        area.value = json;
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(json).then(() => {
            say('已导出当前配置 JSON 并复制到剪贴板。');
          }).catch(() => {
            say('已导出当前配置 JSON 到下方文本框。');
          });
        } else {
          say('已导出当前配置 JSON 到下方文本框。');
        }
      });
    }

    if (importBtn) {
      importBtn.addEventListener('click', () => {
        try {
          const parsed = JSON.parse(area.value.trim());
          if (!parsed || typeof parsed !== 'object') throw new Error('Invalid JSON');
          applyFormData(parsed);
          say('已将 JSON 配置回填到表单，请点击底部「保存设置」使其生效！');
        } catch (err) {
          say('导入失败：JSON 格式不正确，请检查粘贴内容。');
        }
      });
    }

    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        if (!window.confirm('确认将所有外观选项重置为 MD3E 默认值吗？（点击底部「保存设置」后生效）')) {
          return;
        }
        const defaultsEl = document.getElementById('md3e-defaults-json');
        if (defaultsEl) {
          try {
            const defaults = JSON.parse(defaultsEl.textContent || '{}');
            applyFormData(defaults);
            say('已重置表单为默认值，请点击底部「保存设置」确认保存。');
          } catch (e) {}
        }
      });
    }
  }

  function boot() {
    initTabs();
    initColorPickerSync();
    initBackupRestore();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
