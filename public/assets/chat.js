jQuery(document).ready(function($) {
    const chatContainer = $('#ai-chat-container');
    const chatToggle = $('#ai-chat-toggle');
    const chatClose = $('#ai-chat-close');
    const chatMessages = $('#ai-chat-messages');
    const chatForm = $('#ai-chat-form');
    const chatInput = $('#ai-chat-input');

    // Toggle chat visibility
    chatToggle.on('click', function() {
        chatContainer.toggleClass('hidden');
    });

    // Close chat
    chatClose.on('click', function() {
        chatContainer.addClass('hidden');
    });

    // Handle form submission
    chatForm.on('submit', function(e) {
        e.preventDefault();
        const message = chatInput.val().trim();
        
        if (message) {
            // Add user message to chat
            addMessage(message, 'user');
            chatInput.val('');
            
            // Send to server
            $.ajax({
                url: ai_chat_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'ai_chat_query',
                    security: ai_chat_vars.nonce,
                    message: message
                },
                beforeSend: function() {
                    chatInput.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        addMessage(response.data.response, 'ai');
                    } else {
                        addMessage('Sorry, something went wrong.', 'ai');
                    }
                },
                error: function() {
                    addMessage('Error connecting to the server.', 'ai');
                },
                complete: function() {
                    chatInput.prop('disabled', false);
                }
            });
        }
    });

    // Add message to chat
    function addMessage(text, sender) {
        const messageClass = sender === 'user' 
            ? 'flex items-start justify-end mb-4' 
            : 'flex items-start mb-4';
        
        const bubbleClass = sender === 'user'
            ? 'bg-blue-500 text-white p-3 rounded-bl-lg rounded-tr-lg'
            : 'bg-gray-200 p-3 rounded-br-lg rounded-tl-lg';
        
        const messageHtml = `
            <div class="${messageClass}">
                <div class="${bubbleClass}">${text}</div>
            </div>
        `;
        
        chatMessages.append(messageHtml);
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }
});