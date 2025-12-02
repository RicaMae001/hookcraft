<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chatbot - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFB6C1 0%, #FFE4E1 50%, #FFF0F5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .chat-container {
            width: 100%;
            max-width: 800px;
            height: 600px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(255, 105, 180, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
            color: white;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bot-avatar {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header-info h2 {
            font-size: 1.5em;
            margin-bottom: 3px;
        }

        .header-info p {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            background: #FAFAFA;
        }

        .message {
            display: flex;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            padding: 15px 20px;
            border-radius: 18px;
            position: relative;
        }

        .message.bot .message-content {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .message.user .message-content {
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 2px 10px rgba(255, 105, 180, 0.3);
        }

        .message-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            flex-shrink: 0;
        }

        .message.bot .message-avatar {
            background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
            color: white;
        }

        .message.user .message-avatar {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFB6C1 100%);
            color: #FF69B4;
        }

        .typing-indicator {
            display: none;
            padding: 15px 20px;
            background: white;
            border-radius: 18px;
            width: fit-content;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .typing-indicator span {
            height: 10px;
            width: 10px;
            background: #FF69B4;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.7;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .chat-input-container {
            padding: 20px 25px;
            background: white;
            border-top: 2px solid #FFE4E8;
        }

        .chat-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #FFE4E8;
            border-radius: 25px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
        }

        .chat-input:focus {
            border-color: #FF69B4;
            box-shadow: 0 0 0 4px rgba(255, 105, 180, 0.1);
        }

        .send-btn {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
        }

        .send-btn:hover:not(:disabled) {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.4);
        }

        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .send-btn svg {
            width: 24px;
            height: 24px;
        }

        .suggested-questions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0 25px 15px;
            background: #FAFAFA;
        }

        .suggested-btn {
            padding: 8px 16px;
            background: white;
            border: 2px solid #FFE4E8;
            border-radius: 20px;
            color: #FF69B4;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .suggested-btn:hover {
            background: #FF69B4;
            color: white;
            border-color: #FF69B4;
        }

        .timestamp {
            font-size: 11px;
            opacity: 0.6;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
            }

            .message-content {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="bot-avatar">🤖</div>
            <div class="header-info">
                <h2>AI Assistant</h2>
                <p>Online • Ready to help</p>
            </div>
        </div>

        <div class="suggested-questions" id="suggestedQuestions">
            <button class="suggested-btn" onclick="sendSuggested('What are your business hours?')">
                Business Hours?
            </button>
            <button class="suggested-btn" onclick="sendSuggested('Tell me about your products')">
                Products Info
            </button>
            <button class="suggested-btn" onclick="sendSuggested('How can I track my order?')">
                Track Order
            </button>
            <button class="suggested-btn" onclick="sendSuggested('What is your return policy?')">
                Return Policy
            </button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    <div>Hello! 👋 I'm your AI assistant. How can I help you today?</div>
                    <div class="timestamp">Just now</div>
                </div>
            </div>

            <div class="message bot" style="margin-left: 45px;">
                <div class="typing-indicator" id="typingIndicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <div class="chat-input-container">
            <form id="chatForm" class="chat-input-wrapper">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="messageInput" 
                    placeholder="Type your message here..."
                    autocomplete="off"
                    required
                >
                <button type="submit" class="send-btn" id="sendBtn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const typingIndicator = document.getElementById('typingIndicator');
        const suggestedQuestions = document.getElementById('suggestedQuestions');

        // Auto-scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Format timestamp
        function getTimestamp() {
            const now = new Date();
            return now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }

        // Add message to chat
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            messageDiv.innerHTML = `
                ${!isUser ? '<div class="message-avatar">🤖</div>' : ''}
                <div class="message-content">
                    <div>${message}</div>
                    <div class="timestamp">${getTimestamp()}</div>
                </div>
                ${isUser ? '<div class="message-avatar">👤</div>' : ''}
            `;

            // Insert before typing indicator
            chatMessages.insertBefore(messageDiv, chatMessages.lastElementChild);
            scrollToBottom();
        }

        // Show/hide typing indicator
        function toggleTyping(show) {
            typingIndicator.style.display = show ? 'block' : 'none';
            scrollToBottom();
        }

        // Send message to backend
        async function sendMessage(message) {
            // Disable input
            sendBtn.disabled = true;
            messageInput.disabled = true;

            // Hide suggested questions after first message
            if (suggestedQuestions.children.length > 0) {
                suggestedQuestions.style.display = 'none';
            }

            // Add user message
            addMessage(message, true);
            messageInput.value = '';

            // Show typing indicator
            toggleTyping(true);

            try {
                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                // Hide typing indicator
                toggleTyping(false);

                if (response.ok) {
                    // Add bot response
                    addMessage(data.response);
                } else {
                    addMessage('Sorry, I encountered an error. Please try again.');
                }
            } catch (error) {
                toggleTyping(false);
                addMessage('Sorry, I\'m having trouble connecting. Please check your internet connection.');
                console.error('Error:', error);
            } finally {
                // Re-enable input
                sendBtn.disabled = false;
                messageInput.disabled = false;
                messageInput.focus();
            }
        }

        // Handle form submission
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        });

        // Handle suggested questions
        function sendSuggested(question) {
            sendMessage(question);
        }

        // Focus input on load
        window.addEventListener('load', () => {
            messageInput.focus();
            scrollToBottom();
        });

        // Allow Enter to send, Shift+Enter for new line
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>