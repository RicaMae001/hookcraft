<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chatbot - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .main-container {
            width: 80%;
            max-width: 1000px;
            display: flex;
            gap: 20px;
        }

        /* Orders Sidebar Styles */
        .orders-sidebar {
            width: 350px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(255, 105, 180, 0.25);
            overflow: hidden;
            display: none;
        }

        .orders-sidebar.active {
            display: block;
        }

        .orders-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 20px;
        }

        .orders-header h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .orders-content {
            padding: 15px;
            max-height: 500px;
            overflow-y: auto;
        }

        .order-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: #4CAF50;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2);
        }

        .order-number {
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .order-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .status-pending { background: #FFF3CD; color: #856404; }
        .status-out_for_delivery { background: #D4EDDA; color: #155724; }
        .status-delivered { background: #D1ECF1; color: #0C5460; }

        .order-detail {
            font-size: 13px;
            color: #666;
            margin: 5px 0;
        }

        .order-detail i {
            width: 16px;
            color: #4CAF50;
        }

        .reference-btn {
            width: 100%;
            padding: 8px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s;
        }

        .reference-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .no-orders {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .no-orders i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        /* Chat Container Styles */
        .chat-container {
            flex: 1;
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

        .message.delivery .message-content {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 10px rgba(33, 150, 243, 0.3);
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

        .message.delivery .message-avatar {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
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

        .chat-mode-indicator.delivery {
            color: #2196F3;
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

        /* Modal Styles */
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
            max-width: 500px;
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                width: 90%;
            }
            
            .orders-sidebar {
                width: 100%;
                max-height: 300px;
            }
            
            .orders-content {
                max-height: 250px;
            }
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
            
            .modal-content {
                margin: 20% auto;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    
@include('components.login_modal')
@include('components.signup_modal')

    <div class="main-container">
        <!-- Orders Sidebar (Only shown in delivery chat) -->
        <div class="orders-sidebar" id="ordersSidebar">
            <div class="orders-header">
                <h3><i class="fas fa-box"></i> Your Orders</h3>
                <small>Click to reference in chat</small>
            </div>
            <div class="orders-content" id="ordersContent">
                <div class="no-orders">
                    <i class="fas fa-box-open"></i>
                    <p>Loading orders...</p>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
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
                        <span id="liveChatText">Chat with Support</span>
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
    </div>

    <div id="liveChatModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Choose Support Type</h3>
                <p>How can we help you today?</p>
            </div>
            <div class="modal-buttons" style="flex-direction: column; gap: 15px;">
                <button type="button" class="modal-btn primary" onclick="startLiveChatWithType('staff')" 
                        style="width: 100%; padding: 15px; text-align: left; display: flex; align-items: center; gap: 15px;">
                    <i class="fas fa-user-tie" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-size: 16px; font-weight: bold;">Website Support</div>
                        <div style="font-size: 12px; opacity: 0.9;">General inquiries, products, account help</div>
                    </div>
                </button>
                <button type="button" class="modal-btn primary" onclick="startLiveChatWithType('delivery')" 
                        style="width: 100%; padding: 15px; text-align: left; display: flex; align-items: center; gap: 15px; background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);">
                    <i class="fas fa-truck" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-size: 16px; font-weight: bold;">Order & Delivery Support</div>
                        <div style="font-size: 12px; opacity: 0.9;">Track orders, delivery status, order issues</div>
                    </div>
                </button>
                <button type="button" class="modal-btn secondary" onclick="closeLiveChatModal()" style="width: 100%;">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let isLiveChatMode = false;
        let liveChatSessionId = null;
        let pollingInterval = null;
        let currentChatStatus = null;
        let activityHeartbeat = null;
        let currentSupportType = null;
        let customerOrders = [];

        const ordersSidebar = document.getElementById('ordersSidebar');
        const ordersContent = document.getElementById('ordersContent');
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
            
            activityHeartbeat = setInterval(sendActivityHeartbeat, 10000);
            sendActivityHeartbeat();
        }

        // Stop heartbeat
        function stopActivityHeartbeat() {
            if (activityHeartbeat) {
                clearInterval(activityHeartbeat);
                activityHeartbeat = null;
            }
        }

        // Track page visibility
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
            
            if (sessionStorage.getItem('staffJustJoined') === 'true') {
                sessionStorage.removeItem('staffJustJoined');
                setTimeout(() => {
                    addMessage('✓ A support member has joined the chat!', 'system');
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
                        currentSupportType = data.session.support_type || 'staff';
                        
                        const status = data.session.status;
                        currentChatStatus = status;
                        
                        if (status === 'waiting') {
                            updateUIForLiveChat('waiting', data.session.queue_position);
                            addMessage('🎫 Reconnected to queue. You are #' + data.session.queue_position + ' in line.', 'system');
                        } else if (status === 'active') {
                            updateUIForLiveChat('active');
                            addMessage('✓ Reconnected to your active chat session!', 'system');
                            await loadChatHistory(data.session.session_id);
                            startActivityHeartbeat();
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
                        } else if (msg.sender_type === 'admin' || msg.sender_type === 'staff' || msg.sender_type === 'delivery') {
                            const messageType = currentSupportType === 'delivery' ? 'delivery' : 'staff';
                            addMessage(msg.message, messageType, msg.sender_name || 'Support');
                        } else if (msg.sender_type === 'system') {
                            addMessage(msg.message, 'system');
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading chat history:', error);
            }
        }

        // Load customer orders when delivery chat is active
        async function loadCustomerOrders() {
            if (!isLiveChatMode || currentSupportType !== 'delivery') {
                ordersSidebar.classList.remove('active');
                return;
            }

            try {
                const response = await fetch('{{ route("customer.orders.ongoing") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                const data = await response.json();

                if (data.success && data.orders && data.orders.length > 0) {
                    customerOrders = data.orders;
                    displayOrders(data.orders);
                    ordersSidebar.classList.add('active');
                } else {
                    ordersContent.innerHTML = `
                        <div class="no-orders">
                            <i class="fas fa-box-open"></i>
                            <p>No ongoing orders</p>
                            <small>You don't have any active orders</small>
                        </div>
                    `;
                    ordersSidebar.classList.add('active');
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                ordersContent.innerHTML = `
                    <div class="no-orders">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Failed to load orders</p>
                    </div>
                `;
            }
        }

        function displayOrders(orders) {
            ordersContent.innerHTML = orders.map(order => {
                const statusClass = order.delivery_status === 'Pending' ? 'status-pending' : 
                                  order.delivery_status === 'Out for Delivery' ? 'status-out_for_delivery' : 
                                  'status-delivered';
                const statusText = order.delivery_status;
                
                return `
                    <div class="order-card">
                        <div class="order-number">
                            <i class="fas fa-receipt"></i> ${order.order_number}
                        </div>
                        <span class="order-status ${statusClass}">${statusText}</span>
                        <div class="order-detail">
                            <i class="fas fa-shopping-cart"></i> ${order.items_count} item(s)
                        </div>
                        <div class="order-detail">
                            <i class="fas fa-peso-sign"></i> ₱${parseFloat(order.total).toLocaleString()}
                        </div>
                        ${order.address ? `
                            <div class="order-detail">
                                <i class="fas fa-map-marker-alt"></i> ${order.address.substring(0, 40)}...
                            </div>
                        ` : ''}
                        <button class="reference-btn" onclick="referenceOrder('${order.order_number}')">
                            <i class="fas fa-comment-dots"></i> Reference in Chat
                        </button>
                    </div>
                `;
            }).join('');
        }

        function referenceOrder(orderNumber) {
            messageInput.value = `I have a question about order ${orderNumber}: `;
            messageInput.focus();
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
            if (type === 'delivery') avatar = '🚚';
            if (type === 'system') avatar = '';

            let content = `<div>${message}</div><div class="timestamp">${getTimestamp()}</div>`;
            if (senderName && (type === 'staff' || type === 'delivery')) {
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
            
            if (isLiveChatMode && (type === 'staff' || type === 'delivery')) {
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
                window.location.href = '{{ route("home") }}';
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

        async function startLiveChatWithType(type) {
            closeLiveChatModal();
            currentSupportType = type;
            
            try {
                const route = type === 'delivery' ? '{{ route("livechat.request-delivery") }}' : '{{ route("livechat.request") }}';
                
                const response = await fetch(route, {
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
                    currentChatStatus = 'waiting';
                    
                    // Load orders if delivery chat
                    if (type === 'delivery') {
                        await loadCustomerOrders();
                    }
                    
                    const staffType = type === 'delivery' ? 'delivery coordinator' : 'staff member';
                    updateUIForLiveChat('waiting', data.queue_position);
                    addMessage(`🎫 You have been added to the queue. A ${staffType} will be with you shortly...`, 'system');
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
                headerTitle.textContent = 'Waiting for Support';
                headerStatus.innerHTML = `<span class="live-chat-status">You are #${queuePosition} in queue</span>`;
                chatModeIndicator.innerHTML = `<span>⏱️</span><span>Waiting for support to join...</span>`;
                chatModeIndicator.className = `chat-mode-indicator ${currentSupportType === 'delivery' ? 'delivery' : 'live'}`;
            } else if (status === 'active') {
                btn.className = 'live-chat-btn active';
                icon.textContent = '✓';
                text.textContent = 'End Chat';
                headerAvatar.textContent = currentSupportType === 'delivery' ? '🚚' : '👨‍💼';
                headerTitle.textContent = currentSupportType === 'delivery' ? 'Delivery Support' : 'Live Support';
                headerStatus.innerHTML = '<span class="live-chat-status">Connected to support</span>';
                chatModeIndicator.innerHTML = `<span>${currentSupportType === 'delivery' ? '🚚' : '👨‍💼'}</span><span>Live chat with support</span>`;
                chatModeIndicator.className = `chat-mode-indicator ${currentSupportType === 'delivery' ? 'delivery' : 'live'}`;
            }
        }

        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            
            pollingInterval = setInterval(async () => {
                if (!liveChatSessionId) return;

                try {
                    const response = await fetch(`{{ url('livechat/poll') }}/${liveChatSessionId}`);
                    const data = await response.json();

                    if (data.status === 'active' && currentChatStatus !== 'active') {
                        currentChatStatus = 'active';
                        updateUIForLiveChat('active');
                        addMessage('✓ A support member has joined the chat!', 'system');
                        startActivityHeartbeat();
                    } else if (data.status === 'waiting' && currentChatStatus !== 'waiting') {
                        currentChatStatus = 'waiting';
                        updateUIForLiveChat('waiting', data.queue_position);
                    }

                    if (data.new_messages && data.new_messages.length > 0) {
                        data.new_messages.forEach(msg => {
                            if (msg.sender_type === 'admin' || msg.sender_type === 'staff' || msg.sender_type === 'delivery') {
                                const messageType = currentSupportType === 'delivery' ? 'delivery' : 'staff';
                                addMessage(msg.message, messageType, msg.sender_name || 'Support');
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
                stopActivityHeartbeat();
                
                await fetch('{{ route("livechat.end") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        session_id: liveChatSessionId
                    })
                });
                
                endLiveChat();
            } catch (error) {
                console.error('Error ending chat:', error);
                endLiveChat();
            }
        }

        function endLiveChat() {
            if (pollingInterval) clearInterval(pollingInterval);
            stopActivityHeartbeat();
            isLiveChatMode = false;
            liveChatSessionId = null;
            currentChatStatus = null;
            currentSupportType = null;
            
            ordersSidebar.classList.remove('active');
            
            liveChatBtn.className = 'live-chat-btn';
            document.getElementById('liveChatIcon').textContent = '💬';
            document.getElementById('liveChatText').textContent = 'Chat with Support';
            
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