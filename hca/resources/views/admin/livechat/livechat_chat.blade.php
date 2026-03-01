{{-- Save as: resources/views/admin/livechat/livechat_chat.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Live Chat - ' . $session->customer_name)

@section('content')
<div class="container-fluid px-4 py-2">
    <div class="mb-3">
        <a href="{{ route('admin.livechat.index') }}" class="btn btn-modern-secondary btn-sm border"
           style="border-color: var(--bs-border-color) !important;">
            <i class="fas fa-arrow-left me-2"></i> Back to Live Chats
        </a>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="admin-chat-layout">

        {{-- ===== LEFT SIDEBAR ===== --}}
        <div class="admin-chat-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <i class="fas fa-layer-group me-2"></i> Customer Info
            </div>

            {{-- Profile --}}
            <div class="sidebar-customer-profile">
                <div class="sidebar-avatar">{{ strtoupper(substr($session->customer_name, 0, 2)) }}</div>
                <div class="sidebar-customer-name">{{ $session->customer_name }}</div>
                @if($session->customer_email)
                <div class="sidebar-customer-email">{{ $session->customer_email }}</div>
                @endif
                <span class="sidebar-status-badge {{ $session->status === 'active' ? 'badge-active' : 'badge-closed' }}">
                    <i class="fas fa-circle me-1" style="font-size:8px"></i>{{ ucfirst($session->status) }}
                </span>
            </div>

            {{-- Tabs --}}
            <div class="sidebar-tabs">
                <button class="sidebar-tab active" onclick="switchTab('orders', this)">
                    <i class="fas fa-box me-1"></i> Orders
                </button>
                <button class="sidebar-tab" onclick="switchTab('customizations', this)">
                    <i class="fas fa-paint-brush me-1"></i> Customize
                </button>
            </div>

            {{-- Orders Tab --}}
            <div class="sidebar-tab-content active" id="tab-orders">
                <div class="sidebar-loading" id="ordersLoading">
                    <i class="fas fa-spinner fa-spin me-2"></i> Loading orders...
                </div>
                <div id="ordersList" class="sidebar-list"></div>
                <div class="sidebar-empty" id="ordersEmpty" style="display:none;">
                    <i class="fas fa-box-open"></i><p>No orders found</p>
                </div>
            </div>

            {{-- Customizations Tab --}}
            <div class="sidebar-tab-content" id="tab-customizations">
                <div class="sidebar-loading" id="customsLoading">
                    <i class="fas fa-spinner fa-spin me-2"></i> Loading...
                </div>
                <div id="customsList" class="sidebar-list"></div>
                <div class="sidebar-empty" id="customsEmpty" style="display:none;">
                    <i class="fas fa-palette"></i><p>No customizations found</p>
                </div>
            </div>
        </div>

        {{-- ===== CHAT AREA ===== --}}
        <div class="admin-chat-main">
            <div class="content-card chat-container">

                {{-- Chat Header --}}
                <div class="chat-header">
                    <div class="d-flex align-items-center gap-3">
                        <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="chat-user-avatar">
                            <div class="avatar-content">{{ strtoupper(substr($session->customer_name, 0, 2)) }}</div>
                            @if($session->status === 'active')
                            <span class="status-indicator status-active"></span>
                            @endif
                        </div>
                        <div class="chat-user-info">
                            <h5 class="user-name mb-0">{{ $session->customer_name }}</h5>
                            <div class="user-meta">
                                @if($session->customer_email)
                                <span class="meta-item"><i class="fas fa-envelope"></i> {{ $session->customer_email }}</span>
                                @endif
                                <span class="meta-item"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}</span>
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

                {{-- Messages --}}
                <div class="chat-body" id="chatMessages">
                    <div class="messages-wrapper">
                        @php
                            $cStatusMap = [
                                'Pending'   => ['class'=>'cs-pending',   'icon'=>'⏳'],
                                'Reviewing' => ['class'=>'cs-reviewing', 'icon'=>'🔍'],
                                'Approved'  => ['class'=>'cs-approved',  'icon'=>'✅'],
                                'Rejected'  => ['class'=>'cs-rejected',  'icon'=>'❌'],
                                'Completed' => ['class'=>'cs-completed', 'icon'=>'🎉'],
                            ];
                        @endphp

                        @foreach($messages as $message)
                            @php
                                $sender       = $message->sender_type;
                                $time         = \Carbon\Carbon::parse($message->created_at)->setTimezone(config('app.timezone','Asia/Manila'))->format('h:i A');
                                $hasProduct   = !empty($message->product_image);
                                $hasCustomize = !empty($message->customize_image);
                                $cSt          = $cStatusMap[$message->customize_status ?? 'Pending'] ?? $cStatusMap['Pending'];
                            @endphp

                            @if($sender === 'system')
                                <div class="message-group system-group">
                                    <div class="system-divider">
                                        <span class="divider-text"><i class="fas fa-info-circle me-1"></i>{{ $message->message }}</span>
                                        <span class="divider-time">{{ $time }}</span>
                                    </div>
                                </div>

                            @elseif($sender === 'customer')
                                <div class="message-group customer-group">
                                    <div class="message-avatar customer-avatar">{{ strtoupper(substr($session->customer_name,0,1)) }}</div>
                                    <div class="message-container">
                                        <div class="message-header">
                                            <span class="sender-name">{{ $session->customer_name }}</span>
                                            <span class="message-time">{{ $time }}</span>
                                        </div>
                                        <div class="message-bubble customer-bubble">
                                            @if($message->message)<div class="bubble-content">{{ $message->message }}</div>@endif
                                            @if($hasProduct)
                                            <div class="product-card-bubble">
                                                <img class="product-card-img" src="{{ $message->product_image }}" alt="{{ $message->product_name }}"
                                                     onclick="openImageModal('{{ addslashes($message->product_image) }}','{{ addslashes($message->product_name) }}')"
                                                     onerror="this.style.display='none'">
                                                <div class="product-card-info">
                                                    <div class="product-card-name">{{ $message->product_name }}</div>
                                                    <div class="product-card-price">₱{{ number_format($message->product_price,2) }}</div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($hasCustomize)
                                            <div class="customize-card-bubble customer-customize">
                                                <div class="customize-bubble-header">
                                                    <div class="customize-bubble-icon">🎨</div>
                                                    <div>
                                                        <div class="customize-bubble-title">{{ $message->customize_name }}</div>
                                                        <div class="customize-bubble-ref">{{ $message->customize_ref }}</div>
                                                    </div>
                                                </div>
                                                <img class="customize-bubble-img" src="{{ $message->customize_image }}" alt="Custom Image"
                                                     onclick="openImageModal('{{ addslashes($message->customize_image) }}','{{ addslashes($message->customize_name) }}')"
                                                     onerror="this.style.display='none'">
                                                @if($message->customize_price)<div class="customize-bubble-row">💰 ₱{{ number_format($message->customize_price,2) }}</div>@endif
                                                @if($message->customize_details)<div class="customize-bubble-row">📝 {{ Str::limit($message->customize_details,80) }}</div>@endif
                                                @if($message->customize_instructions)<div class="customize-bubble-row">💬 {{ Str::limit($message->customize_instructions,60) }}</div>@endif
                                                <span class="customize-bubble-status {{ $cSt['class'] }}">{{ $cSt['icon'] }} {{ $message->customize_status ?? 'Pending' }}</span>
                                            </div>
                                            @endif
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
                                            @if($message->message)<div class="bubble-content">{{ $message->message }}</div>@endif
                                            @if($hasProduct)
                                            <div class="product-card-bubble staff-product">
                                                <img class="product-card-img" src="{{ $message->product_image }}" alt="{{ $message->product_name }}"
                                                     onclick="openImageModal('{{ addslashes($message->product_image) }}','{{ addslashes($message->product_name) }}')"
                                                     onerror="this.style.display='none'">
                                                <div class="product-card-info">
                                                    <div class="product-card-name">{{ $message->product_name }}</div>
                                                    <div class="product-card-price">₱{{ number_format($message->product_price,2) }}</div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($hasCustomize)
                                            <div class="customize-card-bubble staff-customize">
                                                <div class="customize-bubble-header">
                                                    <div class="customize-bubble-icon">🎨</div>
                                                    <div>
                                                        <div class="customize-bubble-title">{{ $message->customize_name }}</div>
                                                        <div class="customize-bubble-ref">{{ $message->customize_ref }}</div>
                                                    </div>
                                                </div>
                                                <img class="customize-bubble-img" src="{{ $message->customize_image }}" alt="Custom Image"
                                                     onclick="openImageModal('{{ addslashes($message->customize_image) }}','{{ addslashes($message->customize_name) }}')"
                                                     onerror="this.style.display='none'">
                                                @if($message->customize_price)<div class="customize-bubble-row">💰 ₱{{ number_format($message->customize_price,2) }}</div>@endif
                                                @if($message->customize_details)<div class="customize-bubble-row">📝 {{ Str::limit($message->customize_details,80) }}</div>@endif
                                                @if($message->customize_instructions)<div class="customize-bubble-row">💬 {{ Str::limit($message->customize_instructions,60) }}</div>@endif
                                                <span class="customize-bubble-status {{ $cSt['class'] }}">{{ $cSt['icon'] }} {{ $message->customize_status ?? 'Pending' }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="message-avatar staff-avatar">{{ strtoupper(substr(session('admin_name','A'),0,1)) }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button id="scrollBtn" class="scroll-to-bottom" style="display:none;">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div id="typingIndicator" style="display:none;">
                        <div class="message-group customer-group">
                            <div class="message-avatar customer-avatar">{{ strtoupper(substr($session->customer_name,0,1)) }}</div>
                            <div class="message-container">
                                <div class="typing-dots"><span></span><span></span><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Input --}}
                <div class="chat-footer">
                    @if($session->status === 'active')
                    <form id="chatForm" class="chat-input-form">
                        <div class="input-wrapper">
                            <button type="button" class="input-action-btn"><i class="fas fa-paperclip"></i></button>
                            <input type="text" id="messageInput" class="message-input" placeholder="Type your message..." autocomplete="off" required>
                            <button type="submit" class="send-button">
                                <i class="fas fa-paper-plane"></i>
                                <span class="ms-2 d-none d-sm-inline">Send</span>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="chat-closed-notice"><i class="fas fa-lock me-2"></i> This chat session has been closed</div>
                    @endif
                </div>

            </div>
        </div>

    </div>{{-- end admin-chat-layout --}}
</div>

{{-- Image Modal --}}
<div class="img-modal-overlay" id="imgModalOverlay" onclick="closeImageModal(event)">
    <div class="img-modal-box">
        <div class="img-modal-header">
            <span class="img-modal-title" id="imgModalTitle"></span>
            <button class="img-modal-close" onclick="closeImageModal(null)">&#x2715;</button>
        </div>
        <div class="img-modal-body"><img id="imgModalImg" src="" alt=""></div>
    </div>
</div>

@push('styles')
<style>
/* LAYOUT */
.admin-chat-layout {
    display: flex; gap: 1.25rem;
    height: calc(100vh - 185px); min-height: 560px;
}

/* SIDEBAR */
.admin-chat-sidebar {
    width: 290px; min-width: 270px; flex-shrink: 0;
    background: var(--card-bg); border: 1px solid var(--border-color);
    border-radius: 16px; display: flex; flex-direction: column;
    height: 100%; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: width 0.3s ease, min-width 0.3s ease, opacity 0.3s ease;
}
.admin-chat-sidebar.collapsed { width:0; min-width:0; opacity:0; pointer-events:none; border:none; box-shadow:none; }
.sidebar-header { padding:1rem 1.25rem; font-weight:700; font-size:0.9rem; color:white; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); flex-shrink:0; }
.sidebar-customer-profile { padding:1.1rem 1rem; text-align:center; border-bottom:1px solid var(--border-color); flex-shrink:0; }
.sidebar-avatar { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); color:white; font-size:17px; font-weight:700; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; box-shadow:0 4px 12px rgba(255,107,157,0.3); }
.sidebar-customer-name  { font-size:0.9rem; font-weight:700; color:var(--text-primary); }
.sidebar-customer-email { font-size:0.72rem; color:var(--text-secondary); margin-top:2px; word-break:break-all; }
.sidebar-status-badge   { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; margin-top:0.4rem; }
.badge-active { background:#d4edda; color:#155724; }
.badge-closed { background:#f8d7da; color:#721c24; }

.sidebar-tabs  { display:flex; border-bottom:1px solid var(--border-color); flex-shrink:0; }
.sidebar-tab   { flex:1; padding:0.65rem 0.5rem; background:none; border:none; font-size:0.78rem; font-weight:600; color:var(--text-secondary); cursor:pointer; border-bottom:3px solid transparent; transition:all 0.2s; }
.sidebar-tab:hover  { color:var(--primary-pink); background:var(--hover-bg); }
.sidebar-tab.active { color:var(--primary-pink); border-bottom-color:var(--primary-pink); }

.sidebar-tab-content { display:none; flex:1; overflow-y:auto; padding:0.65rem; }
.sidebar-tab-content.active { display:flex; flex-direction:column; }
.sidebar-tab-content::-webkit-scrollbar { width:4px; }
.sidebar-tab-content::-webkit-scrollbar-thumb { background:var(--border-color); border-radius:4px; }

.sidebar-loading { text-align:center; padding:1.75rem 1rem; color:var(--text-secondary); font-size:0.82rem; }
.sidebar-empty   { text-align:center; padding:1.75rem 1rem; color:var(--text-secondary); }
.sidebar-empty i { font-size:1.8rem; opacity:0.35; margin-bottom:0.4rem; display:block; }
.sidebar-empty p { font-size:0.78rem; margin:0; }

.sidebar-order-card { background:var(--light-bg); border:1px solid var(--border-color); border-radius:10px; padding:0.7rem; margin-bottom:0.5rem; transition:box-shadow 0.2s; }
.sidebar-order-card:hover { box-shadow:0 2px 10px rgba(0,0,0,0.08); }
.sidebar-order-number { font-size:0.78rem; font-weight:700; color:var(--primary-pink); }
.sidebar-order-status { display:inline-block; padding:1px 7px; border-radius:6px; font-size:0.67rem; font-weight:600; margin-left:5px; }
.st-pending   { background:#fff3cd; color:#856404; }
.st-out       { background:#cce5ff; color:#004085; }
.st-delivered { background:#d4edda; color:#155724; }
.sidebar-order-meta  { font-size:0.72rem; color:var(--text-secondary); margin-top:3px; }
.sidebar-order-total { font-size:0.78rem; font-weight:700; color:var(--text-primary); margin-top:3px; }
.sidebar-order-items { display:flex; gap:4px; margin-top:6px; flex-wrap:wrap; }
.sidebar-order-img   { width:52px; height:52px; border-radius:8px; object-fit:cover; border:2px solid var(--border-color); cursor:zoom-in; transition:transform 0.2s; }
.sidebar-order-img:hover { transform:scale(1.08); box-shadow:0 3px 10px rgba(0,0,0,0.15); }
.sidebar-order-img-wrap  { display:flex; gap:5px; margin-top:7px; flex-wrap:wrap; }
.sidebar-share-btn   { width:100%; margin-top:7px; padding:5px; border-radius:8px; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); color:white; border:none; font-size:0.72rem; font-weight:600; cursor:pointer; transition:opacity 0.2s; }
.sidebar-share-btn:hover { opacity:0.88; }

.sidebar-custom-card    { background:var(--light-bg); border:1px solid var(--border-color); border-radius:10px; padding:0.7rem; margin-bottom:0.5rem; transition:box-shadow 0.2s; }
.sidebar-custom-card:hover { box-shadow:0 2px 10px rgba(0,0,0,0.08); }
.sidebar-custom-ref    { font-size:0.78rem; font-weight:700; color:var(--primary-pink); }
.sidebar-custom-name   { font-size:0.78rem; font-weight:600; color:var(--text-primary); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sidebar-custom-img    { width:100%; height:85px; object-fit:cover; border-radius:8px; margin:5px 0; border:1px solid var(--border-color); display:block; cursor:zoom-in; }
.sidebar-custom-status { display:inline-block; padding:2px 8px; border-radius:6px; font-size:0.67rem; font-weight:600; }
.sidebar-custom-detail { font-size:0.72rem; color:var(--text-secondary); margin-top:3px; }
.sidebar-share-btn-outline { width:100%; margin-top:7px; padding:5px; border-radius:8px; background:transparent; border:1.5px solid var(--primary-pink); color:var(--primary-pink); font-size:0.72rem; font-weight:600; cursor:pointer; transition:all 0.2s; }
.sidebar-share-btn-outline:hover { background:var(--primary-pink); color:white; }

.sidebar-toggle-btn { width:34px; height:34px; border-radius:9px; background:var(--light-bg); border:1px solid var(--border-color); color:var(--text-secondary); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.9rem; transition:all 0.2s; flex-shrink:0; }
.sidebar-toggle-btn:hover { background:var(--primary-pink); color:white; border-color:var(--primary-pink); }

/* CHAT MAIN */
.admin-chat-main { flex:1; min-width:0; height:100%; }
.chat-container  { display:flex; flex-direction:column; height:100%; overflow:hidden; border:1px solid var(--bs-border-color); }

.chat-header { padding:1.1rem 1.5rem; background:var(--card-bg); border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; flex-shrink:0; }
.chat-user-avatar { position:relative; flex-shrink:0; }
.avatar-content { width:46px; height:46px; border-radius:13px; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); color:white; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.status-indicator { position:absolute; bottom:1px; right:1px; width:12px; height:12px; border-radius:50%; border:2px solid var(--card-bg); }
.status-active    { background:#28a745; }
.chat-user-info   { flex:1; min-width:0; }
.user-name  { font-size:1rem; font-weight:700; color:var(--text-primary); margin:0; }
.user-meta  { display:flex; align-items:center; gap:0.875rem; margin-top:0.2rem; flex-wrap:wrap; }
.meta-item  { font-size:0.77rem; color:var(--text-secondary); display:flex; align-items:center; gap:0.3rem; }
.meta-item i { font-size:0.7rem; opacity:0.7; }
.chat-actions { display:flex; align-items:center; gap:0.75rem; }

.chat-body { flex:1; overflow-y:auto; background:var(--light-bg); position:relative; padding:1.5rem; }
.chat-body::-webkit-scrollbar { width:5px; }
.chat-body::-webkit-scrollbar-track { background:transparent; }
.chat-body::-webkit-scrollbar-thumb { background:var(--border-color); border-radius:10px; }
.messages-wrapper { max-width:800px; margin:0 auto; }

.message-group  { display:flex; margin-bottom:1.25rem; animation:msgIn 0.3s ease; }
@keyframes msgIn { from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);} }
.customer-group { justify-content:flex-start; }
.staff-group    { justify-content:flex-end; }
.system-group   { justify-content:center; margin:1.5rem 0; }

.message-avatar  { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:white; flex-shrink:0; box-shadow:0 2px 8px rgba(0,0,0,0.12); }
.customer-avatar { background:linear-gradient(135deg,#667eea,#764ba2); margin-right:10px; }
.staff-avatar    { background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); margin-left:10px; }

.message-container { max-width:70%; display:flex; flex-direction:column; }
.message-header    { display:flex; align-items:center; gap:0.4rem; margin-bottom:0.35rem; padding:0 0.2rem; }
.sender-name  { font-size:0.77rem; font-weight:600; color:var(--text-primary); }
.message-time { font-size:0.7rem; color:var(--text-secondary); }

.message-bubble { position:relative; padding:0.75rem 1rem; border-radius:14px; word-wrap:break-word; line-height:1.5; font-size:0.9rem; box-shadow:0 2px 8px rgba(0,0,0,0.06); transition:box-shadow 0.2s; }
.message-bubble:hover { box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.customer-bubble { background:var(--card-bg); color:var(--text-primary); border:1px solid var(--border-color); border-bottom-left-radius:4px; }
.staff-bubble    { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; border-bottom-right-radius:4px; }
.bubble-content  { white-space:pre-wrap; }

.product-card-bubble { display:flex; gap:10px; align-items:center; border-radius:11px; padding:7px 9px; margin-top:8px; border:1px solid rgba(255,255,255,0.3); }
.customer-bubble .product-card-bubble { background:rgba(102,126,234,0.08); border-color:rgba(102,126,234,0.25); }
.staff-bubble .product-card-bubble    { background:rgba(255,255,255,0.15); border-color:rgba(255,255,255,0.35); }
.product-card-img   { width:48px; height:48px; object-fit:cover; border-radius:7px; flex-shrink:0; cursor:zoom-in; border:2px solid rgba(255,255,255,0.4); transition:transform 0.2s; display:block; }
.customer-bubble .product-card-img { border-color:rgba(102,126,234,0.3); }
.product-card-img:hover { transform:scale(1.08); }
.product-card-name  { font-size:12px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.product-card-price { font-size:11px; opacity:0.85; margin-top:2px; }

.customize-card-bubble   { border-radius:11px; padding:9px; margin-top:8px; }
.customer-customize { background:rgba(255,152,0,0.06); border:1px solid rgba(255,152,0,0.3); }
.staff-customize    { background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.35); }
.customize-bubble-header { display:flex; align-items:center; gap:7px; margin-bottom:6px; }
.customize-bubble-icon   { width:27px; height:27px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
.customer-customize .customize-bubble-icon { background:rgba(255,152,0,0.15); }
.staff-customize .customize-bubble-icon    { background:rgba(255,255,255,0.2); }
.customize-bubble-title  { font-size:12px; font-weight:700; }
.customize-bubble-ref    { font-size:10px; opacity:0.75; }
.customize-bubble-img    { width:100%; max-height:110px; object-fit:cover; border-radius:8px; margin:5px 0; cursor:zoom-in; display:block; transition:transform 0.2s; }
.customize-bubble-img:hover { transform:scale(1.02); }
.customize-bubble-row    { font-size:11px; margin:3px 0; opacity:0.9; }
.customize-bubble-status { display:inline-block; padding:2px 8px; border-radius:7px; font-size:11px; font-weight:700; margin-top:5px; }
.cs-pending   {background:#FFF3CD;color:#856404;}
.cs-reviewing {background:#CCE5FF;color:#004085;}
.cs-approved  {background:#D4EDDA;color:#155724;}
.cs-rejected  {background:#F8D7DA;color:#721C24;}
.cs-completed {background:#D4EDDA;color:#155724;}
.staff-bubble .cs-pending   {background:rgba(255,243,205,0.35);color:#FFE082;}
.staff-bubble .cs-reviewing {background:rgba(204,229,255,0.3);color:#90CAF9;}
.staff-bubble .cs-approved  {background:rgba(212,237,218,0.3);color:#A5D6A7;}
.staff-bubble .cs-rejected  {background:rgba(248,215,218,0.3);color:#EF9A9A;}
.staff-bubble .cs-completed {background:rgba(212,237,218,0.3);color:#A5D6A7;}

.system-divider { display:flex; flex-direction:column; align-items:center; gap:0.35rem; }
.divider-text { display:inline-flex; align-items:center; padding:0.45rem 1rem; background:linear-gradient(135deg,rgba(102,126,234,0.1),rgba(118,75,162,0.1)); border:1px solid rgba(102,126,234,0.2); border-radius:20px; font-size:0.78rem; font-weight:500; color:var(--text-primary); }
.divider-time { font-size:0.67rem; color:var(--text-secondary); }

.typing-dots { display:flex; align-items:center; gap:4px; padding:0.85rem 1rem; background:var(--card-bg); border:1px solid var(--border-color); border-radius:14px; border-bottom-left-radius:4px; }
.typing-dots span { width:7px; height:7px; border-radius:50%; background:var(--text-secondary); animation:typingBounce 1.4s infinite; }
.typing-dots span:nth-child(2){animation-delay:0.2s;}
.typing-dots span:nth-child(3){animation-delay:0.4s;}
@keyframes typingBounce{0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-7px);}}

.scroll-to-bottom { position:absolute; bottom:1.25rem; right:1.25rem; width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); color:white; border:none; box-shadow:0 4px 16px rgba(0,0,0,0.15); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; transition:all 0.3s; z-index:10; }
.scroll-to-bottom:hover { transform:translateY(-2px); }

.chat-footer     { padding:1.1rem 1.5rem; background:var(--card-bg); border-top:1px solid var(--border-color); flex-shrink:0; }
.chat-input-form { max-width:800px; margin:0 auto; }
.input-wrapper   { display:flex; align-items:center; gap:0.6rem; padding:0.35rem; background:var(--light-bg); border:2px solid var(--border-color); border-radius:13px; transition:all 0.3s; }
.input-wrapper:focus-within { border-color:var(--primary-pink); background:var(--card-bg); box-shadow:0 0 0 3px rgba(255,107,157,0.1); }
.input-action-btn { width:36px; height:36px; border-radius:9px; background:transparent; border:none; color:var(--text-secondary); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.95rem; transition:all 0.2s; }
.input-action-btn:hover { background:var(--hover-bg); color:var(--primary-pink); }
.message-input  { flex:1; border:none; background:transparent; color:var(--text-primary); font-size:0.9rem; padding:0.4rem; outline:none; }
.message-input::placeholder { color:var(--text-secondary); }
.send-button    { padding:0.5rem 1.25rem; background:linear-gradient(135deg,var(--primary-pink),var(--secondary)); color:white; border:none; border-radius:10px; font-weight:600; font-size:0.9rem; cursor:pointer; display:flex; align-items:center; transition:all 0.3s; box-shadow:0 2px 8px rgba(255,107,157,0.3); }
.send-button:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(255,107,157,0.4); }
.chat-closed-notice { text-align:center; padding:0.875rem; color:var(--text-secondary); font-size:0.875rem; background:var(--hover-bg); border-radius:10px; }
@keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.7;transform:scale(1.1);}}
.pulse-animation { animation:pulse 2s ease-in-out infinite; }

.img-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:9999; align-items:center; justify-content:center; padding:1.5rem; backdrop-filter:blur(4px); }
.img-modal-overlay.active { display:flex; }
.img-modal-box   { background:white; border-radius:16px; overflow:hidden; max-width:520px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.4); animation:popIn 0.25s cubic-bezier(0.34,1.56,0.64,1); }
@keyframes popIn { from{transform:scale(0.85);opacity:0;}to{transform:scale(1);opacity:1;} }
.img-modal-header { display:flex; align-items:center; justify-content:space-between; padding:0.85rem 1.1rem; border-bottom:1px solid #dee2e6; }
.img-modal-title  { font-size:0.9rem; font-weight:600; color:#212529; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:85%; }
.img-modal-close  { background:none; border:none; font-size:1.3rem; cursor:pointer; color:#6c757d; line-height:1; padding:0 0.25rem; transition:color 0.15s; }
.img-modal-close:hover { color:#dc3545; }
.img-modal-body { padding:1rem; text-align:center; background:#f9f9f9; }
.img-modal-body img { max-width:100%; max-height:420px; object-fit:contain; border-radius:8px; display:block; margin:0 auto; }

@media(max-width:1100px){ .admin-chat-sidebar{width:255px;min-width:240px;} }
@media(max-width:900px){
    .admin-chat-layout{flex-direction:column;height:auto;}
    .admin-chat-sidebar{width:100%!important;min-width:0;max-width:100%;height:300px;}
    .admin-chat-main{height:calc(100vh - 200px);min-height:420px;}
}
@media(max-width:576px){
    .message-avatar{width:30px;height:30px;font-size:11px;}
    .message-container{max-width:90%;}
    .chat-footer{padding:0.75rem 1rem;}
    .send-button span{display:none!important;}
}
</style>
@endpush

@push('scripts')
<script>
@php
    $jsSession   = ['id' => $session->id, 'status' => $session->status, 'user_id' => $session->user_id];
    $jsCustName  = $session->customer_name;
    $jsCustInit  = strtoupper(substr($session->customer_name, 0, 1));
    $jsAdminInit = strtoupper(substr(session('admin_name', 'A'), 0, 1));
@endphp
const SESSION    = {!! json_encode($jsSession) !!};
const CUST_NAME  = {!! json_encode($jsCustName) !!};
const CUST_INIT  = {!! json_encode($jsCustInit) !!};
const ADMIN_INIT = {!! json_encode($jsAdminInit) !!};
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;

const chatMessages = document.getElementById('chatMessages');
const chatForm     = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const scrollBtn    = document.getElementById('scrollBtn');
let pollingInterval = null;
let lastMessageId   = {{ $messages->last()->id ?? 0 }};

/* ---- Helpers ---- */
function esc(t)  { const d=document.createElement('div'); d.textContent=t||''; return d.innerHTML; }
function fmt(v)  { return parseFloat(v||0).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2}); }
function safe(s) { return (s||'').replace(/'/g,"\\'"); }

function statusCls(s)  { return {Pending:'cs-pending',Reviewing:'cs-reviewing',Approved:'cs-approved',Rejected:'cs-rejected',Completed:'cs-completed'}[s]||'cs-pending'; }
function statusIcon(s) { return {Pending:'⏳',Reviewing:'🔍',Approved:'✅',Rejected:'❌',Completed:'🎉'}[s]||'⏳'; }
function orderCls(s)   { return {'Pending':'st-pending','Out for Delivery':'st-out','Delivered':'st-delivered'}[s]||'st-pending'; }

/* ---- Sidebar ---- */
function switchTab(tab, btn) {
    document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sidebar-tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-'+tab).classList.add('active');
}
function toggleSidebar() { document.getElementById('adminSidebar').classList.toggle('collapsed'); }

async function loadSidebarOrders() {
    try {
        const res  = await fetch(`/admin/livechat/customer-orders/${SESSION.user_id}`);
        const data = await res.json();
        document.getElementById('ordersLoading').style.display = 'none';
        const list = document.getElementById('ordersList');
        if (!data.success || !data.orders || !data.orders.length) {
            document.getElementById('ordersEmpty').style.display = 'block'; return;
        }
        data.orders.forEach(order => {
            const imgs = (order.product_images||[]).slice(0,4)
                .map(i => i.image_url
                    ? `<img class="sidebar-order-img" src="${esc(i.image_url)}" title="${esc(i.name)}" onerror="this.src='';this.style.display='none'" onclick="openImageModal('${safe(i.image_url)}','${safe(i.name)}')">`
                    : `<div class="sidebar-order-img" style="background:var(--hover-bg);display:flex;align-items:center;justify-content:center;font-size:18px;">📦</div>`
                ).join('');
            const card = document.createElement('div');
            card.className = 'sidebar-order-card';
            card.innerHTML = `
                <div>
                    <span class="sidebar-order-number">${esc(order.order_number)}</span>
                    <span class="sidebar-order-status ${orderCls(order.delivery_status)}">${esc(order.delivery_status)}</span>
                </div>
                <div class="sidebar-order-meta">${esc(order.address||'')} &middot; ${order.items_count} item(s)</div>
                <div class="sidebar-order-total">&#8369;${fmt(order.total)}</div>
                <div class="sidebar-order-items">${imgs}</div>
                <button class="sidebar-share-btn" onclick='shareOrderInChat(${JSON.stringify(order)})'>
                    <i class="fas fa-share me-1"></i> Share in Chat
                </button>`;
            list.appendChild(card);
        });
    } catch(e) {
        document.getElementById('ordersLoading').innerHTML = '<span style="color:red;font-size:0.8rem">Failed to load orders</span>';
    }
}

async function loadSidebarCustomizations() {
    try {
        const res  = await fetch(`/admin/livechat/customer-customizations/${SESSION.user_id}`);
        const data = await res.json();
        document.getElementById('customsLoading').style.display = 'none';
        const list = document.getElementById('customsList');
        if (!data.success || !data.customizations || !data.customizations.length) {
            document.getElementById('customsEmpty').style.display = 'block'; return;
        }
        data.customizations.forEach(c => {
            const card    = document.createElement('div');
            card.className = 'sidebar-custom-card';
            const imgHtml   = c.image_url
                ? `<img class="sidebar-custom-img" src="${esc(c.image_url)}" onerror="this.style.display='none'" onclick="openImageModal('${safe(c.image_url)}','${safe(c.customization_name||'')}')">` : '';
            const priceHtml = c.admin_price ? `<div class="sidebar-custom-detail">&#128176; &#8369;${fmt(c.admin_price)}</div>` : '';
            const detail    = (c.customization_details||'').substring(0,60)+((c.customization_details||'').length>60?'...':'');
            card.innerHTML = `
                <div>
                    <span class="sidebar-custom-ref">${esc(c.ref)}</span>
                    <span class="sidebar-custom-status ${statusCls(c.status)}">${statusIcon(c.status)} ${esc(c.status)}</span>
                </div>
                <div class="sidebar-custom-name">${esc(c.customization_name)}</div>
                ${imgHtml}${priceHtml}
                <div class="sidebar-custom-detail">${esc(detail)}</div>
                <button class="sidebar-share-btn-outline" onclick='shareCustomInChat(${JSON.stringify(c)})'>
                    <i class="fas fa-share me-1"></i> Share in Chat
                </button>`;
            list.appendChild(card);
        });
    } catch(e) {
        document.getElementById('customsLoading').innerHTML = '<span style="color:red;font-size:0.8rem">Failed to load customizations</span>';
    }
}

async function shareOrderInChat(order) {
    const msg = `Order ${order.order_number} — Status: ${order.delivery_status} | Total: ₱${fmt(order.total)} | ${order.items_count} item(s)`;
    // Get first product image & name to show in chat bubble
    const firstItem = (order.product_images && order.product_images.length > 0) ? order.product_images[0] : null;
    const productObj = firstItem ? {
        product_name:  firstItem.name,
        product_price: firstItem.price,
        product_image: firstItem.image_url,
    } : null;
    await sendAdminMessage(msg, null, productObj);
}

async function shareCustomInChat(c) {
    const msg = `Customization ${c.ref} "${c.customization_name}" — Status: ${c.status}` +
        (c.admin_price ? ` | Price: ₱${fmt(c.admin_price)}` : ' (awaiting pricing)');
    const msgObj = c.image_url ? {
        customize_ref: c.ref, customize_name: c.customization_name,
        customize_details: c.customization_details, customize_instructions: c.special_instructions,
        customize_status: c.status, customize_price: c.admin_price, customize_image: c.image_url,
    } : null;
    await sendAdminMessage(msg, msgObj);
}

/* ---- Image modal ---- */
function openImageModal(src, title) {
    document.getElementById('imgModalImg').src           = src;
    document.getElementById('imgModalTitle').textContent = title||'Image';
    document.getElementById('imgModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImageModal(event) {
    if (event && event.target !== document.getElementById('imgModalOverlay')) return;
    document.getElementById('imgModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeImageModal(null); });

/* ---- Scroll ---- */
function scrollToBottom(smooth=true) {
    chatMessages.scrollTo({top:chatMessages.scrollHeight, behavior:smooth?'smooth':'auto'});
    scrollBtn.style.display='none';
}
chatMessages.addEventListener('scroll', () => {
    scrollBtn.style.display = (chatMessages.scrollTop+chatMessages.clientHeight < chatMessages.scrollHeight-100) ? 'flex' : 'none';
});
scrollBtn.addEventListener('click', () => scrollToBottom(true));

/* ---- Build message HTML ---- */
function buildProductCard(msg) {
    if (!msg.product_image) return '';
    return `<div class="product-card-bubble">
        <img class="product-card-img" src="${esc(msg.product_image)}" alt="${esc(msg.product_name)}"
             onclick="openImageModal('${safe(msg.product_image)}','${safe(msg.product_name)}')"
             onerror="this.style.display='none'">
        <div class="product-card-info">
            <div class="product-card-name">${esc(msg.product_name)}</div>
            <div class="product-card-price">&#8369;${fmt(msg.product_price)}</div>
        </div>
    </div>`;
}

function buildCustomizeCard(msg, isStaff) {
    if (!msg.customize_image) return '';
    const cStatus = msg.customize_status||'Pending';
    const bc = isStaff ? 'staff-customize' : 'customer-customize';
    return `<div class="customize-card-bubble ${bc}">
        <div class="customize-bubble-header">
            <div class="customize-bubble-icon">&#127912;</div>
            <div>
                <div class="customize-bubble-title">${esc(msg.customize_name||'Custom Order')}</div>
                <div class="customize-bubble-ref">${esc(msg.customize_ref||'')}</div>
            </div>
        </div>
        <img class="customize-bubble-img" src="${esc(msg.customize_image)}"
             onclick="openImageModal('${safe(msg.customize_image)}','${safe(msg.customize_name)}')"
             onerror="this.style.display='none'">
        ${msg.customize_price?`<div class="customize-bubble-row">&#128176; &#8369;${fmt(msg.customize_price)}</div>`:''}
        ${msg.customize_details?`<div class="customize-bubble-row">&#128221; ${esc((msg.customize_details||'').substring(0,80))}</div>`:''}
        ${msg.customize_instructions?`<div class="customize-bubble-row">&#128172; ${esc((msg.customize_instructions||'').substring(0,60))}</div>`:''}
        <span class="customize-bubble-status ${statusCls(cStatus)}">${statusIcon(cStatus)} ${esc(cStatus)}</span>
    </div>`;
}

function addMessage(message, type, timestamp, msgObj) {
    const wrapper = document.querySelector('.messages-wrapper');
    const group   = document.createElement('div');
    group.className = `message-group ${type}-group`;
    const t  = new Date(timestamp).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',hour12:true});
    const ph = msgObj ? buildProductCard(msgObj) : '';
    const ch = msgObj ? buildCustomizeCard(msgObj, type==='staff') : '';
    const mh = message ? `<div class="bubble-content">${esc(message)}</div>` : '';

    if (type==='customer') {
        group.innerHTML=`
            <div class="message-avatar customer-avatar">${esc(CUST_INIT)}</div>
            <div class="message-container">
                <div class="message-header"><span class="sender-name">${esc(CUST_NAME)}</span><span class="message-time">${t}</span></div>
                <div class="message-bubble customer-bubble">${mh}${ph}${ch}</div>
            </div>`;
    } else if (type==='staff') {
        group.innerHTML=`
            <div class="message-container">
                <div class="message-header text-end"><span class="message-time">${t}</span><span class="sender-name">You</span></div>
                <div class="message-bubble staff-bubble">${mh}${ph}${ch}</div>
            </div>
            <div class="message-avatar staff-avatar">${esc(ADMIN_INIT)}</div>`;
    } else {
        group.innerHTML=`
            <div class="system-divider">
                <span class="divider-text"><i class="fas fa-info-circle me-1"></i>${esc(message)}</span>
                <span class="divider-time">${t}</span>
            </div>`;
    }
    wrapper.appendChild(group);
    scrollToBottom(true);
}

/* ---- Send ---- */
async function sendAdminMessage(message, customObj, productObj) {
    if (!message) return;
    const payload = {session_id: SESSION.id, message};
    if (customObj) {
        payload.customize_ref          = customObj.customize_ref;
        payload.customize_name         = customObj.customize_name;
        payload.customize_details      = customObj.customize_details;
        payload.customize_instructions = customObj.customize_instructions;
        payload.customize_status       = customObj.customize_status;
        payload.customize_price        = customObj.customize_price;
        payload.customize_image        = customObj.customize_image;
    }
    if (productObj) {
        payload.product_name  = productObj.product_name;
        payload.product_price = productObj.product_price;
        payload.product_image = productObj.product_image;
    }
    try {
        const res  = await fetch('/admin/livechat/send', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            // Merge both objects for bubble display
            const bubbleObj = Object.assign({}, customObj||{}, productObj||{});
            addMessage(message, 'staff', new Date().toISOString(), (customObj||productObj) ? bubbleObj : null);
        } else alert(data.message||'Failed to send');
    } catch(e) { console.error(e); alert('Failed to send message'); }
}

if (chatForm) {
    chatForm.addEventListener('submit', async e => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;
        messageInput.disabled = true;
        const sb = chatForm.querySelector('.send-button');
        const orig = sb.innerHTML;
        sb.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        await sendAdminMessage(message, null);
        messageInput.value = '';
        messageInput.disabled = false;
        sb.innerHTML = orig;
        messageInput.focus();
    });
}

/* ---- Poll ---- */
async function pollMessages() {
    try {
        const res  = await fetch(`/admin/livechat/poll/${SESSION.id}?last_id=${lastMessageId}`);
        const data = await res.json();
        if (data.success && data.new_messages && data.new_messages.length) {
            data.new_messages.forEach(msg => {
                const type = msg.sender_type==='customer'?'customer':msg.sender_type==='system'?'system':'staff';
                addMessage(msg.message, type, msg.created_at, msg);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });
        }
        if (data.status==='closed' && SESSION.status==='active') {
            clearInterval(pollingInterval); SESSION.status='closed';
            addMessage('Chat session has been closed','system',new Date().toISOString(),null);
            setTimeout(()=>location.reload(),2000);
        }
    } catch(e) { console.error('Poll error:',e); }
}

/* ---- End chat ---- */
function endChat() {
    if (!confirm('Are you sure you want to end this chat session?')) return;
    fetch(`/admin/livechat/end/${SESSION.id}`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.success){clearInterval(pollingInterval);addMessage('Chat session ended by staff','system',new Date().toISOString(),null);setTimeout(()=>location.reload(),1500);}
        else alert(data.message||'Failed to end chat');
    })
    .catch(e=>{console.error(e);alert('Error ending chat.');});
}

/* ---- Init ---- */
document.addEventListener('DOMContentLoaded',()=>{
    scrollToBottom(false);
    if(SESSION.status==='active') pollingInterval=setInterval(pollMessages,3000);
    if(messageInput) messageInput.focus();
    loadSidebarOrders();
    loadSidebarCustomizations();
});
window.addEventListener('beforeunload',()=>{if(pollingInterval)clearInterval(pollingInterval);});
</script>
@endpush
@endsection