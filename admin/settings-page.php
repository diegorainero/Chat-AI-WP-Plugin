<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
function ai_chat_add_admin_menu() {
    add_menu_page(
        'AI Chat Settings',
        'AI Chat',
        'manage_options',
        'ai-chat-settings',
        'ai_chat_settings_page',
        'dashicons-format-chat'
    );
}
add_action('admin_menu', 'ai_chat_add_admin_menu');

// Register settings
function ai_chat_register_settings() {
    register_setting('ai_chat_settings_group', 'ai_chat_api_key');
    register_setting('ai_chat_settings_group', 'ai_chat_content_types');
    register_setting('ai_chat_settings_group', 'ai_chat_excluded_categories');
}
add_action('admin_init', 'ai_chat_register_settings');

// Settings page content
function ai_chat_settings_page() {
    ?>
    <div class="wrap">
        <h1>AI Chat Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('ai_chat_settings_group'); ?>
            <?php do_settings_sections('ai_chat_settings_group'); ?>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">AI API Key</th>
                    <td>
                        <input type="password" name="ai_chat_api_key" value="<?php echo esc_attr(get_option('ai_chat_api_key')); ?>" class="regular-text" />
                        <p class="description">Enter your OpenAI API key</p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Content Types to Scan</th>
                    <td>
                        <?php
                        $post_types = get_post_types(['public' => true], 'objects');
                        $selected_types = get_option('ai_chat_content_types', ['post', 'page']);
                        
                        foreach ($post_types as $post_type) {
                            $checked = in_array($post_type->name, $selected_types) ? 'checked' : '';
                            echo '<label><input type="checkbox" name="ai_chat_content_types[]" value="' . esc_attr($post_type->name) . '" ' . $checked . '> ' . esc_html($post_type->label) . '</label><br>';
                        }
                        ?>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Excluded Categories</th>
                    <td>
                        <input type="text" name="ai_chat_excluded_categories" value="<?php echo esc_attr(get_option('ai_chat_excluded_categories')); ?>" class="regular-text" />
                        <p class="description">Comma-separated category slugs to exclude</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}