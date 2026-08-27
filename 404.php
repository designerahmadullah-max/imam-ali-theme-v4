<?php get_header(); ?>
<main class="min-h-[70vh] flex flex-col items-center justify-center gap-6 px-6 py-20 bg-cream">
  <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary">404 Error</span>
  <h1 class="font-serif text-[56px] md:text-[72px] text-brown leading-tight text-center">Page Not Found</h1>
  <p class="font-sans text-[16px] text-muted max-w-[480px] text-center leading-relaxed">
    The page you are looking for may have been moved, deleted, or does not exist.
  </p>
  <a href="<?php echo esc_url(home_url('/')); ?>"
     class="mt-4 inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
    Return to Homepage
  </a>
</main>
<?php get_footer(); ?>
