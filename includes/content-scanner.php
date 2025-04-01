<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to scan site content
function scan_site_content() {
    $content_types = get_option('ai_chat_content_types', ['post', 'page']);
    $excluded_categories = explode(',', get_option('ai_chat_excluded_categories', ''));
    $excluded_categories = array_map('trim', $excluded_categories);
    
    $args = [
        'post_type' => $content_types,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    
    $query = new WP_Query($args);
    $site_content = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_categories = wp_get_post_categories(get_the_ID(), ['fields' => 'slugs']);
            
            // Skip excluded categories
            if (array_intersect($excluded_categories, $post_categories)) {
                continue;
            }

            $site_content[] = [
                'title' => get_the_title(),
                'content' => get_the_content(),
                'url' => get_permalink(),
            ];
        }
        wp_reset_postdata();
    }

    // Store the scanned content in a transient for efficiency
    set_transient('ai_chat_site_content', $site_content, HOUR_IN_SECONDS);
}