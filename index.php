<?php get_header(); ?>
<main class="py-20 px-6 md:px-12 lg:px-20 bg-cream min-h-[60vh]">
  <div class="max-w-[1440px] mx-auto">
    <?php if (have_posts()) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/content', 'card'); ?>
        <?php endwhile; ?>
      </div>
      <div class="mt-12"><?php the_posts_navigation(); ?></div>
    <?php else : ?>
      <p class="font-sans text-muted text-[16px]">No posts found.</p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
