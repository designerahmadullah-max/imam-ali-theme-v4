<?php get_header(); ?>

<main>
  <!-- Category header -->
  <section class="bg-[#2a1b0f] py-20 px-6 md:px-12 lg:px-20">
    <div class="max-w-[1440px] mx-auto">
      <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 font-sans text-[13px] text-white/40">
          <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-white/70 transition-colors">Home</a></li>
          <li class="text-white/20">›</li>
          <li class="text-white/70"><?php single_cat_title(); ?></li>
        </ol>
      </nav>
      <?php if (is_category()) :
        $cat = get_queried_object(); ?>
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-3 block">Category</span>
      <h1 class="font-serif text-[52px] md:text-[68px] text-white leading-tight mb-3">
        <?php echo esc_html($cat->name); ?>
      </h1>
      <?php if ($cat->description) : ?>
      <p class="font-sans text-[16px] text-white/65 max-w-[600px] leading-relaxed mt-2">
        <?php echo esc_html($cat->description); ?>
      </p>
      <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- Articles grid — same card style as New Articles homepage section -->
  <section class="py-20 px-6 md:px-12 lg:px-20 bg-white">
    <div class="max-w-[1440px] mx-auto">
      <p class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-muted mb-12">
        <?php echo esc_html($wp_query->found_posts); ?> Articles
      </p>
      <?php if (have_posts()) : ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
        <?php while (have_posts()) : the_post();
          $ac = get_the_category();
        ?>
        <article class="flex flex-col group">
          <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-2xl mb-5">
            <img src="<?php echo esc_url(imam_ali_thumb(get_the_ID(), 'imam-ali-card')); ?>"
                 alt="<?php the_title_attribute(); ?>"
                 class="w-full h-[220px] object-cover group-hover:scale-[1.03] transition-transform duration-500">
          </a>
          <?php if ($ac) : ?>
          <a href="<?php echo esc_url(get_category_link($ac[0]->term_id)); ?>"
             class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors mb-2">
            <?php echo esc_html($ac[0]->name); ?>
          </a>
          <?php endif; ?>
          <h3 class="font-serif text-[26px] leading-tight text-brown mb-1 group-hover:text-primary transition-colors">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <p class="font-sans text-[13px] text-muted leading-relaxed line-clamp-2 mb-2">
            <?php the_excerpt(); ?>
          </p>
          <a href="<?php the_permalink(); ?>"
             class="inline-flex items-center gap-1.5 font-sans font-bold text-[13px] text-brown group-hover:text-primary transition-colors mt-5">
            Read More <svg width="10" height="10" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </a>
        </article>
        <?php endwhile; ?>
      </div>

      <!-- Pagination -->
      <div class="mt-16 flex justify-center">
        <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => '&larr; Previous',
            'next_text' => 'Next &rarr;',
        ]);
        ?>
      </div>

      <?php else : ?>
      <p class="font-sans text-[16px] text-muted">No articles found in this category yet. Check back soon.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
