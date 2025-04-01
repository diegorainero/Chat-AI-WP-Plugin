<?php
/**
 * Plugin Name: AI Chat Plugin
 * Description: A WordPress plugin that uses AI to provide a chat interface for users to query site information.
 * Version: 1.0
 * Author: Diego Rainero
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register activation and deactivation hooks
register_activation_hook( __FILE__, 'ai_chat_plugin_activate' );
register_deactivation_hook( __FILE__, 'ai_chat_plugin_deactivate' );

function ai_chat_plugin_activate() {
    // Code to run on activation
}

function ai_chat_plugin_deactivate() {
    // Code to run on deactivation
}

// Enqueue frontend assets
function ai_chat_enqueue_scripts() {
    wp_enqueue_style( 'ai-chat-style', plugin_dir_url( __FILE__ ) . 'public/assets/chat.css' );
    wp_enqueue_script( 'ai-chat-script', plugin_dir_url( __FILE__ ) . 'public/assets/chat.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'ai_chat_enqueue_scripts' );

// Include necessary files
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/ai-handler.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/content-scanner.php';
require_once plugin_dir_path( __FILE__ ) . 'public/chat-widget.php';
