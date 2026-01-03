{{-- Save as: resources/views/admin/livechat/livechat_view.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'View Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Back Button and Header -->
        <div class="col-12 mb-3">
            <a href="{{ route('admin.livechat.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Chat List
            </a>
        </div>

        <!-- Chat Info Card -->
        <div class="col-lg-12 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong><i class="fas fa-user"></i> Customer:</strong><br>
                            {{ $session->customer_name }}
                            @if($session->customer_email)
                                <br><small class="text-muted">{{ $session->customer_email }}</small>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-user-tie"></i> Handled By:</strong><br>
                            @if($session->admin_id)
                                {{ DB::table('admin')->where('id', $session->admin_id)->value('name') }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-clock"></i> Duration:</strong><br>
                            @if($session->started_at && $session->closed_at)
                                {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-calendar"></i> Date:</strong><br>
                            {{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Messages (Read-Only) -->
        <div class="col-lg-12">
            <div class="card shadow-lg" style="height: 70vh;">
                <!-- Chat Header -->
                <div class="card-header bg-gradient-secondary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="chat-avatar me-3">
                                    <i class="fas fa-history fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Chat History</h5>
                                    <small>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-archive"></i> Closed Session
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light btn-sm" onclick="printChat()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn btn-light btn-sm" onclick="exportChat()">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteThisChat()">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="card-body p-4" id="chatMessages" style="height: calc(70vh - 100px); overflow-y: auto; background: #f8f9fc;">
                    @if(count($messages) > 0)
                        @foreach($messages as $message)
                            @if($message->sender_type === 'system')
                                <div class="text-center my-3">
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-info-circle"></i> {{ $message->message }}
                                    </span>
                                    <div class="small text-muted mt-1">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y h:i A') }}
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
                                                <div class="message-sender">
                                                    {{ $session->customer_name }}
                                                </div>
                                                <div class="message-bubble bg-light">
                                                    {{ $message->message }}
                                                </div>
                                                <div class="message-time">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
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
                                                <div class="message-sender text-end">
                                                    @if($message->sender_id)
                                                        {{ DB::table('admin')->where('id', $message->sender_id)->value('name') ?? 'Staff' }}
                                                    @else
                                                        Staff
                                                    @endif
                                                </div>
                                                <div class="message-bubble bg-primary text-white">
                                                    {{ $message->message }}
                                                </div>
                                                <div class="message-time text-end">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                                </div>
                                            </div>
                                            <div class="message-avatar">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No messages in this chat session</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ route('admin.livechat.delete', $session->id) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.bg-gradient-secondary {
    background: linear-gradient(135deg, #858796 0%, #60616f 100%);
}

.chat-avatar {
    color: rgba(255, 255, 255, 0.9);
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

.message-sender {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #666;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

#chatMessages {
    scroll-behavior: smooth;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

/* Print styles */
@media print {
    .btn, .card-header button {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

<script>
// Auto scroll to bottom on load
window.addEventListener('load', () => {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

// Print chat function
function printChat() {
    window.print();
}

// Export chat as text
function exportChat() {
    const customerName = '{{ $session->customer_name }}';
    const chatDate = '{{ \Carbon\Carbon::parse($session->created_at)->format("Y-m-d") }}';
    
    let chatText = `Chat History - ${customerName}\n`;
    chatText += `Date: {{ \Carbon\Carbon::parse($session->created_at)->format("M d, Y h:i A") }}\n`;
    chatText += `Handled by: `;
    @if($session->admin_id)
        chatText += '{{ DB::table("admin")->where("id", $session->admin_id)->value("name") }}';
    @else
        chatText += 'Not assigned';
    @endif
    chatText += '\n';
    chatText += '='.repeat(60) + '\n\n';
    
    @foreach($messages as $message)
        @if($message->sender_type === 'system')
            chatText += '[SYSTEM] {{ $message->message }}\n';
            chatText += '{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}\n\n';
        @elseif($message->sender_type === 'customer')
            chatText += '[{{ $session->customer_name }}]\n';
            chatText += '{{ $message->message }}\n';
            chatText += '{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}\n\n';
        @else
            @if($message->sender_id)
                chatText += '[{{ DB::table("admin")->where("id", $message->sender_id)->value("name") ?? "Staff" }}]\n';
            @else
                chatText += '[Staff]\n';
            @endif
            chatText += '{{ $message->message }}\n';
            chatText += '{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}\n\n';
        @endif
    @endforeach
    
    // Create download
    const blob = new Blob([chatText], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `chat_${customerName.replace(/\s+/g, '_')}_${chatDate}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Delete this chat
function deleteThisChat() {
    const customerName = '{{ $session->customer_name }}';
    if (confirm(`Are you sure you want to delete this chat history with ${customerName}?\n\nThis action cannot be undone.`)) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection