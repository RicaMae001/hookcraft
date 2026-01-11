{{-- Save as: resources/views/admin/livechat/livechat_chat.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Live Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Back Button and Header -->
        <div class="col-12 mb-3">
            <a href="{{ route('admin.livechat.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Chat List
            </a>
        </div>

        <!-- Chat Interface -->
        <div class="col-lg-12">
            <div class="card shadow-lg" style="height: 80vh;">
                <!-- Chat Header -->
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="chat-avatar me-3">
                                    <i class="fas fa-user-circle fa-3x"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $session->customer_name }}</h5>
                                    <small>
                                        @if($session->customer_email)
                                            <i class="fas fa-envelope"></i> {{ $session->customer_email }}
                                        @endif
                                        @if($session->status === 'active')
                                            <span class="badge bg-success ms-2">
                                                <i class="fas fa-circle pulse"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary ms-2">
                                                <i class="fas fa-times-circle"></i> Closed
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($session->status === 'active')
                                <button type="button" class="btn btn-danger btn-sm" onclick="endChat()">
                                    <i class="fas fa-times-circle"></i> End Chat
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="card-body p-4" id="chatMessages" style="height: calc(80vh - 200px); overflow-y: auto; background: #f8f9fc;">
                    @forelse($messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="text-center my-3">
                                <span class="badge bg-info px-3 py-2">
                                    <i class="fas fa-info-circle"></i> {{ $message->message }}
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ \Carbon\Carbon::parse($message->created_at)->setTimezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}
                                </div>
                            </div>
                        @elseif($message->sender_type === 'customer')
                            <div class="message-wrapper mb-3">
                                <div class="d-flex justify-content-start">
                                    <div class="message customer-message">
                                        <div class="message-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="message-content">
                                            <div class="message-bubble bg-light">
                                                {{ $message->message }}
                                            </div>
                                            <div class="message-time">
                                                {{ \Carbon\Carbon::parse($message->created_at)->setTimezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message-wrapper mb-3">
                                <div class="d-flex justify-content-end">
                                    <div class="message staff-message">
                                        <div class="message-content">
                                            <div class="message-bubble bg-primary text-white">
                                                {{ $message->message }}
                                            </div>
                                            <div class="message-time text-end">
                                                {{ \Carbon\Carbon::parse($message->created_at)->setTimezone(config('app.timezone', 'Asia/Manila'))->format('h:i A') }}
                                            </div>
                                        </div>
                                        <div class="message-avatar">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comments-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No messages yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Input -->
                <div class="card-footer bg-white border-top">
                    @if($session->status === 'active')
                        <form id="chatForm" class="d-flex align-items-center gap-2">
                            <input 
                                type="text" 
                                class="form-control" 
                                id="messageInput" 
                                placeholder="Type your message..."
                                autocomplete="off"
                                required
                            >
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>
                    @else
                        <div class="alert alert-secondary mb-0">
                            <i class="fas fa-info-circle"></i> This chat session has been closed.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.chat-avatar {
    color: rgba(255, 255, 255, 0.8);
}

.message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}

.customer-message .message-avatar {
    background: linear-gradient(135deg, #FFB6C1 0%, #FF69B4 100%);
    color: white;
}

.staff-message .message-avatar {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
}

.message-content {
    max-width: 70%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

.customer-message .message-bubble {
    border-bottom-left-radius: 4px;
}

.staff-message .message-bubble {
    border-bottom-right-radius: 4px;
}

.message-time {
    font-size: 11px;
    color: #6c757d;
    margin-top: 4px;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

#chatMessages {
    scroll-behavior: smooth;
}

#messageInput:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}
</style>

<script>
const sessionId = {{ $session->id }};
const sessionStatus = '{{ $session->status }}';
const chatMessages = document.getElementById('chatMessages');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
let pollingInterval = null;
let customerActiveTimeout = null;
let customerWarningShown = false;

// Get user's timezone
const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

// Scroll to bottom
function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Format time to local timezone
function formatTimeToLocal(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true
    });
}

// Show customer status notification
function showCustomerStatus(status) {
    const existingNotification = document.getElementById('customerStatusNotification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.id = 'customerStatusNotification';
    notification.className = 'alert alert-warning alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    if (status === 'away') {
        notification.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Customer Away</strong><br>
            Customer has left the chat page. Chat will auto-end in 15 minutes if they don't return.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    } else if (status === 'back') {
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <strong>Customer Back</strong><br>
            Customer has returned to the chat.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Auto-dismiss success notification after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    } else if (status === 'auto-ending') {
        notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        notification.innerHTML = `
            <i class="fas fa-clock"></i>
            <strong>Auto-Ending Chat</strong><br>
            Customer has been away for 15 minutes. Chat will end automatically.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    }
    
    document.body.appendChild(notification);
}

// Add system message to chat
function addSystemMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'text-center my-3';
    messageDiv.innerHTML = `
        <span class="badge bg-warning px-3 py-2">
            <i class="fas fa-info-circle"></i> ${message}
        </span>
        <div class="small text-muted mt-1">
            ${formatTimeToLocal(new Date().toISOString())}
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Add message to chat
function addMessage(message, type, timestamp) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message-wrapper mb-3';
    
    // Format the timestamp to local time
    const formattedTime = formatTimeToLocal(timestamp);
    
    if (type === 'customer') {
        messageDiv.innerHTML = `
            <div class="d-flex justify-content-start">
                <div class="message customer-message">
                    <div class="message-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble bg-light">
                            ${escapeHtml(message)}
                        </div>
                        <div class="message-time">
                            ${formattedTime}
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'admin') {
        messageDiv.innerHTML = `
            <div class="d-flex justify-content-end">
                <div class="message staff-message">
                    <div class="message-content">
                        <div class="message-bubble bg-primary text-white">
                            ${escapeHtml(message)}
                        </div>
                        <div class="message-time text-end">
                            ${formattedTime}
                        </div>
                    </div>
                    <div class="message-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Send message
if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        sendBtn.disabled = true;
        
        try {
            const response = await fetch('{{ route("admin.livechat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    message: message
                })
            });
            
            if (response.ok) {
                // Use current timestamp in ISO format
                addMessage(message, 'admin', new Date().toISOString());
                messageInput.value = '';
            } else {
                alert('Failed to send message');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to send message');
        } finally {
            sendBtn.disabled = false;
            messageInput.focus();
        }
    });
}

// Poll for new messages and customer activity
function startPolling() {
    if (sessionStatus !== 'active') return;
    
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch(`{{ url('admin/livechat/poll') }}/${sessionId}`);
            const data = await response.json();
            
            if (data.success && data.new_messages && data.new_messages.length > 0) {
                data.new_messages.forEach(msg => {
                    addMessage(msg.message, 'customer', msg.created_at);
                });
            }
            
            // Check customer activity status
            if (data.customer_active !== undefined) {
                if (data.customer_active === false && !customerWarningShown) {
                    customerWarningShown = true;
                    showCustomerStatus('away');
                    addSystemMessage('⚠️ Customer has left the chat page');
                } else if (data.customer_active === true && customerWarningShown) {
                    customerWarningShown = false;
                    showCustomerStatus('back');
                    addSystemMessage('✓ Customer has returned to the chat');
                }
            }
            
            // Check for auto-end warning
            if (data.auto_end_warning) {
                showCustomerStatus('auto-ending');
                addSystemMessage('⏰ Chat will auto-end soon due to customer inactivity');
            }
            
            if (data.status === 'closed') {
                clearInterval(pollingInterval);
                if (data.reason === 'customer_inactive') {
                    addSystemMessage('🔴 Chat ended: Customer was inactive for 15 minutes');
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    location.reload();
                }
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 3000); // Poll every 3 seconds
}

// End chat
async function endChat() {
    if (!confirm('Are you sure you want to end this chat session?')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ url('admin/livechat/end') }}/${sessionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });
        
        if (response.ok) {
            window.location.href = '{{ route("admin.livechat.index") }}';
        } else {
            alert('Failed to end chat');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to end chat');
    }
}

// Initialize
window.addEventListener('load', () => {
    scrollToBottom();
    if (sessionStatus === 'active') {
        startPolling();
        messageInput.focus();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
@endsection