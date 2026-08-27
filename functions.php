<?php
defined('ABSPATH') || exit;

/* ── Theme setup ─────────────────────────────────────────────── */
function imam_ali_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', ['height' => 120, 'width' => 280, 'flex-height' => true, 'flex-width' => true]);

    register_nav_menus([
        'primary'      => __('Primary Menu', 'imam-ali'),
        'footer-col-1' => __('Footer Column 1 — Life & Legacy', 'imam-ali'),
        'footer-col-2' => __('Footer Column 2 — Ahlul Bayt', 'imam-ali'),
        'footer-col-3' => __('Footer Column 3 — Imamate & Ghadir', 'imam-ali'),
        'footer-col-4' => __('Footer Column 4 — Battles & Leadership', 'imam-ali'),
        'footer-col-5' => __('Footer Column 5 — Wisdom', 'imam-ali'),
    ]);

    add_image_size('imam-ali-featured',  1600, 900, true);
    add_image_size('imam-ali-card',      900,  600, true);
    add_image_size('imam-ali-thumbnail', 400,  300, true);
}
add_action('after_setup_theme', 'imam_ali_setup');

/* ── Enqueue assets ──────────────────────────────────────────── */
function imam_ali_enqueue() {
    wp_enqueue_style('imam-ali-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&family=DM+Serif+Display&display=swap',
        [], null);

    wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);

    wp_add_inline_script('tailwind-cdn', '
        tailwind.config = {
            theme: { extend: {
                colors: {
                    primary:       "#068f5f",
                    "primary-dark":"#056b47",
                    brown:         "#2a1b0f",
                    cream:         "#f5f0e8",
                    muted:         "#6b5a47",
                    border:        "#d9cec0",
                    sand:          "#c8a96e",
                },
                fontFamily: {
                    serif: ["DM Serif Display","Georgia","serif"],
                    sans:  ["Manrope","system-ui","sans-serif"],
                },
            }}
        }
    ');

    wp_enqueue_style('imam-ali-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['imam-ali-fonts'], '4.0.0');

    wp_enqueue_script('imam-ali-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [], '4.0.0', true);

    if (is_singular() && comments_open()) wp_enqueue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'imam_ali_enqueue');

/* ── Helpers ─────────────────────────────────────────────────── */
function imam_ali_reading_time(int $post_id = 0): int {
    $words = str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id ?: get_the_ID())));
    return max(1, (int) round($words / 200));
}
add_filter('excerpt_length', fn() => 30);
add_filter('excerpt_more',   fn() => '…');

function imam_ali_get_categories(): array {
    return get_categories([
        'orderby'    => 'term_order',
        'order'      => 'ASC',
        'hide_empty' => false,
        'exclude'    => get_option('default_category'),
    ]);
}

function imam_ali_thumb(int $post_id, string $size = 'imam-ali-featured'): string {
    $url = get_the_post_thumbnail_url($post_id, $size);
    return $url ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';
}

/* ── Customizer: site logo ───────────────────────────────────── */
function imam_ali_customizer(WP_Customize_Manager $wp_customize): void {
    $wp_customize->add_setting('imam_ali_logo_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'imam_ali_logo_url', [
        'label'   => 'Site Logo (Header & Footer)',
        'section' => 'title_tagline',
    ]));
}
add_action('customize_register', 'imam_ali_customizer');

/* ── Homepage placement meta box ────────────────────────────── */
function imam_ali_meta_boxes(): void {
    add_meta_box(
        'imam_ali_homepage',
        '🏠 Homepage Placement',
        'imam_ali_homepage_meta_html',
        'post', 'side', 'high'
    );
}
add_action('add_meta_boxes', 'imam_ali_meta_boxes');

