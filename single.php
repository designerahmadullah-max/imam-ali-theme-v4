<?php
defined('ABSPATH') || exit;
get_header();
?>
<main>
  <?php while (have_posts()) : the_post(); ?>

  <!-- Full-width featured image (reduced height vs v3) -->
  <?php if (has_post_thumbnail()) : ?>
  <div class="w-full bg-brown overflow-hidden" style="max-height:calc(70vh - 120px); min-height:280px;">
    <?php the_post_thumbnail('full', [
        'class' => 'w-full h-full object-cover object-center block',
        'style' => 'max-height:calc(70vh - 120px); min-height:280px;',
    ]); ?>
  </div>
  <?php endif; ?>

  <!-- Breadcrumbs (directly under image) -->
  <div class="bg-white border-b border-border px-6 md:px-12 lg:px-20 py-3">
    <div class="max-w-[800px] mx-auto">
      <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1.5 font-sans text-[12px] text-muted">
          <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary transition-colors">Home</a></li>
          <li class="text-border">›</li>
          <?php $cats = get_the_category(); if ($cats) : ?>
          <li>
            <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"
               class="hover:text-primary transition-colors"><?php echo esc_html($cats[0]->name); ?></a>
          </li>
          <li class="text-border">›</li>
          <?php endif; ?>
          <li class="text-brown font-bold truncate max-w-[320px]"><?php the_title(); ?></li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Article content -->
  <div class="py-14 px-6 md:px-12 lg:px-20 bg-white">
    <div class="max-w-[800px] mx-auto">

      <!-- Category label -->
      <?php if ($cats) : ?>
      <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"
         class="inline-block font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-5 hover:text-primary-dark transition-colors">
        <?php echo esc_html($cats[0]->name); ?>
      </a>
      <?php endif; ?>

      <!-- Title -->
      <h1 class="font-serif text-[44px] md:text-[56px] leading-tight text-brown mb-10"><?php the_title(); ?></h1>

      <!-- Content -->
      <div class="ia-content">
        <?php the_content(); ?>
      </div>

      <!-- Tags -->
      <?php $tags = get_the_tags(); if ($tags) : ?>
      <div class="mt-12 pt-8 border-t border-border flex flex-wrap gap-2">
        <?php foreach ($tags as $tag) : ?>
        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
           class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase px-4 py-2 rounded-lg bg-white border border-border text-muted hover:text-primary hover:border-primary transition-all">
          <?php echo esc_html($tag->name); ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Related articles -->
  <?php
  $current_cats = wp_get_post_categories(get_the_ID());
  if (!empty($current_cats)) :
      $related = get_posts([
          'category__in' => $current_cats,
          'post__not_in' => [get_the_ID()],
          'posts_per_page' => 3,
      ]);
      if (!empty($related)) : ?>
  <div class="py-16 px-6 md:px-12 lg:px-20 bg-white border-t border-border">
    <div class="max-w-[1440px] mx-auto">
      <p class="font-sans font-extrabold text-[24px] text-primary tracking-[-0.22px] mb-10">Related Articles</p>
      <div class="grid sm:grid-cols-3 gap-x-8 gap-y-10">
        <?php foreach ($related as $rp) :
          $rc = get_the_category($rp->ID); ?>
        <article class="group flex flex-col">
          <a href="<?php echo esc_url(get_permalink($rp)); ?>" class="block overflow-hidden rounded-2xl mb-5">
            <img src="<?php echo esc_url(imam_ali_thumb($rp->ID,'imam-ali-card')); ?>"
                 alt="<?php echo esc_attr(get_the_title($rp)); ?>"
                 class="w-full h-[200px] object-cover group-hover:scale-[1.03] transition-transform duration-500">
          </a>
          <?php if ($rc) : ?>
          <a href="<?php echo esc_url(get_category_link($rc[0]->term_id)); ?>"
             class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors mb-2">
            <?php echo esc_html($rc[0]->name); ?>
          </a>
          <?php endif; ?>
          <h3 class="font-serif text-[24px] text-brown leading-tight group-hover:text-primary transition-colors">
            <a href="<?php echo esc_url(get_permalink($rp)); ?>"><?php echo esc_html(get_the_title($rp)); ?></a>
          </h3>
          <p class="font-sans text-[13px] text-muted leading-relaxed mt-2"><?php echo esc_html(get_the_excerpt($rp)); ?></p>
          <a href="<?php echo esc_url(get_permalink($rp)); ?>"
             class="inline-flex items-center gap-1.5 font-sans font-bold text-[13px] text-brown group-hover:text-primary transition-colors mt-4">
            Read More <svg width="10" height="10" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; endif; ?>

  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
