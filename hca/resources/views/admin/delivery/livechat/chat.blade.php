@extends('admin.delivery.layouts.delivery-layout')

@section('title', 'Live Chat - ' . $session->customer_name)

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Live Chat</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('delivery.livechat.index') }}">Chats</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $session->customer_name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('delivery.livechat.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
        @if($session->status === 'active')
            <button type="button" class="btn btn-danger" onclick="endChat()">
                <i class="bi bi-x-circle me-1"></i> End Chat
            </button>
        @endif
    </div>
</div>

<div class="row">
    <!-- Left Sidebar - Customer Info & Orders -->
    <div class="col-lg-4">
        <!-- Customer Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-person fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $session->customer_name }}</h5>
                        @if($session->customer_email)
                            <p class="text-muted mb-0">{{ $session->customer_email }}</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <p class="text-muted mb-1">Status</p>
                            @if($session->status === 'active')
                                <span class="badge bg-success">
                                    <i class="bi bi-circle-fill me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-circle me-1"></i>Closed
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <p class="text-muted mb-1">Chat Started</p>
                            <p class="mb-0 fw-semibold">
                                {{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ongoing Orders Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Ongoing Orders</h6>
                <span class="badge bg-primary">{{ $ongoingOrders->count() }}</span>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($ongoingOrders as $order)
                    <div class="order-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $order->order_number }}</h6>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, h:i A') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ 
                                $order->delivery_status === 'Pending' ? 'warning' : 
                                ($order->delivery_status === 'Out for Delivery' ? 'success' : 'secondary') 
                            }}">
                                {{ $order->delivery_status }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Items:</small>
                                <small class="fw-semibold">{{ $order->items_count }} item(s)</small>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Total:</small>
                                <small class="fw-semibold">₱{{ number_format($order->total, 2) }}</small>
                            </div>
                            @if($order->address)
                                <div class="mb-1">
                                    <small class="text-muted d-block mb-1">Address:</small>
                                    <small class="text-muted">{{ Str::limit($order->address, 50) }}</small>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" 
                                onclick="insertOrderReference('{{ $order->order_number }}')">
                            <i class="bi bi-chat-left-text me-1"></i>Reference in Chat
                        </button>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-box-seam display-6 text-muted mb-3"></i>
                        <p class="text-muted mb-0">No ongoing orders</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Messages</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-secondary text-start" 
                            onclick="sendQuickMessage('Can you provide your order number?')">
                        <i class="bi bi-question-circle me-2"></i>Ask for Order Number
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-start"
                            onclick="sendQuickMessage('Your order is on the way! Expected delivery today.')">
                        <i class="bi bi-truck me-2"></i>Delivery Update
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-start"
                            onclick="sendQuickMessage('Please provide your complete delivery address.')">
                        <i class="bi bi-geo-alt me-2"></i>Request Address
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-start"
                            onclick="sendQuickMessage('We apologize for the delay. Let me check the status for you.')">
                        <i class="bi bi-exclamation-triangle me-2"></i>Delay Notice
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-start"
                            onclick="sendQuickMessage('Your order has been delivered successfully!')">
                        <i class="bi bi-check-circle me-2"></i>Delivery Confirmation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Chat Interface -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-0 d-flex flex-column">
                <!-- Chat Messages Area -->
                <div class="flex-grow-1 p-4" id="chatMessages" 
                     style="overflow-y: auto; height: calc(100vh - 400px);">
                    
                    @forelse($messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="text-center my-4">
                                <div class="badge bg-info px-3 py-2">
                                    <i class="bi bi-info-circle me-1"></i> {{ $message->message }}
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                </div>
                            </div>
                        @elseif($message->sender_type === 'customer')
                            <div class="message mb-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar bg-light-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="bg-light rounded p-3">
                                            <div class="mb-1">
                                                <span class="fw-semibold">{{ $session->customer_name }}</span>
                                                <span class="text-muted small ms-2">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                                </span>
                                            </div>
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message mb-4">
                                <div class="d-flex justify-content-end">
                                    <div class="flex-grow-1 me-3">
                                        <div class="bg-primary text-white rounded p-3">
                                            <div class="mb-1">
                                                <span class="fw-semibold">You</span>
                                                <span class="opacity-75 small ms-2">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                                </span>
                                            </div>
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar bg-light-success rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-truck text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-5">
                            <div class="avatar avatar-xl bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-chat-left-text display-6 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No messages yet</h5>
                            <p class="text-muted">Start the conversation by sending a message</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Input Area -->
                <div class="border-top p-4">
                    @if($session->status === 'active')
                        <form id="chatForm" class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <input 
                                    type="text" 
                                    class="form-control form-control-lg" 
                                    id="messageInput" 
                                    placeholder="Type your message..."
                                    autocomplete="off"
                                    required
                                >
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-4" id="sendBtn">
                                <i class="bi bi-send me-1"></i> Send
                            </button>
                        </form>
                        <div class="mt-2 text-muted small">
                            Press Enter to send • Press Shift+Enter for new line
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-chat-square-text fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Chat Session Closed</h6>
                                    <p class="mb-0">This chat session has ended. You can no longer send messages.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

#chatMessages::-webkit-scrollbar {
    width: 6px;
}

#chatMessages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.order-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.card {
    border-color: #e0e0e0;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom-color: #e0e0e0;
}
</style>

<script>
// Constants
const sessionId = {{ $session->id }};
const sessionStatus = '{{ $session->status }}';
let pollingInterval = null;
let customerWarningShown = false;

// DOM Elements
const chatMessages = document.getElementById('chatMessages');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');

// Utility Functions
function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
}

function insertOrderReference(orderNumber) {
    messageInput.value = `Regarding order ${orderNumber}: `;
    messageInput.focus();
}

function sendQuickMessage(message) {
    if (!messageInput || sessionStatus !== 'active') return;
    messageInput.value = message;
    messageInput.focus();
}

// Message Handling
function addSystemMessage(message) {
    const now = new Date();
    const time = formatTime(now);
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'text-center my-4';
    messageDiv.innerHTML = `
        <div class="badge bg-info px-3 py-2">
            <i class="bi bi-info-circle me-1"></i> ${escapeHtml(message)}
        </div>
        <div class="text-muted small mt-1">${time}</div>
    `;
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function addMessage(message, type, timestamp) {
    const time = formatTime(timestamp);
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message mb-4';
    
    if (type === 'customer') {
        messageDiv.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <div class="avatar bg-light-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-person text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="bg-light rounded p-3">
                        <div class="mb-1">
                            <span class="fw-semibold">{{ $session->customer_name }}</span>
                            <span class="text-muted small ms-2">${time}</span>
                        </div>
                        <p class="mb-0">${escapeHtml(message)}</p>
                    </div>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="d-flex justify-content-end">
                <div class="flex-grow-1 me-3">
                    <div class="bg-primary text-white rounded p-3">
                        <div class="mb-1">
                            <span class="fw-semibold">You</span>
                            <span class="opacity-75 small ms-2">${time}</span>
                        </div>
                        <p class="mb-0">${escapeHtml(message)}</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="avatar bg-light-success rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-truck text-success"></i>
                    </div>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Form Submission
if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) {
            messageInput.focus();
            return;
        }
        
        // Disable form while sending
        sendBtn.disabled = true;
        messageInput.disabled = true;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            const response = await fetch('{{ route("delivery.livechat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Add message to UI immediately
                addMessage(message, 'delivery', new Date().toISOString());
                messageInput.value = '';
            } else {
                alert(data.message || 'Failed to send message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Network error. Please try again.');
        } finally {
            // Re-enable form
            sendBtn.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        }
    });
    
    // Handle Enter key (send) and Shift+Enter (new line)
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
}

// Polling for new messages
function startPolling() {
    if (sessionStatus !== 'active') return;
    
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch(`{{ url('delivery/livechat/poll') }}/${sessionId}`);
            const data = await response.json();
            
            if (data.success && data.new_messages && data.new_messages.length > 0) {
                data.new_messages.forEach(msg => {
                    addMessage(msg.message, 'customer', msg.created_at);
                });
            }
            
            // Handle customer status changes
            if (data.customer_active !== undefined) {
                if (data.customer_active === false && !customerWarningShown) {
                    customerWarningShown = true;
                    addSystemMessage('Customer has left the chat page');
                } else if (data.customer_active === true && customerWarningShown) {
                    customerWarningShown = false;
                    addSystemMessage('Customer has returned to the chat');
                }
            }
            
            // Handle auto-end warnings
            if (data.auto_end_warning) {
                addSystemMessage('Chat will auto-end soon due to customer inactivity');
            }
            
            // Handle session closure
            if (data.status === 'closed') {
                clearInterval(pollingInterval);
                if (data.reason === 'customer_inactive') {
                    addSystemMessage('Chat ended: Customer was inactive for 15 minutes');
                    setTimeout(() => location.reload(), 3000);
                } else {
                    location.reload();
                }
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 3000);
}

// End chat function
async function endChat() {
    if (!confirm('Are you sure you want to end this chat session?')) {
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const response = await fetch(`{{ url('delivery/livechat/end') }}/${sessionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
            }
        });
        
        if (response.ok) {
            window.location.href = '{{ route("delivery.livechat.index") }}';
        } else {
            alert('Failed to end chat');
        }
    } catch (error) {
        console.error('Error ending chat:', error);
        alert('Failed to end chat');
    }
}

// Initialize
window.addEventListener('load', () => {
    scrollToBottom();
    if (sessionStatus === 'active') {
        startPolling();
        messageInput?.focus();
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