function imam_ali_homepage_meta_html(WP_Post $post): void {
    wp_nonce_field('imam_ali_hp_nonce', 'imam_ali_hp_nonce');
    $is_hero      = get_post_meta($post->ID, '_ia_hero',              true);
    $is_main      = get_post_meta($post->ID, '_ia_featured_main',     true);
    $is_secondary = get_post_meta($post->ID, '_ia_featured_secondary',true);
    ?>
    <div style="font-family:sans-serif;font-size:13px;line-height:1.7;">

      <label style="display:flex;align-items:flex-start;gap:8px;margin-bottom:14px;cursor:pointer;padding:10px;background:#f0f8f4;border-radius:8px;border:1px solid #b7e4cf;">
        <input type="checkbox" name="ia_hero" value="1" <?php checked($is_hero,'1'); ?> style="margin-top:3px;accent-color:#068f5f;">
        <span>
          <strong style="color:#2a1b0f;">Hero Section</strong><br>
          <span style="color:#888;font-size:12px;">Shows in the hero banner. Select multiple to enable the slider.</span>
        </span>
      </label>

      <label style="display:flex;align-items:flex-start;gap:8px;margin-bottom:14px;cursor:pointer;padding:10px;background:#fafafa;border-radius:8px;border:1px solid #e0dbd4;">
        <input type="checkbox" name="ia_featured_main" value="1" <?php checked($is_main,'1'); ?> style="margin-top:3px;accent-color:#068f5f;">
        <span>
          <strong style="color:#2a1b0f;">Main Featured Article</strong><br>
          <span style="color:#888;font-size:12px;">Large card on the left. Only one article at a time.</span>
        </span>
      </label>

      <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;padding:10px;background:#fafafa;border-radius:8px;border:1px solid #e0dbd4;">
        <input type="checkbox" name="ia_featured_secondary" value="1" <?php checked($is_secondary,'1'); ?> style="margin-top:3px;accent-color:#068f5f;">
        <span>
          <strong style="color:#2a1b0f;">Secondary Featured</strong><br>
          <span style="color:#888;font-size:12px;">Smaller cards (middle & right columns). Up to 8.</span>
        </span>
      </label>

    </div>
    <?php
}

function imam_ali_save_meta(int $post_id): void {
    if (!isset($_POST['imam_ali_hp_nonce']) ||
        !wp_verify_nonce($_POST['imam_ali_hp_nonce'], 'imam_ali_hp_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Hero
    if (!empty($_POST['ia_hero'])) {
        update_post_meta($post_id, '_ia_hero', '1');
    } else {
        delete_post_meta($post_id, '_ia_hero');
    }

    // Main featured — enforce unique
    if (!empty($_POST['ia_featured_main'])) {
        foreach (get_posts([
            'posts_per_page' => -1,
            'meta_key'       => '_ia_featured_main',
            'meta_value'     => '1',
            'post__not_in'   => [$post_id],
        ]) as $p) {
            delete_post_meta($p->ID, '_ia_featured_main');
        }
        update_post_meta($post_id, '_ia_featured_main', '1');
    } else {
        delete_post_meta($post_id, '_ia_featured_main');
    }

    // Secondary featured
    if (!empty($_POST['ia_featured_secondary'])) {
        update_post_meta($post_id, '_ia_featured_secondary', '1');
    } else {
        delete_post_meta($post_id, '_ia_featured_secondary');
    }
}
add_action('save_post', 'imam_ali_save_meta');

/* ── Footer nav menu helper ─────────────────────────────────── */
function imam_ali_footer_menu(string $location, string $fallback_heading): void {
    if (has_nav_menu($location)) {
        wp_nav_menu([
            'theme_location' => $location,
            'container'      => false,
            'items_wrap'     => '<ul class="space-y-2.5">%3$s</ul>',
            'walker'         => new Imam_Ali_Footer_Walker(),
            'depth'          => 1,
            'fallback_cb'    => false,
        ]);
    } else {
        echo '<p style="font-size:12px;color:#6b5a47;font-family:Manrope,sans-serif;">No menu assigned. Go to <strong>Appearance &rarr; Menus</strong> and assign a menu to <em>' . esc_html($fallback_heading) . '</em>.</p>';
    }
}

/* ── Custom Walker for footer nav items ─────────────────────── */
class Imam_Ali_Footer_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        $output .= '<li class="flex items-center gap-1.5 group/item">';
        $output .= '<svg class="shrink-0 text-primary opacity-50 group-hover/item:opacity-100 transition-opacity" width="7" height="7" fill="none" viewBox="0 0 7 12"><path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        $output .= '<a href="' . esc_url($data_object->url) . '" class="font-sans text-[13px] text-[#4a3828] group-hover/item:text-primary transition-colors leading-snug">' . esc_html($data_object->title) . '</a>';
        $output .= '</li>';
    }
    public function end_el(&$output, $data_object, $depth = 0, $args = null) {}
}
