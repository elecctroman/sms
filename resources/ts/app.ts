import AOS from 'aos';

AOS.init();

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-theme-toggle]');
  if (toggle) {
    toggle.addEventListener('click', () => {
      document.body.classList.toggle('theme-dark');
    });
  }

  document.querySelectorAll('[data-copy]').forEach((element) => {
    element.addEventListener('click', () => {
      const value = (element as HTMLElement).dataset.copy || '';
      navigator.clipboard.writeText(value);
    });
  });
});
