document.addEventListener('DOMContentLoaded', function () {

  /* ── Mobile nav ─────────────────────────────────────────────── */
  var toggle    = document.getElementById('ia-mobile-toggle');
  var mobileNav = document.getElementById('ia-mobile-nav');
  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () { mobileNav.classList.toggle('hidden'); });
  }

  /* ── Hero slider ─────────────────────────────────────────────── */
  var slides  = document.querySelectorAll('.ia-slide');
  var dots    = document.querySelectorAll('.ia-dot');
  var current = 0;
  var timer;

  function goTo(n) {
    slides[current].classList.remove('opacity-100');
    slides[current].classList.add('opacity-0');
    if (dots[current]) { dots[current].classList.remove('bg-white','w-6'); dots[current].classList.add('bg-white/40','w-2'); }
    current = (n + slides.length) % slides.length;
    slides[current].classList.remove('opacity-0');
    slides[current].classList.add('opacity-100');
    if (dots[current]) { dots[current].classList.remove('bg-white/40','w-2'); dots[current].classList.add('bg-white','w-6'); }
  }

  function startTimer() { timer = setInterval(function(){ goTo(current + 1); }, 5000); }
  function stopTimer()  { clearInterval(timer); }

  if (slides.length > 1) {
    startTimer();
    var heroSection = document.getElementById('ia-hero');
    if (heroSection) {
      heroSection.addEventListener('mouseenter', stopTimer);
      heroSection.addEventListener('mouseleave', startTimer);
    }
    dots.forEach(function(d, i) { d.addEventListener('click', function(){ stopTimer(); goTo(i); startTimer(); }); });
    var prev = document.getElementById('ia-prev');
    var next = document.getElementById('ia-next');
    if (prev) prev.addEventListener('click', function(){ stopTimer(); goTo(current-1); startTimer(); });
    if (next) next.addEventListener('click', function(){ stopTimer(); goTo(current+1); startTimer(); });
  }

  /* ── Newsletter ──────────────────────────────────────────────── */
  window.iaNlSubmit = function(e) {
    e.preventDefault();
    e.target.innerHTML = '<div style="display:flex;align-items:center;gap:8px;color:#068f5f;font-family:Manrope,sans-serif;font-size:14px;font-weight:600;padding:8px 0"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Thank you for subscribing!</div>';
  };

  /* ── Keyboard: Esc closes search ────────────────────────────── */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') iaSearchClose();
  });

});

/* ── Full-screen search overlay ─────────────────────────────── */
function iaSearchOpen() {
  var overlay = document.getElementById('ia-search-overlay');
  if (!overlay) return;
  overlay.style.display = 'flex';
  // Fade in
  requestAnimationFrame(function() {
    requestAnimationFrame(function() {
      overlay.style.opacity = '1';
    });
  });
  var input = document.getElementById('ia-search-input');
  if (input) setTimeout(function(){ input.focus(); }, 100);
}

function iaSearchClose() {
  var overlay = document.getElementById('ia-search-overlay');
  if (!overlay) return;
  overlay.style.opacity = '0';
  setTimeout(function(){ overlay.style.display = 'none'; }, 250);
  var input = document.getElementById('ia-search-input');
  if (input) input.value = '';
}
