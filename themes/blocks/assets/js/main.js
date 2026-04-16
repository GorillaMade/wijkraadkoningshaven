// main.js

// NAVIGATION DROPDOWNS
(function () {
  const OPEN_CLASS = 'is-open';
  const TOGGLE_SEL = '.nav__toggle';

  function closeItem(item) {
    if (!item) return;
    item.classList.remove(OPEN_CLASS);
    const toggle = item.querySelector(TOGGLE_SEL);
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  }

  function openItem(item) {
    if (!item) return;
    item.classList.add(OPEN_CLASS);
    const toggle = item.querySelector(TOGGLE_SEL);
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
  }

  function toggleItem(item) {
    const toggle = item.querySelector(TOGGLE_SEL);
    if (!toggle) return;
    if (toggle.getAttribute('aria-expanded') === 'true') closeItem(item);
    else openItem(item);
  }

  function closeAll() {
    document.querySelectorAll('.nav__item.' + OPEN_CLASS).forEach(closeItem);
  }

  function initNavDropdowns() {
    closeAll();

    document.querySelectorAll(TOGGLE_SEL).forEach(toggle => {
      toggle.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        const item = toggle.closest('.nav__item');
        toggleItem(item);
      });
    });

    window.addEventListener('pageshow', ev => {
      if (ev.persisted) closeAll();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNavDropdowns);
  } else {
    initNavDropdowns();
  }
})();

// HAMBURGER TOGGLE
document.addEventListener('DOMContentLoaded', () => {
  const hamburger = document.querySelector('.hamburger');
  const nav = document.querySelector('.nav');

  if (hamburger && nav) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      nav.classList.toggle('open');
    });
  }
});
