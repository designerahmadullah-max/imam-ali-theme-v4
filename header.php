<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class('bg-cream text-brown font-sans'); ?>>
<?php wp_body_open(); ?>

<!-- ── Full-screen search overlay ──────────────────────────────── -->
<div id="ia-search-overlay"
     aria-modal="true" role="dialog"
     class="fixed inset-0 z-[200] flex-col items-center justify-center px-6"
     style="display:none; opacity:0; transition:opacity .25s ease; background:rgba(10,5,0,0.96); backdrop-filter:blur(6px);">
  <button onclick="iaSearchClose()" aria-label="Close search"
          class="absolute top-6 right-6 w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white/70 hover:text-white hover:border-white/50 transition-all">
    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path d="M18 6 6 18M6 6l12 12"/>
    </svg>
  </button>
  <div class="w-full max-w-[680px]">
    <p class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-6 text-center">Search Articles</p>
    <div class="relative flex items-center border-b-2 border-white/30 pb-3" id="ia-search-box-wrap">
      <svg class="shrink-0 text-white/40 mr-4" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <input id="ia-search-input" type="search" placeholder="Type to search..."
             class="flex-1 bg-transparent border-none outline-none font-serif text-[32px] md:text-[40px] text-white placeholder-white/25 py-2"
             onkeydown="if(event.key==='Enter'&&this.value.trim()){ window.location.href='<?php echo esc_url(home_url('/')); ?>?s='+encodeURIComponent(this.value.trim()); }">
    </div>
    <p class="font-sans text-[13px] text-white/30 mt-5 text-center">Press Enter to search &nbsp;&middot;&nbsp; Esc to close</p>
  </div>
</div>

<header id="ia-header" class="sticky top-0 z-50 bg-[#2a1b0f]">
  <div class="max-w-[1440px] mx-auto px-6 md:px-12 lg:px-20">
    <div class="flex items-center h-[80px] gap-8">

      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0 flex items-center">
        <?php $logo_url = get_theme_mod('imam_ali_logo_url', ''); ?>
        <?php if ($logo_url) : ?>
          <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="h-[64px] w-auto object-contain">
        <?php elseif (has_custom_logo()) :
          the_custom_logo();
        else : ?>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shrink-0">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <div class="font-serif text-[22px] text-white leading-none">Imam Ali</div>
              <div class="font-sans font-bold text-[9px] tracking-[0.8px] uppercase text-white/50 leading-none mt-0.5">Editorial</div>
            </div>
          </div>
        <?php endif; ?>
      </a>

      <!-- Desktop nav: chapter style -->
      <?php $cats = imam_ali_get_categories(); $num = 1; ?>
      <nav class="hidden lg:flex items-stretch flex-1 justify-center h-full gap-2" aria-label="Primary">
        <?php foreach ($cats as $cat) :
          $active  = is_category($cat->term_id);
          $chapter = str_pad($num++, 2, '0', STR_PAD_LEFT);
        ?>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
           class="group relative flex flex-col justify-center items-start px-5 h-full border-b-[3px] transition-colors duration-200
                  <?php echo $active
                    ? 'border-primary'
                    : 'border-transparent hover:border-primary/60'; ?>">
          <span class="font-sans font-bold text-[9.5px] tracking-[0.8px] uppercase leading-none mb-1.5
                       <?php echo $active ? 'text-primary' : 'text-white/35 group-hover:text-primary/80'; ?> transition-colors">
            CHAPTER <?php echo $chapter; ?>
          </span>
          <span class="font-serif text-[15px] leading-none whitespace-nowrap
                       <?php echo $active ? 'text-white' : 'text-white/75 group-hover:text-white'; ?> transition-colors">
            <?php echo esc_html($cat->name); ?>
          </span>
        </a>
        <?php endforeach; ?>
      </nav>

      <!-- Right controls -->
      <div class="flex items-center gap-1 ml-auto">
        <button onclick="iaSearchOpen()" class="p-2.5 text-white/60 hover:text-white transition-colors" aria-label="Search">
          <svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
        </button>
        <button id="ia-mobile-toggle" class="lg:hidden p-2.5 text-white/60 hover:text-white" aria-label="Menu">
          <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
      </div>

    </div>

    <!-- Mobile nav -->
    <div id="ia-mobile-nav" class="hidden lg:hidden border-t border-white/10 py-4">
      <nav class="flex flex-col gap-1">
        <?php $n = 1; foreach ($cats as $cat) :
          $ch = str_pad($n++, 2, '0', STR_PAD_LEFT);
          $ac = is_category($cat->term_id);
        ?>
          <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
             class="flex flex-col px-4 py-3 rounded-xl <?php echo $ac ? 'bg-white/10' : 'hover:bg-white/5'; ?> transition-colors">
            <span class="font-sans font-bold text-[10px] tracking-[0.8px] uppercase text-primary mb-0.5">CHAPTER <?php echo $ch; ?></span>
            <span class="font-serif text-[17px] text-white"><?php echo esc_html($cat->name); ?></span>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>

  </div>
</header>
