document.addEventListener('DOMContentLoaded', () => {
  const langToggle = document.getElementById('langToggle');
  if (!langToggle) return;

  const STORAGE_KEY = 'site_language';
  const DEFAULT_LANG = 'es';

  const TEXT_SELECTOR = [
    'header nav a',
    '.mobile-nav a',
    'main h1', 'main h2', 'main h3', 'main h4', 'main h5', 'main h6',
    'main p', 'main li', 'main a', 'main button', 'main label',
    'main span', 'main small', 'main option',
    'footer h1', 'footer h2', 'footer h3', 'footer h4',
    'footer p', 'footer li', 'footer a', 'footer span', 'footer small'
  ].join(', ');

  const PLACEHOLDER_SELECTOR = 'input[placeholder], textarea[placeholder]';

  function hasLetters(text) {
    return /[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/.test(text);
  }

  function isExcluded(el) {
    return (
      el.closest('[data-no-translate]') ||
      el.matches('#langToggle') ||
      el.matches('#themeToggle') ||
      el.matches('#menuToggle')
    );
  }

  function getTextElements() {
    return [...document.querySelectorAll(TEXT_SELECTOR)].filter(el => {
      if (isExcluded(el)) return false;

      // evitamos romper nodos complejos con mucho HTML interno
      if (el.children.length > 0) return false;

      const text = (el.textContent || '').trim();
      if (!text) return false;
      if (text.length < 2) return false;
      if (!hasLetters(text)) return false;

      return true;
    });
  }

  function getPlaceholderElements() {
    return [...document.querySelectorAll(PLACEHOLDER_SELECTOR)].filter(el => {
      if (isExcluded(el)) return false;

      const text = (el.getAttribute('placeholder') || '').trim();
      if (!text) return false;
      if (!hasLetters(text)) return false;

      return true;
    });
  }

  function rememberOriginals() {
    getTextElements().forEach(el => {
      if (!el.dataset.originalText) {
        el.dataset.originalText = el.textContent.trim();
      }
    });

    getPlaceholderElements().forEach(el => {
      if (!el.dataset.originalPlaceholder) {
        el.dataset.originalPlaceholder = el.getAttribute('placeholder').trim();
      }
    });
  }

  function updateToggleUI(currentLang, loading = false) {
    langToggle.dataset.currentLang = currentLang;
    langToggle.disabled = loading;
    langToggle.textContent = currentLang === 'en' ? 'EN | ES' : 'ES | EN';
    document.documentElement.lang = currentLang;
  }

  function restoreSpanish() {
    getTextElements().forEach(el => {
      if (el.dataset.originalText) {
        el.textContent = el.dataset.originalText;
      }
    });

    getPlaceholderElements().forEach(el => {
      if (el.dataset.originalPlaceholder) {
        el.setAttribute('placeholder', el.dataset.originalPlaceholder);
      }
    });

    updateToggleUI('es');
    localStorage.setItem(STORAGE_KEY, 'es');
  }

  async function translateToEnglish() {
    const textElements = getTextElements();
    const placeholderElements = getPlaceholderElements();

    // si ya tradujimos una vez, usamos cache del DOM
    const allTextCached = textElements.every(el => el.dataset.translatedEn);
    const allPlaceholdersCached = placeholderElements.every(el => el.dataset.translatedEnPlaceholder);

    if (allTextCached && allPlaceholdersCached) {
      textElements.forEach(el => {
        el.textContent = el.dataset.translatedEn;
      });

      placeholderElements.forEach(el => {
        el.setAttribute('placeholder', el.dataset.translatedEnPlaceholder);
      });

      updateToggleUI('en');
      localStorage.setItem(STORAGE_KEY, 'en');
      return;
    }

    const payloadItems = [];

    textElements.forEach(el => {
      payloadItems.push({
        type: 'text',
        el,
        value: el.dataset.originalText || el.textContent.trim()
      });
    });

    placeholderElements.forEach(el => {
      payloadItems.push({
        type: 'placeholder',
        el,
        value: el.dataset.originalPlaceholder || el.getAttribute('placeholder').trim()
      });
    });

    const texts = payloadItems.map(item => item.value);

    updateToggleUI('en', true);

    try {
      const response = await fetch('api/translate.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          source: 'es',
          target: 'en',
          texts
        })
      });

      const data = await response.json();

      if (!response.ok || !data.ok || !Array.isArray(data.translations)) {
        throw new Error(data.message || 'No se pudo traducir la página.');
      }

      payloadItems.forEach((item, index) => {
        const translated = data.translations[index] ?? item.value;

        if (item.type === 'text') {
          item.el.textContent = translated;
          item.el.dataset.translatedEn = translated;
        } else if (item.type === 'placeholder') {
          item.el.setAttribute('placeholder', translated);
          item.el.dataset.translatedEnPlaceholder = translated;
        }
      });

      updateToggleUI('en');
      localStorage.setItem(STORAGE_KEY, 'en');

    } catch (error) {
      console.error(error);
      alert('No se pudo traducir la página. Revisa tu API key y el endpoint PHP.');
      updateToggleUI('es');
    }
  }

  langToggle.addEventListener('click', async () => {
    const currentLang = langToggle.dataset.currentLang || DEFAULT_LANG;

    if (currentLang === 'es') {
      await translateToEnglish();
    } else {
      restoreSpanish();
    }
  });

  rememberOriginals();

  const savedLang = localStorage.getItem(STORAGE_KEY) || DEFAULT_LANG;
  updateToggleUI(savedLang);

  if (savedLang === 'en') {
    translateToEnglish();
  }
});