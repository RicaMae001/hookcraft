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
            justify-content: space-between;
        }

        .header-left {
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

        .header-info p.live-chat-status {
            color: #FFD700;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .home-btn {
            background: white;
            color: #FF69B4;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .home-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .live-chat-btn {
            background: white;
            color: #FF69B4;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .live-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .live-chat-btn.waiting {
            background: #FFA500;
            color: white;
            animation: pulse 2s infinite;
        }

        .live-chat-btn.active {
            background: #32CD32;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .queue-badge {
            background: #FF4444;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
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

        .message.system {
            justify-content: center;
        }

        .message.system .message-content {
            background: #FFF3CD;
            color: #856404;
            border: 1px solid #FFE69C;
            text-align: center;
            max-width: 80%;
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

        .message.staff .message-content {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 10px rgba(76, 175, 80, 0.3);
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

        .message.staff .message-avatar {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .sender-name {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
            opacity: 0.8;
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

        .chat-mode-indicator {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .chat-mode-indicator.live {
            color: #32CD32;
            font-weight: 600;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            color: #FF69B4;
            margin-bottom: 10px;
        }

        .modal-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #FFE4E8;
            border-radius: 10px;
            font-size: 14px;
        }

        .modal-form input:focus {
            outline: none;
            border-color: #FF69B4;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .modal-btn.primary {
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            color: white;
        }

        .modal-btn.secondary {
            background: #E0E0E0;
            color: #666;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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

            .live-chat-btn, .home-btn {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            .header-actions {
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="header-left">
                <div class="bot-avatar" id="headerAvatar">🤖</div>
                <div class="header-info">
                    <h2 id="headerTitle">AI Assistant</h2>
                    <p id="headerStatus">Online • Ready to help</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="home-btn" onclick="goToHomepage()">
                    <span>🏠</span>
                    <span>Home</span>
                </button>
                <button class="live-chat-btn" id="liveChatBtn" onclick="toggleLiveChat()">
                    <span id="liveChatIcon">💬</span>
                    <span id="liveChatText">Chat with Staff</span>
                </button>
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
            <div class="chat-mode-indicator" id="chatModeIndicator">
                <span>🤖</span>
                <span>Chatting with AI Assistant</span>
            </div>
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

    <div id="liveChatModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Connect with Staff</h3>
                <p>Are you sure you want to start a live chat with our support team?</p>
            </div>
            <div class="modal-buttons">
                <button type="button" class="modal-btn secondary" onclick="closeLiveChatModal()">Cancel</button>
                <button type="button" class="modal-btn primary" onclick="startLiveChatDirect()">Start Live Chat</button>
            </div>
        </div>
    </div>

    <script>
        let isLiveChatMode = false;
        let liveChatSessionId = null;
        let pollingInterval = null;
        let currentChatStatus = null; // Track current status
        let activityHeartbeat = null; // Track heartbeat interval

        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const typingIndicator = document.getElementById('typingIndicator');
        const suggestedQuestions = document.getElementById('suggestedQuestions');
        const liveChatBtn = document.getElementById('liveChatBtn');
        const liveChatModal = document.getElementById('liveChatModal');
        const chatModeIndicator = document.getElementById('chatModeIndicator');
        const headerAvatar = document.getElementById('headerAvatar');
        const headerTitle = document.getElementById('headerTitle');
        const headerStatus = document.getElementById('headerStatus');

        // Send activity heartbeat to track customer presence
        function sendActivityHeartbeat() {
            if (!liveChatSessionId || !isLiveChatMode) return;
            
            fetch('{{ route("livechat.heartbeat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    session_id: liveChatSessionId
                })
            }).catch(error => console.error('Heartbeat error:', error));
        }

        // Start heartbeat when live chat is active
        function startActivityHeartbeat() {
            if (activityHeartbeat) clearInterval(activityHeartbeat);
            
            // Send heartbeat every 10 seconds
            activityHeartbeat = setInterval(sendActivityHeartbeat, 10000);
            
            // Send initial heartbeat
            sendActivityHeartbeat();
        }

        // Stop heartbeat
        function stopActivityHeartbeat() {
            if (activityHeartbeat) {
                clearInterval(activityHeartbeat);
                activityHeartbeat = null;
            }
        }

        // Track page visibility - stop heartbeat when user leaves
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isLiveChatMode) {
                stopActivityHeartbeat();
            } else if (!document.hidden && isLiveChatMode) {
                startActivityHeartbeat();
            }
        });

        window.addEventListener('load', async () => {
            messageInput.focus();
            scrollToBottom();
            
            // Check if staff just joined (after page refresh)
            if (sessionStorage.getItem('staffJustJoined') === 'true') {
                sessionStorage.removeItem('staffJustJoined');
                
                // Show notification that staff joined
                setTimeout(() => {
                    addMessage('✓ A staff member has joined the chat!', 'system');
                    scrollToBottom();
                }, 500);
            }
            
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            
            if (isAuthenticated) {
                try {
                    const response = await fetch('{{ route("livechat.active-session") }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success && data.has_session) {
                        liveChatSessionId = data.session.session_id;
                        isLiveChatMode = true;
                        
                        const status = data.session.status;
                        currentChatStatus = status; // Set initial status
                        
                        if (status === 'waiting') {
                            updateUIForLiveChat('waiting', data.session.queue_position);
                            addMessage('🎫 Reconnected to queue. You are #' + data.session.queue_position + ' in line.', 'system');
                        } else if (status === 'active') {
                            updateUIForLiveChat('active');
                            addMessage('✓ Reconnected to your active chat session!', 'system');
                            await loadChatHistory(data.session.session_id);
                            startActivityHeartbeat(); // Start heartbeat for active session
                        }
                        
                        startPolling();
                    }
                } catch (error) {
                    console.error('Error checking for active session:', error);
                }
            }
        });

        async function loadChatHistory(sessionId) {
            try {
                const response = await fetch(`{{ url('livechat/history') }}/${sessionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                
                const data = await response.json();
                
                if (data.success && data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        if (msg.sender_type === 'customer') {
                            addMessage(msg.message, 'user');
                        } else if (msg.sender_type === 'admin' || msg.sender_type === 'staff') {
                            addMessage(msg.message, 'staff', msg.sender_name || 'Staff');
                        } else if (msg.sender_type === 'system') {
                            addMessage(msg.message, 'system');
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading chat history:', error);
            }
        }

        function goToHomepage() {
            if (isLiveChatMode && liveChatSessionId) {
                if (confirm('You are in an active live chat. Are you sure you want to leave?')) {
                    window.location.href = '{{ route("home") }}';
                }
            } else {
                window.location.href = '{{ route("home") }}';
            }
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function getTimestamp() {
            const now = new Date();
            return now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }

        function addMessage(message, type = 'bot', senderName = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            
            let avatar = '🤖';
            if (type === 'user') avatar = '👤';
            if (type === 'staff') avatar = '👨‍💼';
            if (type === 'system') avatar = '';

            let content = `<div>${message}</div><div class="timestamp">${getTimestamp()}</div>`;
            if (senderName && type === 'staff') {
                content = `<div class="sender-name">${senderName}</div>${content}`;
            }
            
            messageDiv.innerHTML = type === 'system' ? 
                `<div class="message-content">${content}</div>` :
                `
                ${type !== 'user' ? `<div class="message-avatar">${avatar}</div>` : ''}
                <div class="message-content">${content}</div>
                ${type === 'user' ? `<div class="message-avatar">${avatar}</div>` : ''}
            `;

            chatMessages.insertBefore(messageDiv, chatMessages.lastElementChild);
            scrollToBottom();
            
            if (isLiveChatMode && type === 'staff') {
                markMessagesAsRead();
            }
        }

        function toggleTyping(show) {
            typingIndicator.style.display = show ? 'block' : 'none';
            scrollToBottom();
        }

        function toggleLiveChat() {
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isAuthenticated) {
                alert('Please login to use live chat support.');
                window.location.href = '{{ route("login") }}';
                return;
            }
            
            if (isLiveChatMode) {
                if (confirm('Are you sure you want to end this live chat session?')) {
                    endLiveChatSession();
                }
            } else {
                liveChatModal.style.display = 'block';
            }
        }

        function closeLiveChatModal() {
            liveChatModal.style.display = 'none';
        }

        async function startLiveChatDirect() {
            closeLiveChatModal();
            
            try {
                const response = await fetch('{{ route("livechat.request") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    liveChatSessionId = data.session_id;
                    isLiveChatMode = true;
                    currentChatStatus = 'waiting'; // Set initial status
                    updateUIForLiveChat('waiting', data.queue_position);
                    addMessage('🎫 You have been added to the queue. A staff member will be with you shortly...', 'system');
                    startPolling();
                } else {
                    alert(data.message || 'Failed to start live chat. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to connect. Please check your internet connection.');
            }
        }

        function updateUIForLiveChat(status, queuePosition = null) {
            const btn = liveChatBtn;
            const icon = document.getElementById('liveChatIcon');
            const text = document.getElementById('liveChatText');

            if (status === 'waiting') {
                btn.className = 'live-chat-btn waiting';
                icon.textContent = '⏱️';
                text.innerHTML = `Waiting... <span class="queue-badge">#${queuePosition}</span>`;
                headerAvatar.textContent = '⏱️';
                headerTitle.textContent = 'Waiting for Staff';
                headerStatus.innerHTML = `<span class="live-chat-status">You are #${queuePosition} in queue</span>`;
                chatModeIndicator.innerHTML = '<span>⏱️</span><span>Waiting for staff to join...</span>';
                chatModeIndicator.className = 'chat-mode-indicator live';
            } else if (status === 'active') {
                btn.className = 'live-chat-btn active';
                icon.textContent = '✓';
                text.textContent = 'End Chat';
                headerAvatar.textContent = '👨‍💼';
                headerTitle.textContent = 'Live Chat Active';
                headerStatus.innerHTML = '<span class="live-chat-status">Connected to staff</span>';
                chatModeIndicator.innerHTML = '<span>👨‍💼</span><span>Live chat with staff</span>';
                chatModeIndicator.className = 'chat-mode-indicator live';
            }
        }

        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            
            pollingInterval = setInterval(async () => {
                if (!liveChatSessionId) return;

                try {
                    const response = await fetch(`{{ url('livechat/poll') }}/${liveChatSessionId}`);
                    const data = await response.json();

                    // Update UI when status changes from waiting to active
                    if (data.status === 'active' && currentChatStatus !== 'active') {
                        currentChatStatus = 'active';
                        updateUIForLiveChat('active');
                        addMessage('✓ A staff member has joined the chat!', 'system');
                        startActivityHeartbeat(); // Start heartbeat when staff joins
                    } else if (data.status === 'waiting' && currentChatStatus !== 'waiting') {
                        currentChatStatus = 'waiting';
                        updateUIForLiveChat('waiting', data.queue_position);
                    }

                    if (data.new_messages && data.new_messages.length > 0) {
                        data.new_messages.forEach(msg => {
                            if (msg.sender_type === 'admin' || msg.sender_type === 'staff') {
                                addMessage(msg.message, 'staff', msg.sender_name || 'Staff');
                            } else if (msg.sender_type === 'system') {
                                addMessage(msg.message, 'system');
                            }
                        });
                    }

                    if (data.status === 'closed') {
                        endLiveChat();
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 3000);
        }

        function markMessagesAsRead() {
            if (!liveChatSessionId) return;
            
            fetch(`{{ url('livechat/mark-read') }}/${liveChatSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }).catch(error => console.error('Error marking messages as read:', error));
        }

        async function endLiveChatSession() {
            try {
                stopActivityHeartbeat(); // Stop heartbeat when ending chat
                
                await fetch(`{{ url('admin/livechat/end') }}/${liveChatSessionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                
                endLiveChat();
            } catch (error) {
                console.error('Error ending chat:', error);
                endLiveChat();
            }
        }

        function endLiveChat() {
            if (pollingInterval) clearInterval(pollingInterval);
            stopActivityHeartbeat(); // Stop heartbeat
            isLiveChatMode = false;
            liveChatSessionId = null;
            currentChatStatus = null; // Reset status tracker
            
            liveChatBtn.className = 'live-chat-btn';
            document.getElementById('liveChatIcon').textContent = '💬';
            document.getElementById('liveChatText').textContent = 'Chat with Staff';
            
            headerAvatar.textContent = '🤖';
            headerTitle.textContent = 'AI Assistant';
            headerStatus.textContent = 'Online • Ready to help';
            
            chatModeIndicator.innerHTML = '<span>🤖</span><span>Chatting with AI Assistant</span>';
            chatModeIndicator.className = 'chat-mode-indicator';
            
            addMessage('Chat session ended. You can chat with our AI assistant or start a new live chat.', 'system');
        }

        async function sendMessage(message) {
            sendBtn.disabled = true;
            messageInput.disabled = true;

            if (suggestedQuestions.children.length > 0) {
                suggestedQuestions.style.display = 'none';
            }

            addMessage(message, 'user');
            messageInput.value = '';

            if (isLiveChatMode && liveChatSessionId) {
                try {
                    await fetch('{{ route("livechat.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            session_id: liveChatSessionId,
                            message: message
                        })
                    });
                } catch (error) {
                    console.error('Error sending message:', error);
                    addMessage('Failed to send message. Please try again.', 'system');
                }
            } else {
                toggleTyping(true);

                try {
                    const response = await fetch('{{ route("chatbot.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ message: message })
                    });

                    const data = await response.json();
                    toggleTyping(false);

                    if (response.ok) {
                        addMessage(data.response);
                    } else {
                        addMessage('Sorry, I encountered an error. Please try again.');
                    }
                } catch (error) {
                    toggleTyping(false);
                    addMessage('Sorry, I\'m having trouble connecting.');
                    console.error('Error:', error);
                }
            }

            sendBtn.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        }

        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        });

        function sendSuggested(question) {
            sendMessage(question);
        }

        window.onclick = function(event) {
            if (event.target == liveChatModal) {
                closeLiveChatModal();
            }
        };

        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>