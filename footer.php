<footer>

  <!-- ── 5-column link lists (managed from Appearance › Menus) ─── -->
  <div class="bg-cream border-t border-border py-16 px-6 md:px-12 lg:px-20">
    <div class="max-w-[1440px] mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-10">
      <?php
      $footer_cols = [
        ['loc' => 'footer-col-1', 'label' => 'Life &amp; Legacy',        'slug' => 'life-and-legacy'],
        ['loc' => 'footer-col-2', 'label' => 'Ahlul Bayt',               'slug' => 'ahlul-bayt'],
        ['loc' => 'footer-col-3', 'label' => 'Imamate &amp; Ghadir',     'slug' => 'imamate-and-ghadir'],
        ['loc' => 'footer-col-4', 'label' => 'Battles &amp; Leadership', 'slug' => 'battles-and-leadership'],
        ['loc' => 'footer-col-5', 'label' => 'Wisdom',                   'slug' => 'wisdom'],
      ];
      foreach ($footer_cols as $col) :
        $cat = get_category_by_slug($col['slug']);
        $cat_link = $cat ? get_category_link($cat->term_id) : home_url('/');
      ?>
      <div>
        <a href="<?php echo esc_url($cat_link); ?>"
           class="block font-sans font-bold text-[14px] text-brown mb-5 hover:text-primary transition-colors">
          <?php echo $col['label']; ?>
        </a>
        <?php if (has_nav_menu($col['loc'])) : ?>
          <div class="footer-col-nav">
            <?php wp_nav_menu([
              'theme_location' => $col['loc'],
              'container'      => false,
              'items_wrap'     => '<ul class="space-y-2.5">%3$s</ul>',
              'walker'         => new Imam_Ali_Footer_Walker(),
              'depth'          => 1,
              'fallback_cb'    => false,
            ]); ?>
          </div>
        <?php else : ?>
          <p class="font-sans text-[12px] text-muted italic leading-relaxed">
            Go to <strong>Appearance &rarr; Menus</strong> to assign links to <em><?php echo $col['label']; ?></em>.
          </p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── Dark bar ─────────────────────────────────────────────────── -->
  <div class="bg-[#2a1b0f] px-6 md:px-12 lg:px-20 py-14">
    <div class="max-w-[1440px] mx-auto grid lg:grid-cols-[1fr_auto_360px] gap-10 items-stretch">

      <!-- Left: Site identity -->
      <div class="flex flex-col gap-6">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block">
          <?php $logo_url = get_theme_mod('imam_ali_logo_url', ''); ?>
          <?php if ($logo_url) : ?>
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="h-[80px] w-auto object-contain">
          <?php elseif (has_custom_logo()) :
            the_custom_logo();
          else : ?>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <div>
                <div class="font-serif text-[22px] text-white leading-none">Imam Ali</div>
                <div class="font-sans font-bold text-[9px] tracking-[0.8px] uppercase text-white/50 leading-none mt-0.5">Editorial</div>
              </div>
            </div>
          <?php endif; ?>
        </a>

        <a href="mailto:info@imam-ali.info"
           class="font-serif text-[28px] md:text-[32px] text-white/80 hover:text-primary transition-colors leading-none">
          info@imam-Ali.info
        </a>

        <!-- Social links -->
        <div class="flex items-center gap-3">
          <span class="font-sans font-bold text-[10px] tracking-[0.44px] uppercase text-white/40 mr-1">Follow</span>
          <?php
          $socials = [
            ['name'=>'Facebook', 'href'=>'https://facebook.com',  'svg'=>'<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['name'=>'X',        'href'=>'https://x.com',         'svg'=>'<path d="M4 4l16 16M4 20L20 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
            ['name'=>'YouTube',  'href'=>'https://youtube.com',   'svg'=>'<rect x="2" y="5" width="20" height="15" rx="4" stroke="currentColor" stroke-width="1.8"/><path d="M10 9l6 3-6 3V9z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>'],
            ['name'=>'Instagram','href'=>'https://instagram.com', 'svg'=>'<rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>'],
          ];
          foreach ($socials as $s) :
          ?>
          <a href="<?php echo esc_url($s['href']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($s['name']); ?>"
             class="w-9 h-9 rounded-full border border-white/15 flex items-center justify-center text-white/40 hover:text-white hover:border-white/40 transition-all">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24"><?php echo $s['svg']; ?></svg>
          </a>
          <?php endforeach; ?>
        </div>

        <p class="font-sans text-[12px] text-white/25 leading-relaxed max-w-[480px] mt-1">
          &copy; <?php echo date('Y'); ?> Imam Ali Editorial. All rights reserved. An educational platform dedicated to the life, wisdom, and legacy of Imam Ali (A.S.).
        </p>
      </div>

      <!-- Middle: Sister sites with bundled logos -->
      <div class="flex flex-col gap-4 lg:w-[260px] h-full">

        <a href="https://the12thimam.com" target="_blank" rel="noopener noreferrer"
           class="group flex flex-row items-center gap-4 bg-white rounded-xl p-5 flex-1 hover:shadow-[0_4px_20px_rgba(0,0,0,0.15)] transition-all">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/the-12th-imam-logo.png'); ?>"
               alt="The 12th Imam"
               class="h-14 w-14 object-contain shrink-0">
          <div class="flex flex-col gap-1.5 min-w-0">
            <span class="font-serif text-[17px] text-[#1a4a2e] group-hover:text-primary transition-colors leading-tight">The 12th Imam</span>
            <span class="font-sans text-[12px] text-[#3a2a1a] leading-relaxed">His life, occultation, and signs of return.</span>
          </div>
        </a>

        <a href="https://imamhussain.info" target="_blank" rel="noopener noreferrer"
           class="group flex flex-row items-center gap-4 bg-white rounded-xl p-5 flex-1 hover:shadow-[0_4px_20px_rgba(0,0,0,0.15)] transition-all">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/imam-hussain-logo.png'); ?>"
               alt="Imam Hussain"
               class="h-14 w-14 object-contain shrink-0">
          <div class="flex flex-col gap-1.5 min-w-0">
            <span class="font-serif text-[17px] text-[#c0392b] group-hover:text-[#a93226] transition-colors leading-tight">Imam Hussain</span>
            <span class="font-sans text-[12px] text-[#3a2a1a] leading-relaxed">His sacrifice at Karbala — for justice, faith, and truth.</span>
          </div>
        </a>

      </div>

      <!-- Right: Newsletter -->
      <div class="bg-white/6 border border-white/10 rounded-2xl p-7 flex flex-col gap-5">
        <div>
          <h4 class="font-serif text-[26px] text-white mb-1">Stay Informed</h4>
          <p class="font-sans text-[14px] text-white/55 leading-relaxed">
            Receive new articles on the life and wisdom of Imam Ali (A.S.) directly in your inbox.
          </p>
        </div>
        <?php if (function_exists('mc4wp_show_form')) :
          mc4wp_show_form();
        else : ?>
        <form onsubmit="iaNlSubmit(event)" class="flex flex-col gap-3">
          <input type="email" placeholder="Your email address" required
                 class="w-full px-4 py-3 rounded-xl border border-white/15 bg-white/8 font-sans text-[14px] text-white placeholder-white/30 focus:outline-none focus:border-primary/60 transition-colors">
          <button type="submit"
                  class="w-full py-3 rounded-xl bg-primary text-white font-sans font-bold text-[13px] tracking-wide hover:bg-primary-dark transition-colors shadow-[0_4px_16px_rgba(6,143,95,0.35)]">
            Subscribe
          </button>
        </form>
        <?php endif; ?>
      </div>

    </div>
  </div>

</footer>
<?php wp_footer(); ?>
</body>
</html>
