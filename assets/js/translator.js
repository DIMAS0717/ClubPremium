document.addEventListener('DOMContentLoaded', () => {
  const langToggle = document.getElementById('langToggle');
  if (!langToggle) return;

  const STORAGE_KEY = 'site_language';
  const DEFAULT_LANG = 'es';
  const BASE_URL = window.APP_BASE_URL || '';
  const TRANSLATE_URL = `${BASE_URL}/api/translate.php`;

  let textEntries = [];
  let placeholderEntries = [];

  function hasLetters(text) {
    return /[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/.test(text);
  }

  function isInsideNoTranslate(el) {
    if (!el) return false;

    return !!(
      el.closest('[data-no-translate]') ||
      el.id === 'langToggle' ||
      el.id === 'themeToggle' ||
      el.id === 'menuToggle'
    );
  }

  function shouldSkipElement(el) {
    if (!el) return true;
    if (isInsideNoTranslate(el)) return true;

    const tag = el.tagName;
    if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA', 'CODE', 'PRE'].includes(tag)) return true;
    if (el.isContentEditable) return true;

    return false;
  }

  function captureTextNodes() {
    const walker = document.createTreeWalker(
      document.body,
      NodeFilter.SHOW_TEXT,
      {
        acceptNode(node) {
          const parent = node.parentElement;
          if (!parent) return NodeFilter.FILTER_REJECT;
          if (shouldSkipElement(parent)) return NodeFilter.FILTER_REJECT;

          const raw = node.nodeValue || '';
          const core = raw.trim();

          if (!core) return NodeFilter.FILTER_REJECT;
          if (core.length < 2) return NodeFilter.FILTER_REJECT;
          if (!hasLetters(core)) return NodeFilter.FILTER_REJECT;

          return NodeFilter.FILTER_ACCEPT;
        }
      }
    );

    const entries = [];
    let node;

    while ((node = walker.nextNode())) {
      const raw = node.nodeValue || '';
      const matchPrefix = raw.match(/^\s*/);
      const matchSuffix = raw.match(/\s*$/);

      const prefix = matchPrefix ? matchPrefix[0] : '';
      const suffix = matchSuffix ? matchSuffix[0] : '';
      const core = raw.trim();

      entries.push({
        node,
        raw,
        prefix,
        core,
        suffix
      });
    }

    return entries;
  }

  function capturePlaceholderNodes() {
    return [...document.querySelectorAll('input[placeholder], textarea[placeholder]')]
      .filter(el => !isInsideNoTranslate(el))
      .map(el => ({
        el,
        raw: el.getAttribute('placeholder') || ''
      }))
      .filter(item => {
        const text = item.raw.trim();
        return text && hasLetters(text);
      });
  }

  function snapshotOriginalSpanish() {
    textEntries = captureTextNodes();
    placeholderEntries = capturePlaceholderNodes();
  }

  function updateToggleUI(currentLang, loading = false) {
    langToggle.dataset.currentLang = currentLang;
    langToggle.disabled = loading;
    langToggle.textContent = currentLang === 'en' ? 'EN | ES' : 'ES | EN';
    document.documentElement.lang = currentLang;
  }

  function restoreSpanish() {
    textEntries.forEach(item => {
      if (item.node) {
        item.node.nodeValue = item.raw;
      }
    });

    placeholderEntries.forEach(item => {
      item.el.setAttribute('placeholder', item.raw);
    });

    updateToggleUI('es');
    localStorage.setItem(STORAGE_KEY, 'es');
  }

  async function translateToEnglish() {
    if (textEntries.length === 0 && placeholderEntries.length === 0) {
      snapshotOriginalSpanish();
    }

    const payloadItems = [
      ...textEntries.map(item => ({
        type: 'text',
        ref: item,
        value: item.core
      })),
      ...placeholderEntries.map(item => ({
        type: 'placeholder',
        ref: item,
        value: item.raw.trim()
      }))
    ];

    if (payloadItems.length === 0) {
      updateToggleUI('en');
      localStorage.setItem(STORAGE_KEY, 'en');
      return;
    }

    updateToggleUI('en', true);

    try {
      const response = await fetch(TRANSLATE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          source: 'ES',
          target: 'EN-US',
          texts: payloadItems.map(item => item.value)
        })
      });

      const data = await response.json();

      if (!response.ok || !data.ok || !Array.isArray(data.translations)) {
        throw new Error(data.message || 'No se pudo traducir la página');
      }

      payloadItems.forEach((item, index) => {
        const translated = data.translations[index] ?? item.value;

        if (item.type === 'text') {
          item.ref.node.nodeValue = item.ref.prefix + translated + item.ref.suffix;
        } else {
          item.ref.el.setAttribute('placeholder', translated);
        }
      });

      updateToggleUI('en');
      localStorage.setItem(STORAGE_KEY, 'en');
    } catch (error) {
      console.error(error);
      alert('No se pudo traducir la página. Revisa tu API key, la URL de DeepL o el endpoint PHP.');
      updateToggleUI('es');
    }
  }

  langToggle.addEventListener('click', async () => {
    const currentLang = langToggle.dataset.currentLang || DEFAULT_LANG;

    if (currentLang === 'es') {
      snapshotOriginalSpanish();
      await translateToEnglish();
    } else {
      restoreSpanish();
    }
  });

  snapshotOriginalSpanish();

  const savedLang = localStorage.getItem(STORAGE_KEY) || DEFAULT_LANG;
  updateToggleUI(savedLang);

  if (savedLang === 'en') {
    translateToEnglish();
  }
});