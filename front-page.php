<?php
defined('ABSPATH') || exit;

/* ── Data queries ─────────────────────────────────────────────── */
$site_cats = imam_ali_get_categories();

// Hero — posts marked with _ia_hero checkbox
$hero_posts = get_posts(['posts_per_page' => 5, 'meta_key' => '_ia_hero', 'meta_value' => '1']);
// Fallback: latest sticky or latest post
if (empty($hero_posts)) {
    $sticky = get_option('sticky_posts');
    if (!empty($sticky)) $hero_posts = get_posts(['post__in' => $sticky, 'posts_per_page' => 1]);
    if (empty($hero_posts)) $hero_posts = get_posts(['posts_per_page' => 1]);
}

// Life & Legacy
$life_posts = get_posts(['posts_per_page' => 1, 'category_name' => 'life-and-legacy']);
$life_post  = $life_posts[0] ?? null;

// Featured
$feat_main_q = get_posts(['posts_per_page' => 1, 'meta_key' => '_ia_featured_main', 'meta_value' => '1']);
$feat_main   = $feat_main_q[0] ?? null;
$feat_sec    = get_posts(['posts_per_page' => 8, 'meta_key' => '_ia_featured_secondary', 'meta_value' => '1', 'post__not_in' => $feat_main ? [$feat_main->ID] : []]);
$feat_mid    = array_slice($feat_sec, 0, 4);
$feat_right  = array_slice($feat_sec, 4, 4);

// Newest per category (deduplicated)
$seen_ids = [];
$newest_per_cat = [];
foreach ($site_cats as $cat) {
    $q = get_posts(['posts_per_page' => 5, 'category' => $cat->term_id, 'exclude' => $seen_ids]);
    if (!empty($q)) { $newest_per_cat[] = ['cat' => $cat, 'post' => $q[0]]; $seen_ids[] = $q[0]->ID; }
}

// Section articles
$ghadir_posts   = get_posts(['posts_per_page' => 1, 'category_name' => 'imamate-and-ghadir']);
$ghadir_post    = $ghadir_posts[0] ?? null;
$shrine_posts   = get_posts(['posts_per_page' => 2, 'category_name' => 'shrine-and-ziyarat']);
$nahj_posts     = get_posts(['posts_per_page' => 1, 'category_name' => 'wisdom']);
$nahj_post      = $nahj_posts[0] ?? null;
$marriage_posts = get_posts(['posts_per_page' => 2, 'category_name' => 'ahlul-bayt']);
$marriage_post  = $marriage_posts[1] ?? ($marriage_posts[0] ?? null);

$famous_titles = [
  ['num'=>'01','ar'=>'أمير المؤمنين','en'=>'Commander of the Faithful'],
  ['num'=>'02','ar'=>'أسد الله','en'=>'Lion of God'],
  ['num'=>'03','ar'=>'المرتضى','en'=>'The Chosen One'],
  ['num'=>'04','ar'=>'الوصي','en'=>'The Trustee'],
  ['num'=>'05','ar'=>'باب مدينة العلم','en'=>'Gate of the City of Knowledge'],
  ['num'=>'06','ar'=>'يعسوب الدين','en'=>'Leader of the Faith'],
  ['num'=>'07','ar'=>'الصديق الأكبر','en'=>'The Greatest Truthful'],
  ['num'=>'08','ar'=>'الفاروق الأعظم','en'=>'The Greatest Distinguisher'],
  ['num'=>'09','ar'=>'الحيدر','en'=>'The Lion'],
  ['num'=>'10','ar'=>'سيف الله','en'=>'Sword of God'],
  ['num'=>'11','ar'=>'الولي','en'=>'The Guardian'],
  ['num'=>'12','ar'=>'الكرار','en'=>'The One Who Charges Repeatedly'],
  ['num'=>'13','ar'=>'الفاروق','en'=>'The Distinguisher of Truth'],
  ['num'=>'14','ar'=>'مولى المؤمنين','en'=>'Master of the Believers'],
];

