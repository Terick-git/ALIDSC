/* ALIDSC v6 - Main JS */

document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu
  const toggle = document.querySelector('.mobile-toggle');
  const nav = document.querySelector('.nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => nav.classList.toggle('open'));
    document.querySelectorAll('.nav a').forEach(link => {
      link.addEventListener('click', () => nav.classList.remove('open'));
    });
  }

  // FAQ accordion
  document.querySelectorAll('.faq-question').forEach(q => {
    q.addEventListener('click', () => {
      const item = q.closest('.faq-item');
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!isOpen) item.classList.add('open');
    });
  });

  // Smooth scroll for anchors
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
  });

  // Form simple validation
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      let valid = true;
      form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) { valid = false; field.style.borderColor = '#e53e3e'; }
        else { field.style.borderColor = ''; }
      });
      if (!valid) { e.preventDefault(); alert('Veuillez remplir tous les champs obligatoires.'); }
      else if (form.dataset.success) { e.preventDefault(); alert(form.dataset.success); form.reset(); }
    });
  });

  // Current nav highlight
  const current = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav a, .sidebar-nav a').forEach(link => {
    if (link.getAttribute('href') === current) link.classList.add('active');
  });
});
