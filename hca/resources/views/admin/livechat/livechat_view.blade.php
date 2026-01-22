{{-- Save as: resources/views/admin/livechat/livechat_view.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'View Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <a href="{{ route('admin.livechat.index') }}" class="btn btn-modern-secondary btn-sm mb-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Chat List
                    </a>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-modern-secondary btn-sm" onclick="printChat()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button class="btn btn-modern-primary btn-sm" onclick="exportChat()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteThisChat()">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-card">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-primary-soft">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Customer</label>
                                    <div class="fw-semibold">{{ $session->customer_name }}</div>
                                    @if($session->customer_email)
                                        <small class="text-muted">{{ $session->customer_email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-success-soft">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Handled By</label>
                                    <div class="fw-semibold">
                                        @if($session->admin_id)
                                            {{ DB::table('admin')->where('id', $session->admin_id)->value('name') }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-warning-soft">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Duration</label>
                                    <div class="fw-semibold">
                                        @if($session->started_at && $session->closed_at)
                                            {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-info-soft">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Session Date</label>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($session->created_at)->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Transcript Card -->
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <!-- Chat Header -->
                <div class="content-card-header">
                    <div>
                        <h5 class="content-card-title mb-1">
                            <i class="fas fa-comments me-2" style="color: var(--primary-pink);"></i>Chat Transcript
                        </h5>
                        <small class="text-muted">Complete conversation history</small>
                    </div>
                    <span class="badge-modern badge-info">
                        <i class="fas fa-archive me-1"></i> Closed Session
                    </span>
                </div>

                <!-- Chat Messages -->
                <div class="card-body p-4" id="chatMessages" style="height: 600px; overflow-y: auto; background: var(--light-bg);">
                    @forelse($messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="system-message-wrapper text-center my-4">
                                <div class="system-message-badge">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>{{ $message->message }}</span>
                                </div>
                                <div class="system-message-time">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @elseif($message->sender_type === 'customer')
                            <div class="message-wrapper customer-wrapper mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="message-avatar customer-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="message-content-wrapper">
                                        <div class="message-header">
                                            <span class="message-sender">{{ $session->customer_name }}</span>
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                        </div>
                                        <div class="message-bubble customer-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message-wrapper staff-wrapper mb-4">
                                <div class="d-flex align-items-start justify-content-end">
                                    <div class="message-content-wrapper text-end">
                                        <div class="message-header">
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                            <span class="message-sender">
                                                @if($message->sender_id)
                                                    {{ DB::table('admin')->where('id', $message->sender_id)->value('name') ?? 'Staff' }}
                                                @else
                                                    Staff
                                                @endif
                                            </span>
                                        </div>
                                        <div class="message-bubble staff-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                    <div class="message-avatar staff-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon">
                                <i class="fas fa-comments-slash"></i>
                            </div>
                            <h5 class="mt-3 text-muted">No Messages Found</h5>
                            <p class="text-muted mb-0">This chat session has no messages</p>
                        </div>
                    @endforelse
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

@push('styles')
<style>
/* Info Box Styling */
.info-box {
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.info-content {
    flex: 1;
}

.bg-primary-soft {
    background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(255, 107, 157, 0.05));
    color: var(--primary-pink);
}

.bg-success-soft {
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(72, 187, 120, 0.05));
    color: var(--success);
}

.bg-warning-soft {
    background: linear-gradient(135deg, rgba(246, 173, 85, 0.15), rgba(246, 173, 85, 0.05));
    color: var(--warning);
}

.bg-info-soft {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
    color: var(--secondary);
}

/* System Messages */
.system-message-wrapper {
    padding: 0 20px;
}

.system-message-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.system-message-time {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 8px;
}

/* Message Styling */
.message-wrapper {
    max-width: 85%;
}

.customer-wrapper {
    margin-left: 0;
    margin-right: auto;
}

.staff-wrapper {
    margin-left: auto;
    margin-right: 0;
}

.message-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
    color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.customer-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin-right: 12px;
}

.staff-avatar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    margin-left: 12px;
}

.message-content-wrapper {
    flex: 1;
    min-width: 0;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}

.message-sender {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
}

.message-time {
    font-size: 11px;
    color: var(--text-secondary);
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 16px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: inline-block;
    max-width: 100%;
}

.customer-bubble {
    background: var(--card-bg);
    color: var(--text-primary);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--border-color);
}

.staff-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: var(--border-color);
}

/* Chat Messages Container */
#chatMessages {
    scroll-behavior: smooth;
}

#chatMessages::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track {
    background: var(--light-bg);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .message-wrapper {
        max-width: 95%;
    }
    
    .info-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

/* Print Styles */
@media print {
    .btn, button, .badge-modern {
        display: none !important;
    }
    
    .content-card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    #chatMessages {
        height: auto !important;
        overflow: visible !important;
    }
    
    .message-bubble {
        break-inside: avoid;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto scroll to bottom on load
window.addEventListener('load', () => {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Print chat function
function printChat() {
    window.print();
}

// Export chat as text
function exportChat() {
    const customerName = '{{ $session->customer_name }}';
    const chatDate = '{{ \Carbon\Carbon::parse($session->created_at)->format("Y-m-d") }}';
    
    let chatText = `CHAT HISTORY EXPORT\n`;
    chatText += `${'='.repeat(80)}\n\n`;
    chatText += `Customer: ${customerName}\n`;
    @if($session->customer_email)
    chatText += `Email: {{ $session->customer_email }}\n`;
    @endif
    chatText += `Date: {{ \Carbon\Carbon::parse($session->created_at)->format("M d, Y h:i A") }}\n`;
    chatText += `Handled by: `;
    @if($session->admin_id)
        chatText += '{{ DB::table("admin")->where("id", $session->admin_id)->value("name") }}';
    @else
        chatText += 'Not assigned';
    @endif
    chatText += '\n';
    @if($session->started_at && $session->closed_at)
    chatText += `Duration: {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes\n`;
    @endif
    chatText += `\n${'='.repeat(80)}\n\n`;
    
    @foreach($messages as $message)
        @if($message->sender_type === 'system')
            chatText += `[SYSTEM NOTIFICATION - {{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}]\n`;
            chatText += `{{ $message->message }}\n\n`;
        @elseif($message->sender_type === 'customer')
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] {{ $session->customer_name }}:\n`;
            chatText += `{{ $message->message }}\n\n`;
        @else
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] `;
            @if($message->sender_id)
                chatText += '{{ DB::table("admin")->where("id", $message->sender_id)->value("name") ?? "Staff" }}';
            @else
                chatText += 'Staff';
            @endif
            chatText += `:\n{{ $message->message }}\n\n`;
        @endif
    @endforeach
    
    chatText += `${'='.repeat(80)}\n`;
    chatText += `End of chat transcript\n`;
    chatText += `Exported: ${new Date().toLocaleString()}\n`;
    
    // Create and trigger download
    const blob = new Blob([chatText], { type: 'text/plain;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `chat_transcript_${customerName.replace(/\s+/g, '_')}_${chatDate}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Delete chat with confirmation
function deleteThisChat() {
    const customerName = '{{ $session->customer_name }}';
    
    if (confirm(`⚠️ Delete Chat History\n\nAre you sure you want to permanently delete this chat conversation with ${customerName}?\n\nThis action cannot be undone.`)) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection{{-- Save as: resources/views/admin/livechat/livechat_view.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'View Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <a href="{{ route('admin.livechat.index') }}" class="btn btn-modern-secondary btn-sm mb-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Chat List
                    </a>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-modern-secondary btn-sm" onclick="printChat()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button class="btn btn-modern-primary btn-sm" onclick="exportChat()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteThisChat()">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-card">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-primary-soft">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Customer</label>
                                    <div class="fw-semibold">{{ $session->customer_name }}</div>
                                    @if($session->customer_email)
                                        <small class="text-muted">{{ $session->customer_email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-success-soft">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Handled By</label>
                                    <div class="fw-semibold">
                                        @if($session->admin_id)
                                            {{ DB::table('admin')->where('id', $session->admin_id)->value('name') }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-warning-soft">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Duration</label>
                                    <div class="fw-semibold">
                                        @if($session->started_at && $session->closed_at)
                                            {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-info-soft">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Session Date</label>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($session->created_at)->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Transcript Card -->
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <!-- Chat Header -->
                <div class="content-card-header">
                    <div>
                        <h5 class="content-card-title mb-1">
                            <i class="fas fa-comments me-2" style="color: var(--primary-pink);"></i>Chat Transcript
                        </h5>
                        <small class="text-muted">Complete conversation history</small>
                    </div>
                    <span class="badge-modern badge-info">
                        <i class="fas fa-archive me-1"></i> Closed Session
                    </span>
                </div>

                <!-- Chat Messages -->
                <div class="card-body p-4" id="chatMessages" style="height: 600px; overflow-y: auto; background: var(--light-bg);">
                    @forelse($messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="system-message-wrapper text-center my-4">
                                <div class="system-message-badge">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>{{ $message->message }}</span>
                                </div>
                                <div class="system-message-time">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @elseif($message->sender_type === 'customer')
                            <div class="message-wrapper customer-wrapper mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="message-avatar customer-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="message-content-wrapper">
                                        <div class="message-header">
                                            <span class="message-sender">{{ $session->customer_name }}</span>
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                        </div>
                                        <div class="message-bubble customer-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message-wrapper staff-wrapper mb-4">
                                <div class="d-flex align-items-start justify-content-end">
                                    <div class="message-content-wrapper text-end">
                                        <div class="message-header">
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                            <span class="message-sender">
                                                @if($message->sender_id)
                                                    {{ DB::table('admin')->where('id', $message->sender_id)->value('name') ?? 'Staff' }}
                                                @else
                                                    Staff
                                                @endif
                                            </span>
                                        </div>
                                        <div class="message-bubble staff-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                    <div class="message-avatar staff-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon">
                                <i class="fas fa-comments-slash"></i>
                            </div>
                            <h5 class="mt-3 text-muted">No Messages Found</h5>
                            <p class="text-muted mb-0">This chat session has no messages</p>
                        </div>
                    @endforelse
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

@push('styles')
<style>
/* Info Box Styling */
.info-box {
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.info-content {
    flex: 1;
}

.bg-primary-soft {
    background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(255, 107, 157, 0.05));
    color: var(--primary-pink);
}

.bg-success-soft {
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(72, 187, 120, 0.05));
    color: var(--success);
}

.bg-warning-soft {
    background: linear-gradient(135deg, rgba(246, 173, 85, 0.15), rgba(246, 173, 85, 0.05));
    color: var(--warning);
}

.bg-info-soft {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
    color: var(--secondary);
}

/* System Messages */
.system-message-wrapper {
    padding: 0 20px;
}

.system-message-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.system-message-time {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 8px;
}

/* Message Styling */
.message-wrapper {
    max-width: 85%;
}

.customer-wrapper {
    margin-left: 0;
    margin-right: auto;
}

.staff-wrapper {
    margin-left: auto;
    margin-right: 0;
}

.message-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
    color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.customer-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin-right: 12px;
}

.staff-avatar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    margin-left: 12px;
}

.message-content-wrapper {
    flex: 1;
    min-width: 0;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}

.message-sender {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
}

.message-time {
    font-size: 11px;
    color: var(--text-secondary);
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 16px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: inline-block;
    max-width: 100%;
}

.customer-bubble {
    background: var(--card-bg);
    color: var(--text-primary);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--border-color);
}

.staff-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: var(--border-color);
}

/* Chat Messages Container */
#chatMessages {
    scroll-behavior: smooth;
}

#chatMessages::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track {
    background: var(--light-bg);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .message-wrapper {
        max-width: 95%;
    }
    
    .info-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

/* Print Styles */
@media print {
    .btn, button, .badge-modern {
        display: none !important;
    }
    
    .content-card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    #chatMessages {
        height: auto !important;
        overflow: visible !important;
    }
    
    .message-bubble {
        break-inside: avoid;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto scroll to bottom on load
window.addEventListener('load', () => {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Print chat function
function printChat() {
    window.print();
}

// Export chat as text
function exportChat() {
    const customerName = '{{ $session->customer_name }}';
    const chatDate = '{{ \Carbon\Carbon::parse($session->created_at)->format("Y-m-d") }}';
    
    let chatText = `CHAT HISTORY EXPORT\n`;
    chatText += `${'='.repeat(80)}\n\n`;
    chatText += `Customer: ${customerName}\n`;
    @if($session->customer_email)
    chatText += `Email: {{ $session->customer_email }}\n`;
    @endif
    chatText += `Date: {{ \Carbon\Carbon::parse($session->created_at)->format("M d, Y h:i A") }}\n`;
    chatText += `Handled by: `;
    @if($session->admin_id)
        chatText += '{{ DB::table("admin")->where("id", $session->admin_id)->value("name") }}';
    @else
        chatText += 'Not assigned';
    @endif
    chatText += '\n';
    @if($session->started_at && $session->closed_at)
    chatText += `Duration: {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes\n`;
    @endif
    chatText += `\n${'='.repeat(80)}\n\n`;
    
    @foreach($messages as $message)
        @if($message->sender_type === 'system')
            chatText += `[SYSTEM NOTIFICATION - {{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}]\n`;
            chatText += `{{ $message->message }}\n\n`;
        @elseif($message->sender_type === 'customer')
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] {{ $session->customer_name }}:\n`;
            chatText += `{{ $message->message }}\n\n`;
        @else
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] `;
            @if($message->sender_id)
                chatText += '{{ DB::table("admin")->where("id", $message->sender_id)->value("name") ?? "Staff" }}';
            @else
                chatText += 'Staff';
            @endif
            chatText += `:\n{{ $message->message }}\n\n`;
        @endif
    @endforeach
    
    chatText += `${'='.repeat(80)}\n`;
    chatText += `End of chat transcript\n`;
    chatText += `Exported: ${new Date().toLocaleString()}\n`;
    
    // Create and trigger download
    const blob = new Blob([chatText], { type: 'text/plain;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `chat_transcript_${customerName.replace(/\s+/g, '_')}_${chatDate}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Delete chat with confirmation
function deleteThisChat() {
    const customerName = '{{ $session->customer_name }}';
    
    if (confirm(`⚠️ Delete Chat History\n\nAre you sure you want to permanently delete this chat conversation with ${customerName}?\n\nThis action cannot be undone.`)) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection{{-- Save as: resources/views/admin/livechat/livechat_view.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'View Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <a href="{{ route('admin.livechat.index') }}" class="btn btn-modern-secondary btn-sm mb-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Chat List
                    </a>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-modern-secondary btn-sm" onclick="printChat()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button class="btn btn-modern-primary btn-sm" onclick="exportChat()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteThisChat()">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-card">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-primary-soft">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Customer</label>
                                    <div class="fw-semibold">{{ $session->customer_name }}</div>
                                    @if($session->customer_email)
                                        <small class="text-muted">{{ $session->customer_email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-success-soft">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Handled By</label>
                                    <div class="fw-semibold">
                                        @if($session->admin_id)
                                            {{ DB::table('admin')->where('id', $session->admin_id)->value('name') }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-warning-soft">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Duration</label>
                                    <div class="fw-semibold">
                                        @if($session->started_at && $session->closed_at)
                                            {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-icon bg-info-soft">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-content">
                                    <label class="text-muted small mb-1">Session Date</label>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($session->created_at)->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Transcript Card -->
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <!-- Chat Header -->
                <div class="content-card-header">
                    <div>
                        <h5 class="content-card-title mb-1">
                            <i class="fas fa-comments me-2" style="color: var(--primary-pink);"></i>Chat Transcript
                        </h5>
                        <small class="text-muted">Complete conversation history</small>
                    </div>
                    <span class="badge-modern badge-info">
                        <i class="fas fa-archive me-1"></i> Closed Session
                    </span>
                </div>

                <!-- Chat Messages -->
                <div class="card-body p-4" id="chatMessages" style="height: 600px; overflow-y: auto; background: var(--light-bg);">
                    @forelse($messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="system-message-wrapper text-center my-4">
                                <div class="system-message-badge">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>{{ $message->message }}</span>
                                </div>
                                <div class="system-message-time">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @elseif($message->sender_type === 'customer')
                            <div class="message-wrapper customer-wrapper mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="message-avatar customer-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="message-content-wrapper">
                                        <div class="message-header">
                                            <span class="message-sender">{{ $session->customer_name }}</span>
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                        </div>
                                        <div class="message-bubble customer-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message-wrapper staff-wrapper mb-4">
                                <div class="d-flex align-items-start justify-content-end">
                                    <div class="message-content-wrapper text-end">
                                        <div class="message-header">
                                            <span class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
                                            <span class="message-sender">
                                                @if($message->sender_id)
                                                    {{ DB::table('admin')->where('id', $message->sender_id)->value('name') ?? 'Staff' }}
                                                @else
                                                    Staff
                                                @endif
                                            </span>
                                        </div>
                                        <div class="message-bubble staff-bubble">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                    <div class="message-avatar staff-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon">
                                <i class="fas fa-comments-slash"></i>
                            </div>
                            <h5 class="mt-3 text-muted">No Messages Found</h5>
                            <p class="text-muted mb-0">This chat session has no messages</p>
                        </div>
                    @endforelse
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

@push('styles')
<style>
/* Info Box Styling */
.info-box {
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.info-content {
    flex: 1;
}

.bg-primary-soft {
    background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(255, 107, 157, 0.05));
    color: var(--primary-pink);
}

.bg-success-soft {
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(72, 187, 120, 0.05));
    color: var(--success);
}

.bg-warning-soft {
    background: linear-gradient(135deg, rgba(246, 173, 85, 0.15), rgba(246, 173, 85, 0.05));
    color: var(--warning);
}

.bg-info-soft {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
    color: var(--secondary);
}

/* System Messages */
.system-message-wrapper {
    padding: 0 20px;
}

.system-message-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.system-message-time {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 8px;
}

/* Message Styling */
.message-wrapper {
    max-width: 85%;
}

.customer-wrapper {
    margin-left: 0;
    margin-right: auto;
}

.staff-wrapper {
    margin-left: auto;
    margin-right: 0;
}

.message-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
    color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.customer-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin-right: 12px;
}

.staff-avatar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    margin-left: 12px;
}

.message-content-wrapper {
    flex: 1;
    min-width: 0;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}

.message-sender {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
}

.message-time {
    font-size: 11px;
    color: var(--text-secondary);
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 16px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: inline-block;
    max-width: 100%;
}

.customer-bubble {
    background: var(--card-bg);
    color: var(--text-primary);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--border-color);
}

.staff-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: var(--border-color);
}

/* Chat Messages Container */
#chatMessages {
    scroll-behavior: smooth;
}

#chatMessages::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track {
    background: var(--light-bg);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .message-wrapper {
        max-width: 95%;
    }
    
    .info-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

/* Print Styles */
@media print {
    .btn, button, .badge-modern {
        display: none !important;
    }
    
    .content-card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    #chatMessages {
        height: auto !important;
        overflow: visible !important;
    }
    
    .message-bubble {
        break-inside: avoid;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto scroll to bottom on load
window.addEventListener('load', () => {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Print chat function
function printChat() {
    window.print();
}

// Export chat as text
function exportChat() {
    const customerName = '{{ $session->customer_name }}';
    const chatDate = '{{ \Carbon\Carbon::parse($session->created_at)->format("Y-m-d") }}';
    
    let chatText = `CHAT HISTORY EXPORT\n`;
    chatText += `${'='.repeat(80)}\n\n`;
    chatText += `Customer: ${customerName}\n`;
    @if($session->customer_email)
    chatText += `Email: {{ $session->customer_email }}\n`;
    @endif
    chatText += `Date: {{ \Carbon\Carbon::parse($session->created_at)->format("M d, Y h:i A") }}\n`;
    chatText += `Handled by: `;
    @if($session->admin_id)
        chatText += '{{ DB::table("admin")->where("id", $session->admin_id)->value("name") }}';
    @else
        chatText += 'Not assigned';
    @endif
    chatText += '\n';
    @if($session->started_at && $session->closed_at)
    chatText += `Duration: {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} minutes\n`;
    @endif
    chatText += `\n${'='.repeat(80)}\n\n`;
    
    @foreach($messages as $message)
        @if($message->sender_type === 'system')
            chatText += `[SYSTEM NOTIFICATION - {{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}]\n`;
            chatText += `{{ $message->message }}\n\n`;
        @elseif($message->sender_type === 'customer')
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] {{ $session->customer_name }}:\n`;
            chatText += `{{ $message->message }}\n\n`;
        @else
            chatText += `[{{ \Carbon\Carbon::parse($message->created_at)->format("h:i A") }}] `;
            @if($message->sender_id)
                chatText += '{{ DB::table("admin")->where("id", $message->sender_id)->value("name") ?? "Staff" }}';
            @else
                chatText += 'Staff';
            @endif
            chatText += `:\n{{ $message->message }}\n\n`;
        @endif
    @endforeach
    
    chatText += `${'='.repeat(80)}\n`;
    chatText += `End of chat transcript\n`;
    chatText += `Exported: ${new Date().toLocaleString()}\n`;
    
    // Create and trigger download
    const blob = new Blob([chatText], { type: 'text/plain;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `chat_transcript_${customerName.replace(/\s+/g, '_')}_${chatDate}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Delete chat with confirmation
function deleteThisChat() {
    const customerName = '{{ $session->customer_name }}';
    
    if (confirm(`⚠️ Delete Chat History\n\nAre you sure you want to permanently delete this chat conversation with ${customerName}?\n\nThis action cannot be undone.`)) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection