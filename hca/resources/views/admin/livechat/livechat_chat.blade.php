{{-- Save as: resources/views/admin/livechat/livechat.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Live Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('admin.livechat.index') }}" class="btn btn-modern-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Live Chats
            </a>
        </div>

        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="content-card chat-container">
                <!-- Professional Header -->
                <div class="chat-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="chat-user-avatar">
                            <div class="avatar-content">
                                {{ strtoupper(substr($session->customer_name, 0, 2)) }}
                            </div>
                            @if($session->status === 'active')
                            <span class="status-indicator status-active"></span>
                            @endif
                        </div>
                        <div class="chat-user-info">
                            <h5 class="user-name mb-0">{{ $session->customer_name }}</h5>
                            <div class="user-meta">
                                @if($session->customer_email)
                                <span class="meta-item">
                                    <i class="fas fa-envelope"></i> {{ $session->customer_email }}
                                </span>
                                @endif
                                <span class="meta-item">
                                    <i class="fas fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        @if($session->status === 'active')
                        <span class="badge-modern badge-success me-2">
                            <i class="fas fa-circle pulse-animation"></i> Active
                        </span>
                        <button class="btn btn-danger btn-sm" onclick="endChat()">
                            <i class="fas fa-phone-slash me-1"></i> End Chat
                        </button>
                        @else
                        <span class="badge-modern badge-info">
                            <i class="fas fa-archive me-1"></i> Closed
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div class="chat-body" id="chatMessages">
                    <div class="messages-wrapper">
                        @foreach($messages as $message)
                            @php
                                $sender = $message->sender_type;
                                $time = \Carbon\Carbon::parse($message->created_at)
                                    ->setTimezone(config('app.timezone','Asia/Manila'))
                                    ->format('h:i A');
                            @endphp

                            @if($sender === 'system')
                                <div class="message-group system-group">
                                    <div class="system-divider">
                                        <span class="divider-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $message->message }}
                                        </span>
                                        <span class="divider-time">{{ $time }}</span>
                                    </div>
                                </div>
                            @elseif($sender === 'customer')
                                <div class="message-group customer-group">
                                    <div class="message-avatar customer-avatar">
                                        {{ strtoupper(substr($session->customer_name, 0, 1)) }}
                                    </div>
                                    <div class="message-container">
                                        <div class="message-header">
                                            <span class="sender-name">{{ $session->customer_name }}</span>
                                            <span class="message-time">{{ $time }}</span>
                                        </div>
                                        <div class="message-bubble customer-bubble">
                                            <div class="bubble-content">{{ $message->message }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="message-group staff-group">
                                    <div class="message-container">
                                        <div class="message-header text-end">
                                            <span class="message-time">{{ $time }}</span>
                                            <span class="sender-name">You</span>
                                        </div>
                                        <div class="message-bubble staff-bubble">
                                            <div class="bubble-content">{{ $message->message }}</div>
                                        </div>
                                    </div>
                                    <div class="message-avatar staff-avatar">
                                        {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Scroll to Bottom Button -->
                    <button id="scrollBtn" class="scroll-to-bottom" style="display: none;">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <!-- Typing Indicator (for future use) -->
                    <div id="typingIndicator" class="typing-indicator" style="display: none;">
                        <div class="message-group customer-group">
                            <div class="message-avatar customer-avatar">
                                {{ strtoupper(substr($session->customer_name, 0, 1)) }}
                            </div>
                            <div class="message-container">
                                <div class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="chat-footer">
                    @if($session->status === 'active')
                    <form id="chatForm" class="chat-input-form">
                        <div class="input-wrapper">
                            <button type="button" class="input-action-btn" title="Attach file">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input 
                                type="text" 
                                id="messageInput" 
                                class="message-input" 
                                placeholder="Type your message..." 
                                autocomplete="off" 
                                required
                            >
                            <button type="submit" class="send-button">
                                <i class="fas fa-paper-plane"></i>
                                <span class="ms-2 d-none d-sm-inline">Send</span>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="chat-closed-notice">
                        <i class="fas fa-lock me-2"></i>
                        This chat session has been closed
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ============================================
   PROFESSIONAL CHAT CONTAINER
============================================ */
.chat-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 200px);
    min-height: 600px;
    max-height: 800px;
    overflow: hidden;
}

/* ============================================
   CHAT HEADER
============================================ */
.chat-header {
    padding: 1.5rem 2rem;
    background: var(--card-bg);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.chat-user-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-content {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid var(--card-bg);
}

.status-active {
    background: var(--success);
}

.chat-user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.user-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.25rem;
    flex-wrap: wrap;
}

.meta-item {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.meta-item i {
    font-size: 0.75rem;
    opacity: 0.7;
}

.chat-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* ============================================
   CHAT BODY (Messages Area)
============================================ */
.chat-body {
    flex: 1;
    overflow-y: auto;
    background: var(--light-bg);
    position: relative;
    padding: 2rem;
}

.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: transparent;
}

.chat-body::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 10px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}

.messages-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

/* ============================================
   MESSAGE GROUPS
============================================ */
.message-group {
    display: flex;
    margin-bottom: 1.5rem;
    animation: messageSlideIn 0.3s ease;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.customer-group {
    justify-content: flex-start;
}

.staff-group {
    justify-content: flex-end;
}

.system-group {
    justify-content: center;
    margin: 2rem 0;
}

/* ============================================
   MESSAGE AVATARS
============================================ */
.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
}

.customer-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin-right: 12px;
}

.staff-avatar {
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
    margin-left: 12px;
}

/* ============================================
   MESSAGE CONTAINER
============================================ */
.message-container {
    max-width: 70%;
    display: flex;
    flex-direction: column;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    padding: 0 0.25rem;
}

.sender-name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
}

.message-time {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* ============================================
   MESSAGE BUBBLES
============================================ */
.message-bubble {
    position: relative;
    padding: 0.875rem 1.125rem;
    border-radius: 16px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 0.9375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;
}

.message-bubble:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.customer-bubble {
    background: var(--card-bg);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: 4px;
}

.staff-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.bubble-content {
    white-space: pre-wrap;
}

/* ============================================
   SYSTEM MESSAGES
============================================ */
.system-divider {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.divider-text {
    display: inline-flex;
    align-items: center;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 20px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-primary);
}

.divider-time {
    font-size: 0.6875rem;
    color: var(--text-secondary);
}

/* ============================================
   TYPING INDICATOR
============================================ */
.typing-dots {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 1rem 1.25rem;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    border-bottom-left-radius: 4px;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--text-secondary);
    animation: typingBounce 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typingBounce {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-8px);
    }
}

/* ============================================
   SCROLL TO BOTTOM BUTTON
============================================ */
.scroll-to-bottom {
    position: absolute;
    bottom: 1.5rem;
    right: 1.5rem;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
    color: white;
    border: none;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: all 0.3s ease;
    z-index: 10;
}

.scroll-to-bottom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.scroll-to-bottom:active {
    transform: translateY(0);
}

/* ============================================
   CHAT FOOTER (Input Area)
============================================ */
.chat-footer {
    padding: 1.5rem 2rem;
    background: var(--card-bg);
    border-top: 1px solid var(--border-color);
}

.chat-input-form {
    max-width: 900px;
    margin: 0 auto;
}

.input-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: var(--light-bg);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.input-wrapper:focus-within {
    border-color: var(--primary-pink);
    background: var(--card-bg);
    box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.1);
}

.input-action-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: all 0.2s ease;
}

.input-action-btn:hover {
    background: var(--hover-bg);
    color: var(--primary-pink);
}

.message-input {
    flex: 1;
    border: none;
    background: transparent;
    color: var(--text-primary);
    font-size: 0.9375rem;
    padding: 0.5rem;
    outline: none;
}

.message-input::placeholder {
    color: var(--text-secondary);
}

.send-button {
    padding: 0.625rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
}

.send-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
}

.send-button:active {
    transform: translateY(0);
}

.chat-closed-notice {
    text-align: center;
    padding: 1rem;
    color: var(--text-secondary);
    font-size: 0.9375rem;
    background: var(--hover-bg);
    border-radius: 12px;
}

/* ============================================
   PULSE ANIMATION
============================================ */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

.pulse-animation {
    animation: pulse 2s ease-in-out infinite;
}

/* ============================================
   RESPONSIVE DESIGN
============================================ */
@media (max-width: 992px) {
    .chat-container {
        height: calc(100vh - 180px);
    }
    
    .message-container {
        max-width: 80%;
    }
}