$principles = [
  ['title'=>'Justice for All','desc'=>'Imam Ali upheld justice as the highest virtue — equally for friend and foe.'],
  ['title'=>'Knowledge & Wisdom','desc'=>'The Gate of the City of Knowledge, whose wisdom still guides millions today.'],
  ['title'=>'Courage in Battle','desc'=>'Unmatched valor on every battlefield, yet never striking the vulnerable.'],
  ['title'=>'Compassion for the Poor','desc'=>'He would personally deliver food at night to widows and orphans in Kufa.'],
  ['title'=>'Unwavering Faith','desc'=>'His devotion to God never wavered — in prosperity and in trial.'],
  ['title'=>'Eloquence & Oratory','desc'=>'Nahj al-Balagha stands as one of the greatest works of Arabic literature.'],
  ['title'=>'Equality of Humanity','desc'=>'He saw no distinction between Arab and non-Arab, noble and commoner.'],
  ['title'=>'Self-Discipline','desc'=>'He lived simply, refusing the comforts of his position as Caliph.'],
  ['title'=>'Protecting the Weak','desc'=>'He considered it his divine duty to stand for those who had no voice.'],
  ['title'=>'Love for Knowledge','desc'=>'He urged his followers to seek knowledge from cradle to grave.'],
];

$quotes = [
  '"Your remedy is within you, but you do not perceive it."',
  '"The tongue is a beast; if it is let loose, it devours."',
  '"Do not be ashamed of giving little, for refusal is smaller still."',
];

get_header();
?>
<main>

<!-- ── HERO SLIDER ──────────────────────────────────────────────── -->
<?php if (!empty($hero_posts)) :
  $is_slider = count($hero_posts) > 1;
