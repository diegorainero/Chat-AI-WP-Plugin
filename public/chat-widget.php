<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function ai_chat_display_widget() {
    wp_enqueue_script('ai-chat-script');
    wp_enqueue_style('ai-chat-style');
    
    // Add nonce for AJAX security
    wp_localize_script('ai-chat-script', 'ai_chat_vars', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ai_chat_nonce')
    ]);
    ?>
    <div id="ai-chat-container" class="fixed bottom-4 right-4 w-96 bg-white shadow-lg rounded-lg hidden">
        <div class="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-semibold">AI Assistant</h3>
            <button id="ai-chat-close" class="text-white hover:text-gray-200">×</button>
        </div>
        <div id="ai-chat-messages" class="p-4 h-64 overflow-y-auto">
            <div class="flex items-start mb-4">
                <div class="bg-gray-200 p-3 rounded-br-lg rounded-tl-lg">
                    Hello! How can I help you today?
                </div>
            </div>
        </div>
        <div class="p-4 border-t">
            <form id="ai-chat-form" class="flex">
                <input type="text" id="ai-chat-input" placeholder="Ask me anything..." 
                    class="flex-1 border rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                    Send
                </button>
            </form>
        </div>
    </div>
    <button id="ai-chat-toggle" class="fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
    <?php
}
add_action('wp_footer', 'ai_chat_display_widget');