@media (max-width: 768px) {
    .chat-header {
        padding: 1rem 1.25rem;
    }
    
    .avatar-content {
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
    
    .user-name {
        font-size: 1rem;
    }
    
    .user-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .chat-body {
        padding: 1rem;
    }
    
    .message-container {
        max-width: 85%;
    }
    
    .chat-footer {
        padding: 1rem 1.25rem;
    }
    
    .send-button span {
        display: none !important;
    }
    
    .scroll-to-bottom {
        width: 40px;
        height: 40px;
        bottom: 1rem;
        right: 1rem;
    }
}

@media (max-width: 576px) {
    .message-avatar {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .message-container {
        max-width: 90%;
    }
}
</style>
@endpush

@push('scripts')
<script>
const sessionData = @json([
    'id' => $session->id,
    'status' => $session->status
]);

const chatMessages = document.getElementById('chatMessages');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const scrollBtn = document.getElementById('scrollBtn');
let pollingInterval = null;
let lastMessageId = {{ $messages->last()->id ?? 0 }};

function scrollToBottom(smooth = true) { 
    chatMessages.scrollTo({
        top: chatMessages.scrollHeight,
        behavior: smooth ? 'smooth' : 'auto'
    });
    scrollBtn.style.display = 'none'; 
}

chatMessages.addEventListener('scroll', () => {
    const isScrolledUp = chatMessages.scrollTop + chatMessages.clientHeight < chatMessages.scrollHeight - 100;
    scrollBtn.style.display = isScrolledUp ? 'flex' : 'none';
});

scrollBtn.addEventListener('click', () => scrollToBottom(true));

function escapeHtml(text) { 
    const div = document.createElement('div'); 
    div.textContent = text; 
    return div.innerHTML; 
}

function addMessage(message, type, timestamp) {
    const messagesWrapper = document.querySelector('.messages-wrapper');
    const messageGroup = document.createElement('div');
    messageGroup.className = `message-group ${type}-group`;
    
    const formattedTime = new Date(timestamp).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });

    if (type === 'customer') {
        messageGroup.innerHTML = `
            <div class="message-avatar customer-avatar">
                ${escapeHtml('{{ strtoupper(substr($session->customer_name, 0, 1)) }}')}
            </div>
            <div class="message-container">
                <div class="message-header">
                    <span class="sender-name">{{ $session->customer_name }}</span>
                    <span class="message-time">${formattedTime}</span>
                </div>
                <div class="message-bubble customer-bubble">
                    <div class="bubble-content">${escapeHtml(message)}</div>
                </div>
            </div>
        `;
    } else if (type === 'staff') {
        messageGroup.innerHTML = `
            <div class="message-container">
                <div class="message-header text-end">
                    <span class="message-time">${formattedTime}</span>
                    <span class="sender-name">You</span>
                </div>
                <div class="message-bubble staff-bubble">
                    <div class="bubble-content">${escapeHtml(message)}</div>
                </div>
            </div>
            <div class="message-avatar staff-avatar">
                ${escapeHtml('{{ strtoupper(substr(session("admin_name", "A"), 0, 1)) }}')}
            </div>
        `;
    } else { // system
        messageGroup.innerHTML = `
            <div class="system-divider">
                <span class="divider-text">
                    <i class="fas fa-info-circle me-1"></i>
                    ${escapeHtml(message)}
                </span>
                <span class="divider-time">${formattedTime}</span>
            </div>
        `;
    }

    messagesWrapper.appendChild(messageGroup);
    scrollToBottom(true);
}

if (chatForm) {
    chatForm.addEventListener('submit', async e => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;
        
        messageInput.disabled = true;
        const sendBtn = chatForm.querySelector('.send-button');
        const originalHTML = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const res = await fetch('/admin/livechat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    session_id: sessionData.id, 
                    message 
                })
            });
            
            const data = await res.json();
            
            if (data.success) { 
                addMessage(message, 'staff', new Date().toISOString()); 
                messageInput.value = ''; 
            } else {
                alert(data.message || 'Failed to send message');
            }
        } catch (err) { 
            console.error(err); 
            alert('Failed to send message. Please try again.'); 
        } finally { 
            messageInput.disabled = false;
            sendBtn.innerHTML = originalHTML;
            messageInput.focus(); 
        }
    });
}

// Polling for new messages
async function pollMessages() {
    try {
        const res = await fetch(`/admin/livechat/poll/${sessionData.id}?last_id=${lastMessageId}`);
        const data = await res.json();
        
        if (data.success && data.new_messages && data.new_messages.length > 0) {
            data.new_messages.forEach(msg => {
                const type = msg.sender_type === 'customer' ? 'customer' : 
                             msg.sender_type === 'system' ? 'system' : 'staff';
                addMessage(msg.message, type, msg.created_at);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });
        }
        
        if (data.status === 'closed' && sessionData.status === 'active') { 
            clearInterval(pollingInterval);
            sessionData.status = 'closed';
            addMessage('Chat session has been closed', 'system', new Date().toISOString());
            setTimeout(() => location.reload(), 2000);
        }
    } catch (err) { 
        console.error('Polling error:', err); 
    }
}

document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom(false);
    if (sessionData.status === 'active') { 
        pollingInterval = setInterval(pollMessages, 3000); 
    }
    
    // Focus input
    if (messageInput) {
        messageInput.focus();
    }
});

function endChat() {
    if (!confirm('Are you sure you want to end this chat session?')) return;
    
    fetch(`/admin/livechat/end/${sessionData.id}`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { 
            clearInterval(pollingInterval);
            addMessage('Chat session ended by staff', 'system', new Date().toISOString());
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.message || 'Failed to end chat');
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Error ending chat. Please try again.'); 
    });
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
@endpush
@endsection