?>
<section id="ia-hero" class="relative overflow-hidden <?php echo $is_slider ? 'h-[88vh]' : ''; ?>">

  <!-- Mobile (single hero only) -->
  <?php if (!$is_slider) : $hp = $hero_posts[0]; ?>
  <div class="lg:hidden bg-[#f0e8d8]">
    <div class="px-6 py-12">
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Featured Story</span>
      <h1 class="font-serif text-[44px] leading-tight text-brown mb-5">
        <a href="<?php echo esc_url(get_permalink($hp)); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($hp)); ?></a>
      </h1>
      <p class="font-sans text-[15px] text-muted leading-relaxed mb-7 max-w-[480px]"><?php echo esc_html(get_the_excerpt($hp)); ?></p>
      <a href="<?php echo esc_url(get_permalink($hp)); ?>"
         class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-7 py-3.5 rounded-xl hover:bg-primary-dark transition-colors">
        Read Article <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
    </div>
    <img src="<?php echo esc_url(imam_ali_thumb($hp->ID,'imam-ali-featured')); ?>" alt="<?php echo esc_attr(get_the_title($hp)); ?>" class="w-full h-[320px] object-contain object-center">
  </div>
  <?php endif; ?>

  <!-- Desktop slides -->
  <div class="<?php echo $is_slider ? 'block' : 'hidden lg:block'; ?> relative h-[88vh] min-h-[600px]">
    <?php foreach ($hero_posts as $i => $hp) : ?>
    <div class="ia-slide absolute inset-0 transition-opacity duration-700 <?php echo $i === 0 ? 'opacity-100' : 'opacity-0'; ?>">
      <img src="<?php echo esc_url(imam_ali_thumb($hp->ID,'imam-ali-featured')); ?>"
           alt="<?php echo esc_attr(get_the_title($hp)); ?>"
           class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-r from-[#2a1b0f]/85 via-[#2a1b0f]/55 to-transparent"></div>
      <div class="relative z-10 max-w-[1440px] mx-auto px-6 md:px-20 h-full flex flex-col justify-end pb-20">
        <?php $hc = get_the_category($hp->ID); ?>
        <?php if ($hc) : ?>
        <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block"><?php echo esc_html($hc[0]->name); ?></span>
        <?php endif; ?>
        <h1 class="font-serif text-[56px] lg:text-[72px] leading-tight text-white mb-5 max-w-[720px]">
          <a href="<?php echo esc_url(get_permalink($hp)); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($hp)); ?></a>
        </h1>
        <p class="font-sans text-[17px] text-white/70 leading-relaxed mb-8 max-w-[540px]"><?php echo esc_html(get_the_excerpt($hp)); ?></p>
        <div class="flex items-center gap-6">
          <a href="<?php echo esc_url(get_permalink($hp)); ?>"
             class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors shadow-[0_4px_20px_rgba(6,143,95,0.4)]">
            Read Article <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </a>
          <span class="font-sans text-[13px] text-white/50"><?php echo imam_ali_reading_time($hp->ID); ?> min read</span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if ($is_slider) : ?>
    <!-- Slider controls -->
    <button id="ia-prev" class="absolute left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white transition-all">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
    </button>
    <button id="ia-next" class="absolute right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white transition-all">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    </button>
    <!-- Dots -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
      <?php foreach ($hero_posts as $i => $hp) : ?>
      <button class="ia-dot h-2 rounded-full transition-all duration-300 <?php echo $i === 0 ? 'bg-white w-6' : 'bg-white/40 w-2'; ?>"></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

</section>
<?php endif; ?>

<!-- ── CATEGORIES (4 featured — exact text from design) ──────────── -->
<?php
$hero_cats = [
    'life-and-legacy'        => [
        'name' => 'Life &amp; Legacy',
        'desc' => 'The biography, early life, character, and lasting legacy of Imam Ali (A.S.) — the Lion of God.',
    ],
    'ahlul-bayt'             => [
        'name' => 'Ahlul Bayt',
        'desc' => 'The blessed household of the Prophet — their lives, sacrifices, and enduring spiritual authority.',
    ],
    'imamate-and-ghadir'     => [
        'name' => 'Imamate &amp; Ghadir',
        'desc' => 'The divine appointment of Imam Ali at Ghadir Khumm and the doctrine of Imamate in Islam.',
    ],
    'battles-and-leadership' => [
        'name' => 'Battles &amp; Leadership',
        'desc' => 'The military campaigns, governance, and leadership of Imam Ali (A.S.) across Islamic history.',
    ],
];
?>
<section class="py-16 px-6 md:px-12 lg:px-20 bg-cream">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <?php foreach ($hero_cats as $slug => $info) :
      $wcat = get_category_by_slug($slug);
      $link = $wcat ? get_category_link($wcat->term_id) : home_url('/');
    ?>
    <a href="<?php echo esc_url($link); ?>"
       class="group flex flex-col gap-4 bg-white rounded-2xl border border-[#ede8e0] p-7 shadow-[0_2px_12px_rgba(42,27,15,0.04)] hover:shadow-[0_10px_36px_rgba(42,27,15,0.12)] hover:border-primary/40 transition-all duration-300">
      <h3 class="font-serif text-[24px] text-brown leading-tight group-hover:text-primary transition-colors"><?php echo $info['name']; ?></h3>
      <p class="font-sans text-[13px] text-muted leading-relaxed flex-1"><?php echo esc_html($info['desc']); ?></p>
      <div class="flex items-center gap-1.5 font-sans font-bold text-[13px] text-primary mt-1 group-hover:gap-3 transition-all">
        Explore <svg width="10" height="10" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ── LIFE & LEGACY ─────────────────────────────────────────────── -->
<?php if ($life_post) : ?>
<section class="pt-20 pb-8 px-6 md:px-12 lg:px-20 bg-white">
  <div class="max-w-[1440px] mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div>
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Life & Legacy</span>
      <h2 class="font-serif text-[44px] lg:text-[52px] text-brown leading-tight mb-6">
        <a href="<?php echo esc_url(get_permalink($life_post)); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($life_post)); ?></a>
      </h2>
      <p class="font-sans text-[16px] text-muted leading-relaxed mb-8"><?php echo esc_html(get_the_excerpt($life_post)); ?></p>
      <a href="<?php echo esc_url(get_permalink($life_post)); ?>"
         class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
        Read His Full Biography <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
    </div>
    <img src="<?php echo esc_url(imam_ali_thumb($life_post->ID,'imam-ali-featured')); ?>" alt="<?php echo esc_attr(get_the_title($life_post)); ?>" class="w-full h-[420px] object-cover rounded-2xl">
  </div>
</section>
<?php endif; ?>

<!-- ── FAMOUS TITLES ─────────────────────────────────────────────── -->
<section class="pt-8 pb-14 px-6 md:px-12 lg:px-20 bg-white">
  <div class="max-w-[1440px] mx-auto">
    <div class="flex items-center gap-6 mb-10">
      <div class="flex-1 h-px bg-[#836f5d]/30"></div>
      <h2 class="font-sans font-bold text-[22px] text-brown tracking-[-0.22px] whitespace-nowrap">Some of Imam Ali's Most Famous Titles</h2>
      <div class="flex-1 h-px bg-[#836f5d]/30"></div>
    </div>
    <div class="marquee-track overflow-hidden" style="mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent);-webkit-mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent)">
      <div class="flex w-max animate-marquee">
        <?php foreach (array_merge($famous_titles,$famous_titles) as $t) : ?>
        <div class="flex flex-col gap-1.5 shrink-0 w-[220px] px-6 border-l border-border first:border-l-0">
          <span class="font-sans font-bold text-[13px] tracking-[1px] text-primary"><?php echo esc_html($t['num']); ?></span>
          <span class="font-serif text-[22px] leading-tight text-brown"><?php echo esc_html($t['ar']); ?></span>
          <span class="font-sans text-[13px] leading-snug text-muted"><?php echo esc_html($t['en']); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ── FEATURED ARTICLES ─────────────────────────────────────────── -->
<?php if ($feat_main || !empty($feat_mid)) : ?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-cream">
  <div class="max-w-[1440px] mx-auto">
    <p class="font-sans font-extrabold text-primary text-[24px] tracking-[-0.22px] mb-12">FEATURED ARTICLES</p>
    <div class="grid lg:grid-cols-3 gap-8 lg:items-stretch">

      <!-- Left large card -->
      <?php if ($feat_main) : $fc = get_the_category($feat_main->ID); ?>
      <div class="bg-white rounded-2xl overflow-hidden flex flex-col">
        <a href="<?php echo esc_url(get_permalink($feat_main)); ?>" class="block overflow-hidden shrink-0">
          <img src="<?php echo esc_url(imam_ali_thumb($feat_main->ID,'imam-ali-card')); ?>" alt="<?php echo esc_attr(get_the_title($feat_main)); ?>" class="w-full h-[300px] object-cover hover:scale-[1.03] transition-transform duration-500">
        </a>
        <div class="p-8 flex flex-col flex-1 gap-4">
          <?php if ($fc) : ?>
          <a href="<?php echo esc_url(get_category_link($fc[0]->term_id)); ?>" class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors"><?php echo esc_html($fc[0]->name); ?></a>
          <?php endif; ?>
          <h3 class="font-serif text-[32px] leading-tight text-brown hover:text-primary transition-colors">
            <a href="<?php echo esc_url(get_permalink($feat_main)); ?>"><?php echo esc_html(get_the_title($feat_main)); ?></a>
          </h3>
          <p class="font-sans text-[14px] text-muted leading-relaxed line-clamp-2"><?php echo esc_html(get_the_excerpt($feat_main)); ?></p>
          <span class="font-sans text-[12px] text-muted mt-auto"><?php echo imam_ali_reading_time($feat_main->ID); ?> min read</span>
        </div>
      </div>
      <?php endif; ?>

      <!-- Middle column -->
      <div class="flex flex-col divide-y divide-border">
        <?php foreach ($feat_mid as $post) : $mc = get_the_category($post->ID); ?>
        <article class="flex gap-4 py-5 group first:pt-0">
          <a href="<?php echo esc_url(get_permalink($post)); ?>" class="shrink-0 rounded-xl overflow-hidden">
            <img src="<?php echo esc_url(imam_ali_thumb($post->ID,'imam-ali-thumbnail')); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" class="w-[140px] h-[110px] object-cover group-hover:scale-105 transition-transform duration-400">
          </a>
          <div class="flex flex-col justify-center gap-2">
            <?php if ($mc) : ?>
            <a href="<?php echo esc_url(get_category_link($mc[0]->term_id)); ?>" class="font-sans font-bold text-[10px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors"><?php echo esc_html($mc[0]->name); ?></a>
            <?php endif; ?>
            <h3 class="font-serif text-[22px] leading-snug text-brown group-hover:text-primary transition-colors">
              <a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a>
            </h3>
            <span class="font-sans text-[11px] text-muted"><?php echo imam_ali_reading_time($post->ID); ?> min read</span>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <!-- Right column -->
      <div class="flex flex-col divide-y divide-border">
        <?php foreach ($feat_right as $post) : $rc = get_the_category($post->ID); ?>
        <article class="flex gap-4 py-5 group first:pt-0">
          <a href="<?php echo esc_url(get_permalink($post)); ?>" class="shrink-0 rounded-xl overflow-hidden">
            <img src="<?php echo esc_url(imam_ali_thumb($post->ID,'imam-ali-thumbnail')); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" class="w-[140px] h-[110px] object-cover group-hover:scale-105 transition-transform duration-400">
          </a>
          <div class="flex flex-col justify-center gap-2">
            <?php if ($rc) : ?>
            <a href="<?php echo esc_url(get_category_link($rc[0]->term_id)); ?>" class="font-sans font-bold text-[10px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors"><?php echo esc_html($rc[0]->name); ?></a>
            <?php endif; ?>
            <h3 class="font-serif text-[22px] leading-snug text-brown group-hover:text-primary transition-colors">
              <a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a>
            </h3>
            <span class="font-sans text-[11px] text-muted"><?php echo imam_ali_reading_time($post->ID); ?> min read</span>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── NEW ARTICLES ───────────────────────────────────────────────── -->
<?php if (!empty($newest_per_cat)) : ?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-white">
  <div class="max-w-[1440px] mx-auto">
    <p class="font-sans font-extrabold text-primary text-[24px] tracking-[-0.22px] mb-12">NEW ARTICLES</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
      <?php foreach ($newest_per_cat as $item) : $post = $item['post']; $cat = $item['cat']; ?>
      <article class="flex flex-col group">
        <a href="<?php echo esc_url(get_permalink($post)); ?>" class="block overflow-hidden rounded-2xl mb-5">
          <img src="<?php echo esc_url(imam_ali_thumb($post->ID,'imam-ali-card')); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" class="w-full h-[220px] object-cover group-hover:scale-[1.03] transition-transform duration-500">
        </a>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary hover:text-primary-dark transition-colors mb-2"><?php echo esc_html($cat->name); ?></a>
        <h3 class="font-serif text-[26px] leading-tight text-brown mb-3 group-hover:text-primary transition-colors">
          <a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a>
        </h3>
        <p class="font-sans text-[14px] text-muted leading-relaxed line-clamp-2 mb-4"><?php echo esc_html(get_the_excerpt($post)); ?></p>
        <a href="<?php echo esc_url(get_permalink($post)); ?>" class="inline-flex items-center gap-1.5 font-sans font-bold text-[13px] text-brown group-hover:text-primary transition-colors mt-auto">
          Read More <svg width="10" height="10" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── GHADIR ────────────────────────────────────────────────────── -->
<?php if ($ghadir_post) : ?>
<section class="relative overflow-hidden min-h-[480px] flex items-center">
  <img src="<?php echo esc_url(imam_ali_thumb($ghadir_post->ID,'imam-ali-featured')); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-r from-[#1a0f05]/90 via-[#1a0f05]/70 to-transparent"></div>
  <div class="relative z-10 px-6 md:px-12 lg:px-20 py-20 max-w-[1440px] mx-auto w-full">
    <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Imamate & Ghadir</span>
    <h2 class="font-serif text-[44px] lg:text-[56px] text-white leading-tight mb-5 max-w-[640px]"><?php echo esc_html(get_the_title($ghadir_post)); ?></h2>
    <p class="font-sans text-[16px] text-white/70 leading-relaxed mb-8 max-w-[480px]"><?php echo esc_html(get_the_excerpt($ghadir_post)); ?></p>
    <a href="<?php echo esc_url(get_permalink($ghadir_post)); ?>"
       class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
      Read Full Story <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </a>
  </div>
</section>
<?php endif; ?>

<!-- ── QUOTES ────────────────────────────────────────────────────── -->
<section class="py-20 px-6 md:px-12 lg:px-20 bg-primary">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">
    <?php foreach ($quotes as $q) : ?>
    <blockquote class="text-white/90 text-[22px] leading-relaxed italic" style="font-family:Georgia,serif;"><?php echo esc_html($q); ?></blockquote>
    <?php endforeach; ?>
  </div>
</section>

<!-- ── SHRINE ────────────────────────────────────────────────────── -->
<?php if (!empty($shrine_posts)) : ?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-white">
  <div class="max-w-[1440px] mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div>
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Shrine &amp; Ziyarat</span>
      <h2 class="font-serif text-[40px] lg:text-[48px] text-brown leading-tight mb-5">
        <a href="<?php echo esc_url(get_permalink($shrine_posts[0])); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($shrine_posts[0])); ?></a>
      </h2>
      <p class="font-sans text-[15px] text-muted leading-relaxed mb-8"><?php echo esc_html(get_the_excerpt($shrine_posts[0])); ?></p>
      <a href="<?php echo esc_url(get_permalink($shrine_posts[0])); ?>"
         class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
        Explore the Shrine <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
    </div>
    <!-- Single full-coverage image -->
    <div class="overflow-hidden rounded-2xl">
      <img src="<?php echo esc_url(imam_ali_thumb($shrine_posts[0]->ID,'imam-ali-featured')); ?>"
           alt="<?php echo esc_attr(get_the_title($shrine_posts[0])); ?>"
           class="w-full h-[480px] object-cover">
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── WHY INSPIRES (with icons) ─────────────────────────────────── -->
<?php
$principles_icons = [
  ['title'=>'Justice for All',       'desc'=>'Imam Ali upheld justice as the highest virtue — equally for friend and foe.',
   'icon'=>'<path d="M12 3v1m0 16v1M4.22 4.22l.7.7m12.16 12.16.7.7M3 12h1m16 0h1M4.22 19.78l.7-.7M18.36 5.64l.7-.7M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
  ['title'=>'Knowledge & Wisdom',    'desc'=>'The Gate of the City of Knowledge, whose wisdom still guides millions today.',
   'icon'=>'<path d="M12 2a7 7 0 0 1 7 7c0 3.5-2 6-4 7.5V18H9v-1.5C7 15 5 12.5 5 9a7 7 0 0 1 7-7z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 22h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
  ['title'=>'Courage in Battle',     'desc'=>'Unmatched valor on every battlefield, yet never striking the vulnerable.',
   'icon'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
  ['title'=>'Compassion for the Poor','desc'=>'He would personally deliver food at night to widows and orphans in Kufa.',
   'icon'=>'<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
  ['title'=>'Unwavering Faith',      'desc'=>'His devotion to God never wavered — in prosperity and in trial.',
   'icon'=>'<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
  ['title'=>'Eloquence & Oratory',   'desc'=>'Nahj al-Balagha stands as one of the greatest works of Arabic literature.',
   'icon'=>'<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
  ['title'=>'Equality of Humanity',  'desc'=>'He saw no distinction between Arab and non-Arab, noble and commoner.',
   'icon'=>'<circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M1 21v-2a7 7 0 0 1 7-7h8a7 7 0 0 1 7 7v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
  ['title'=>'Self-Discipline',        'desc'=>'He lived simply, refusing the comforts of his position as Caliph.',
   'icon'=>'<path d="m3 17 4-8 4 4 4-6 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 21h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
  ['title'=>'Protecting the Weak',   'desc'=>'He considered it his divine duty to stand for those who had no voice.',
   'icon'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
  ['title'=>'Love for Knowledge',    'desc'=>'He urged his followers to seek knowledge from cradle to grave.',
   'icon'=>'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
];
?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-cream">
  <div class="max-w-[1440px] mx-auto">
    <h2 class="font-serif text-[40px] lg:text-[46px] text-brown text-center leading-tight mb-14">Why Imam Ali Still Inspires the World Today</h2>
    <div class="marquee-track overflow-hidden" style="mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent);-webkit-mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent)">
      <div class="flex w-max animate-marquee-slow">
        <?php foreach (array_merge($principles_icons, $principles_icons) as $p) : ?>
        <div class="flex flex-col gap-4 shrink-0 w-[270px] mx-3 bg-white rounded-2xl p-7 shadow-[0_2px_16px_rgba(42,27,15,0.06)]">
          <!-- Icon -->
          <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" class="text-primary">
              <?php echo $p['icon']; ?>
            </svg>
          </div>
          <h3 class="font-serif text-[20px] text-brown leading-tight"><?php echo esc_html($p['title']); ?></h3>
          <p class="font-sans text-[13px] text-muted leading-relaxed"><?php echo esc_html($p['desc']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ── NAHJ ──────────────────────────────────────────────────────── -->
<?php if ($nahj_post) : ?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-white">
  <div class="max-w-[1440px] mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <img src="<?php echo esc_url(imam_ali_thumb($nahj_post->ID,'imam-ali-featured')); ?>" alt="<?php echo esc_attr(get_the_title($nahj_post)); ?>" class="w-full h-[420px] object-cover rounded-2xl">
    <div>
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Wisdom</span>
      <h2 class="font-serif text-[40px] lg:text-[48px] text-brown leading-tight mb-5">
        <a href="<?php echo esc_url(get_permalink($nahj_post)); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($nahj_post)); ?></a>
      </h2>
      <p class="font-sans text-[15px] text-muted leading-relaxed mb-8"><?php echo esc_html(get_the_excerpt($nahj_post)); ?></p>
      <a href="<?php echo esc_url(get_permalink($nahj_post)); ?>"
         class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
        Explore the Book <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── MARRIAGE ──────────────────────────────────────────────────── -->
<?php if ($marriage_post) : ?>
<section class="py-20 px-6 md:px-12 lg:px-20 bg-cream">
  <div class="max-w-[1440px] mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div>
      <span class="font-sans font-bold text-[11px] tracking-[0.44px] uppercase text-primary mb-4 block">Ahlul Bayt</span>
      <h2 class="font-serif text-[40px] lg:text-[48px] text-brown leading-tight mb-5">
        <a href="<?php echo esc_url(get_permalink($marriage_post)); ?>" class="hover:text-primary transition-colors"><?php echo esc_html(get_the_title($marriage_post)); ?></a>
      </h2>
      <p class="font-sans text-[15px] text-muted leading-relaxed mb-8"><?php echo esc_html(get_the_excerpt($marriage_post)); ?></p>
      <a href="<?php echo esc_url(get_permalink($marriage_post)); ?>"
         class="inline-flex items-center gap-2 bg-primary text-white font-sans font-bold text-[13px] tracking-wide px-8 py-4 rounded-xl hover:bg-primary-dark transition-colors">
        Read Their Story <svg width="12" height="12" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
    </div>
    <img src="<?php echo esc_url(imam_ali_thumb($marriage_post->ID,'imam-ali-featured')); ?>" alt="<?php echo esc_attr(get_the_title($marriage_post)); ?>" class="w-full h-[420px] object-cover rounded-2xl">
  </div>
</section>
<?php endif; ?>

</main>
<?php get_footer(); ?>
