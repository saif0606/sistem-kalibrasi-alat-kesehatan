document.addEventListener('DOMContentLoaded', function () {

  /* ---------------- Loading Screen ---------------- */
  var loadingScreen = document.getElementById('loadingScreen');
  window.addEventListener('load', function () {
    setTimeout(function () {
      if (loadingScreen) loadingScreen.classList.add('hide');
    }, 500);
  });

  /* ---------------- Dark / Light Mode ---------------- */
  var root = document.documentElement;
  var themeToggle = document.getElementById('themeToggle');
  var savedTheme = localStorage.getItem('uptd-theme') || 'light';
  root.setAttribute('data-theme', savedTheme);
  updateToggleIcon(savedTheme);

  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      var current = root.getAttribute('data-theme');
      var next = current === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      localStorage.setItem('uptd-theme', next);
      updateToggleIcon(next);
    });
  }

  function updateToggleIcon(theme) {
    if (!themeToggle) return;
    var icon = themeToggle.querySelector('.knob i');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
  }

  /* ---------------- Sticky Navbar on Scroll ---------------- */
  var navbar = document.getElementById('mainNavbar');
  function handleNavScroll() {
    if (!navbar) return;
    if (window.scrollY > 30) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }
  handleNavScroll();
  window.addEventListener('scroll', handleNavScroll);

  /* ---------------- Mobile Hamburger Menu ---------------- */
  var toggler = document.getElementById('navToggler');
  var navMenu = document.getElementById('navMenu');
  var navOverlay = document.getElementById('navOverlay');

  function closeMenu() {
    if (navMenu) navMenu.classList.remove('open');
    if (navOverlay) navOverlay.classList.remove('show');
    if (toggler) toggler.innerHTML = '<i class="bi bi-list"></i>';
  }

  if (toggler && navMenu) {
    toggler.addEventListener('click', function () {
      var isOpen = navMenu.classList.toggle('open');
      if (navOverlay) navOverlay.classList.toggle('show', isOpen);
      toggler.innerHTML = isOpen ? '<i class="bi bi-x-lg"></i>' : '<i class="bi bi-list"></i>';
    });
  }
  if (navOverlay) navOverlay.addEventListener('click', closeMenu);
  document.querySelectorAll('.nav-menu .nav-link').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  /* ---------------- Active Link on Scroll ---------------- */
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.nav-menu .nav-link');
  window.addEventListener('scroll', function () {
    var scrollPos = window.scrollY + 120;
    sections.forEach(function (sec) {
      if (scrollPos >= sec.offsetTop && scrollPos < sec.offsetTop + sec.offsetHeight) {
        navLinks.forEach(function (l) { l.classList.remove('active'); });
        var active = document.querySelector('.nav-menu .nav-link[href="#' + sec.id + '"]');
        if (active) active.classList.add('active');
      }
    });
  });

  /* ---------------- AOS Init ---------------- */
  if (window.AOS) {
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60
    });
  }

  /* ---------------- Counter Animation ---------------- */
  var counters = document.querySelectorAll('.stat-item .num');
  var counterObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(function (c) { counterObserver.observe(c); });

  function animateCounter(el) {
    var target = el.getAttribute('data-target');
    var suffix = el.getAttribute('data-suffix') || '';
    var isNumeric = target && !isNaN(parseFloat(target));
    if (!isNumeric) return; // leave static text like "ISO/IEC 17025"
    var end = parseFloat(target);
    var duration = 1400;
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var value = Math.floor(progress * end);
      el.textContent = value.toLocaleString('id-ID') + suffix;
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = end.toLocaleString('id-ID') + suffix;
      }
    }
    requestAnimationFrame(step);
  }

  /* ---------------- Back To Top ---------------- */
  var backToTop = document.getElementById('backToTop');
  window.addEventListener('scroll', function () {
    if (!backToTop) return;
    if (window.scrollY > 400) backToTop.classList.add('show');
    else backToTop.classList.remove('show');
  });
  if (backToTop) {
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

});
