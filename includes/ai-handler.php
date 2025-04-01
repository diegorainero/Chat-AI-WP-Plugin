<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function query_ai($user_input) {
    $api_key = get_option('ai_chat_api_key');
    
    if (empty($api_key)) {
        return 'Error: API key not configured. Please contact the site administrator.';
    }

    // Get cached site content or scan if not available
    $site_content = get_transient('ai_chat_site_content');
    if (false === $site_content) {
        scan_site_content();
        $site_content = get_transient('ai_chat_site_content');
    }

    // Prepare context for AI
    $context = "Website content:\n";
    foreach ($site_content as $content) {
        $context .= "Title: {$content['title']}\n";
        $context .= "Content: " . wp_strip_all_tags($content['content']) . "\n\n";
    }

    // Prepare the prompt
    $prompt = "Context:\n{$context}\n\nQuestion: {$user_input}\nAnswer:";

    // Call OpenAI API
    $response = wp_remote_post('https://api.openai.com/v1/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message();
    }

    $body = json_decode($response['body'], true);
    return $body['choices'][0]['text'] ?? 'Sorry, I couldn\'t process your request.';
}

// AJAX handler for chat queries
function ai_chat_ajax_handler() {
    check_ajax_referer('ai_chat_nonce', 'security');
    
    $user_input = sanitize_text_field($_POST['message']);
    $response = query_ai($user_input);
    
    wp_send_json_success([
        'response' => $response
    ]);
}
add_action('wp_ajax_ai_chat_query', 'ai_chat_ajax_handler');
add_action('wp_ajax_nopriv_ai_chat_query', 'ai_chat_ajax_handler');