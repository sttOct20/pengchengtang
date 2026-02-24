/* ============================================================
   彭城堂中医 | main.js — Language Toggle & Navigation
   ============================================================ */

/* ── Language System ── */
const LANG_KEY = 'pct-lang';

function setLanguage(lang) {
  const isEn = lang === 'en';

  // Update html lang attribute
  document.documentElement.lang = isEn ? 'en' : 'zh-CN';

  // Swap all translatable text nodes
  document.querySelectorAll('[data-zh]').forEach(el => {
    el.textContent = isEn ? (el.dataset.en || el.dataset.zh) : el.dataset.zh;
  });

  // Swap placeholders
  document.querySelectorAll('[data-ph-zh]').forEach(el => {
    el.placeholder = isEn ? (el.dataset.phEn || el.dataset.phZh) : el.dataset.phZh;
  });

  // Swap aria-labels
  document.querySelectorAll('[data-aria-zh]').forEach(el => {
    el.setAttribute('aria-label', isEn ? (el.dataset.ariaEn || el.dataset.ariaZh) : el.dataset.ariaZh);
  });

  // Update toggle button label
  const toggle = document.getElementById('langToggle');
  if (toggle) toggle.textContent = isEn ? '中文' : 'EN';

  // Update body font class
  document.body.classList.toggle('lang-en', isEn);

  // Persist
  localStorage.setItem(LANG_KEY, lang);
}

function initLanguage() {
  const saved = localStorage.getItem(LANG_KEY) || 'zh';
  setLanguage(saved);
}

/* ── Navigation ── */
function initNav() {
  const nav = document.getElementById('nav');
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const langToggle = document.getElementById('langToggle');
  const mobileLangToggle = document.getElementById('mobileLangToggle');

  // Scroll: make nav opaque
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });

  // Hamburger toggle
  if (hamburger) {
    hamburger.addEventListener('click', () => {
      const isOpen = mobileNav.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', isOpen);
    });
  }

  // Close mobile nav on link click
  if (mobileNav) {
    mobileNav.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => mobileNav.classList.remove('open'));
    });
  }

  // Language toggle
  function handleLangToggle() {
    const current = localStorage.getItem(LANG_KEY) || 'zh';
    setLanguage(current === 'zh' ? 'en' : 'zh');
  }

  if (langToggle) langToggle.addEventListener('click', handleLangToggle);
  if (mobileLangToggle) mobileLangToggle.addEventListener('click', handleLangToggle);

  // Highlight active nav link
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .nav-mobile a').forEach(a => {
    const href = a.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      a.classList.add('active');
    }
  });
}

/* ── Franchise Form ── */
function initForm() {
  const form = document.getElementById('franchiseForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = form.querySelector('.form-submit');
    btn.disabled = true;
    btn.textContent = '提交中...';

    // Simulate submission (replace with real API endpoint)
    await new Promise(r => setTimeout(r, 1000));

    form.style.display = 'none';
    const success = document.getElementById('formSuccess');
    if (success) success.style.display = 'block';
  });
}

/* ── Scroll Reveal ── */
function initScrollReveal() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
  initLanguage();
  initNav();
  initForm();
  initScrollReveal();
});
