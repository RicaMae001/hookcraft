<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Chat Support - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
            width: 110%;
            max-width: 950px;
            display: flex;
            gap: 20px;
        }

        /* ── Orders Sidebar ── */
        .orders-sidebar {
            width: 350px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(255,105,180,0.25);
            overflow: hidden;
            display: none;
        }
        .orders-sidebar.active { display: block; }

        .orders-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 20px;
        }
        .orders-header h3 { font-size: 1.1em; margin-bottom: 3px; }
        .orders-header small { opacity: 0.85; font-size: 12px; }

        .orders-content {
            padding: 15px;
            max-height: 520px;
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
        .order-card:hover { border-color: #4CAF50; box-shadow: 0 4px 15px rgba(76,175,80,0.2); }

        .order-number { font-weight: bold; color: #4CAF50; margin-bottom: 8px; font-size: 14px; }

        .order-status {
            display: inline-block;
            padding: 4px 12px; border-radius: 12px;
            font-size: 12px; font-weight: 600; margin-bottom: 8px;
        }
        .status-pending          { background: #FFF3CD; color: #856404; }
        .status-out_for_delivery { background: #D4EDDA; color: #155724; }

        .order-detail { font-size: 13px; color: #666; margin: 5px 0; }
        .order-detail i { width: 16px; color: #4CAF50; }

        /* ── Product thumbnails in sidebar ── */
        .order-products { display: flex; gap: 8px; flex-wrap: wrap; margin: 10px 0 4px; }

        .product-thumb { position: relative; cursor: zoom-in; }
        .product-thumb img {
            width: 58px; height: 58px; object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
            display: block;
        }
        .product-thumb:hover img {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
            border-color: #4CAF50;
        }
        .product-thumb .thumb-tooltip {
            display: none;
            position: absolute;
            bottom: calc(100% + 6px); left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.78);
            color: white; font-size: 11px;
            padding: 5px 9px; border-radius: 7px;
            white-space: nowrap; z-index: 10;
            pointer-events: none; text-align: center;
        }
        .product-thumb:hover .thumb-tooltip { display: block; }

        .product-hint { font-size: 11px; color: #aaa; margin: 4px 0 8px; }

        .send-products-btn {
            width: 100%; padding: 8px;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white; border: none; border-radius: 10px;
            font-size: 12px; font-weight: 600; cursor: pointer;
            margin-bottom: 6px; transition: all 0.3s;
        }
        .send-products-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(33,150,243,0.35); }

        .reference-btn {
            width: 100%; padding: 8px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white; border: none; border-radius: 10px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .reference-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(76,175,80,0.35); }

        .no-orders { text-align: center; padding: 40px 20px; color: #999; }
        .no-orders i { font-size: 48px; margin-bottom: 15px; display: block; }

        /* ── Chat Container ── */
        .chat-container {
            flex: 1; height: 600px; background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(255,105,180,0.25);
            display: flex; flex-direction: column; overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
            color: white; padding: 25px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .bot-avatar {
            width: 50px; height: 50px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-info h2 { font-size: 1.5em; margin-bottom: 3px; }
        .header-info p  { font-size: 0.9em; opacity: 0.9; }
        .header-info p.live-chat-status { color: #FFD700; font-weight: 600; }
        .header-actions { display: flex; align-items: center; gap: 10px; }

        .home-btn {
            background: white; color: #FF69B4; border: none;
            padding: 8px 16px; border-radius: 20px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 8px;
            transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .home-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }

        .end-chat-btn {
            background: #FF4444; color: white; border: none;
            padding: 8px 16px; border-radius: 20px; font-weight: 600;
            display: none; align-items: center; gap: 8px;
            cursor: pointer; transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255,68,68,0.3);
        }
        .end-chat-btn.active { display: flex; }
        .end-chat-btn:hover  { transform: translateY(-2px); }

        /* Support Type Selector */
        .support-type-selector {
            display: none; padding: 20px 25px;
            background: white; border-top: 2px solid #FFE4E8;
        }
        .support-type-selector.active { display: block; }
        .support-type-label { font-size: 14px; color: #666; margin-bottom: 15px; text-align: center; font-weight: 600; }
        .support-type-buttons { display: flex; gap: 10px; }
        .support-type-btn {
            flex: 1; padding: 15px; border: 2px solid #FFE4E8;
            border-radius: 15px; background: white; cursor: pointer;
            transition: all 0.3s; text-align: center;
        }
        .support-type-btn:hover          { border-color: #FF69B4; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(255,105,180,0.2); }
        .support-type-btn.delivery:hover { border-color: #2196F3; box-shadow: 0 4px 15px rgba(33,150,243,0.2); }
        .support-type-btn i              { font-size: 28px; display: block; margin-bottom: 8px; }
        .support-type-btn.staff i        { color: #FF69B4; }
        .support-type-btn.delivery i     { color: #2196F3; }
        .support-type-btn .btn-title     { font-weight: 600; color: #333; font-size: 14px; margin-bottom: 4px; }
        .support-type-btn .btn-desc      { font-size: 11px; color: #999; }

        /* Messages */
        .chat-messages { flex: 1; overflow-y: auto; padding: 25px; background: #FAFAFA; }

        .message { display: flex; margin-bottom: 20px; animation: slideIn 0.3s ease; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .message.user   { justify-content: flex-end; }
        .message.system { justify-content: center; }
        .message.system .message-content {
            background: #FFF3CD; color: #856404;
            border: 1px solid #FFE69C; text-align: center; max-width: 80%;
        }
        .message-content { max-width: 70%; padding: 15px 20px; border-radius: 18px; }
        .message.user .message-content {
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            color: white; border-bottom-right-radius: 4px;
            box-shadow: 0 2px 10px rgba(255,105,180,0.3);
        }
        .message.staff .message-content {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white; border-bottom-left-radius: 4px;
            box-shadow: 0 2px 10px rgba(76,175,80,0.3);
        }
        .message.delivery .message-content {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white; border-bottom-left-radius: 4px;
            box-shadow: 0 2px 10px rgba(33,150,243,0.3);
        }
        .message-avatar {
            width: 35px; height: 35px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 10px; flex-shrink: 0;
        }
        .message.user .message-avatar     { background: linear-gradient(135deg,#FFE4E1,#FFB6C1); color: #FF69B4; }
        .message.staff .message-avatar    { background: linear-gradient(135deg,#4CAF50,#45a049); color: white; }
        .message.delivery .message-avatar { background: linear-gradient(135deg,#2196F3,#1976D2); color: white; }

        .sender-name { font-size: 12px; font-weight: 600; margin-bottom: 5px; opacity: 0.8; }
        .timestamp   { font-size: 11px; opacity: 0.6; margin-top: 6px; }

        /* ── Product card inside message bubble ── */
        .product-card-bubble {
            display: flex; gap: 10px; align-items: center;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 12px; padding: 8px 10px; margin-top: 8px;
        }
        /* ── Product image — identical behaviour to checkout page ── */
        .product-card-img {
            width: 56px; height: 56px; object-fit: cover;
            border-radius: 8px; flex-shrink: 0;
            cursor: zoom-in;
            border: 2px solid rgba(255,255,255,0.45);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: block;
        }
        .product-card-img:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.22);
        }
        .product-card-info    { flex: 1; min-width: 0; }
        .product-card-name    { font-size: 13px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .product-card-price   { font-size: 12px; opacity: 0.88; margin-top: 2px; }

        /* Chat input */
        .chat-input-container {
            padding: 18px 25px 20px;
            background: white; border-top: 2px solid #FFE4E8;
        }
        .chat-mode-indicator {
            font-size: 12px; color: #666; margin-bottom: 10px;
            display: flex; align-items: center; gap: 5px;
        }
        .chat-mode-indicator.live     { color: #32CD32; font-weight: 600; }
        .chat-mode-indicator.delivery { color: #2196F3; font-weight: 600; }

        .chat-input-wrapper { display: flex; gap: 12px; align-items: center; }

        .chat-input {
            flex: 1; padding: 14px 20px;
            border: 2px solid #FFE4E8; border-radius: 25px;
            font-size: 15px; outline: none; transition: all 0.3s;
        }
        .chat-input:focus { border-color: #FF69B4; box-shadow: 0 0 0 4px rgba(255,105,180,0.1); }

        .send-btn {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            border: none; border-radius: 50%; color: white; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; box-shadow: 0 4px 15px rgba(255,105,180,0.3); flex-shrink: 0;
        }
        .send-btn:hover:not(:disabled) { transform: scale(1.1); }
        .send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── Image Modal — matches checkout page exactly ── */
        .img-modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.75);
            z-index: 9999;
            align-items: center; justify-content: center;
            padding: 1.5rem;
            backdrop-filter: blur(4px);
        }
        .img-modal-overlay.active { display: flex; }

        .img-modal-box {
            background: white; border-radius: 16px; overflow: hidden;
            max-width: 520px; width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            animation: popIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes popIn {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
        .img-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.85rem 1.1rem; border-bottom: 1px solid #dee2e6;
        }
        .img-modal-title {
            font-size: 0.9rem; font-weight: 600; color: #212529;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%;
        }
        .img-modal-close {
            background: none; border: none; font-size: 1.3rem; cursor: pointer;
            color: #6c757d; line-height: 1; padding: 0 0.25rem; transition: color 0.15s;
        }
        .img-modal-close:hover { color: #dc3545; }
        .img-modal-body { padding: 1rem; text-align: center; background: #f9f9f9; }
        .img-modal-body img {
            max-width: 100%; max-height: 420px; object-fit: contain;
            border-radius: 8px; display: block; margin: 0 auto;
        }

        /* End Chat Modal */
        .modal { display: none; position: fixed; z-index: 1000; inset: 0; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 30px; border-radius: 20px; width: 90%; max-width: 450px; }
        .modal-header  { margin-bottom: 20px; }
        .modal-header h3 { color: #FF69B4; margin-bottom: 8px; }
        .modal-buttons { display: flex; gap: 10px; justify-content: flex-end; }
        .modal-btn { padding: 10px 20px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .modal-btn.primary   { background: linear-gradient(135deg,#FF69B4,#FF1493); color: white; }
        .modal-btn.secondary { background: #E0E0E0; color: #666; }
        .modal-btn:hover { transform: translateY(-2px); }

        @media (max-width: 950px) {
            .main-container { flex-direction: column; width: 90%; }
            .orders-sidebar { width: 100%; max-height: 300px; }
        }
        @media (max-width: 768px) {
            .chat-container { height: 100vh; border-radius: 0; width: 380px; }
            .message-content { max-width: 85%; }
            .support-type-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<div class="main-container">

    <!-- Orders Sidebar (delivery chat only) -->
    <div class="orders-sidebar" id="ordersSidebar">
        <div class="orders-header">
            <h3><i class="fas fa-box"></i> Your Orders</h3>
            <small>Click a product image to preview • Use share buttons to send</small>
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
                <div class="bot-avatar" id="headerAvatar">💬</div>
                <div class="header-info">
                    <h2 id="headerTitle">Live Support</h2>
                    <p id="headerStatus">Choose a support type to begin</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="home-btn" onclick="goToHomepage()">
                    <span>🏠</span><span>Home</span>
                </button>
                <button class="end-chat-btn" id="endChatBtn" onclick="showEndChatModal()">
                    <span>✕</span><span>End Chat</span>
                </button>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages"></div>

        <!-- Support Type Selector -->
        <div class="support-type-selector active" id="supportTypeSelector">
            <div class="support-type-label">Choose Support Type to Begin</div>
            <div class="support-type-buttons">
                <button class="support-type-btn staff" onclick="startLiveChatWithType('staff')">
                    <i class="fas fa-user-tie"></i>
                    <div class="btn-title">Customer Support</div>
                    <div class="btn-desc">General inquiries &amp; help</div>
                </button>
                <button class="support-type-btn delivery" onclick="startLiveChatWithType('delivery')">
                    <i class="fas fa-truck"></i>
                    <div class="btn-title">Delivery Support</div>
                    <div class="btn-desc">Track orders &amp; delivery</div>
                </button>
            </div>
        </div>

        <!-- Text-only Chat Input -->
        <div class="chat-input-container" id="chatInputContainer" style="display:none;">
            <div class="chat-mode-indicator" id="chatModeIndicator">
                <span>💬</span><span>Start a live chat to send messages</span>
            </div>
            <form id="chatForm" class="chat-input-wrapper">
                <input type="text" class="chat-input" id="messageInput"
                       placeholder="Type your message..." autocomplete="off" disabled>
                <button type="submit" class="send-btn" id="sendBtn" disabled>
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M2 21L23 12 2 3v7l15 2L2 14z" fill="white"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- End Chat Modal -->
<div id="liveChatModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>End Chat Session?</h3>
            <p>Are you sure you want to end this live chat?</p>
        </div>
        <div class="modal-buttons">
            <button type="button" class="modal-btn secondary" onclick="closeEndChatModal()">Cancel</button>
            <button type="button" class="modal-btn primary" onclick="confirmEndChat()" style="background:#FF4444;">End Chat</button>
        </div>
    </div>
</div>

<!-- ── Image Modal — identical structure to checkout page ── -->
<div class="img-modal-overlay" id="imgModalOverlay" onclick="closeImageModal(event)">
    <div class="img-modal-box" id="imgModalBox">
        <div class="img-modal-header">
            <span class="img-modal-title" id="imgModalTitle"></span>
            <button class="img-modal-close" onclick="closeImageModal(null)" title="Close">&#x2715;</button>
        </div>
        <div class="img-modal-body">
            <img id="imgModalImg" src="" alt="">
        </div>
    </div>
</div>

<script>
let isLiveChatMode     = false;
let liveChatSessionId  = null;
let pollingInterval    = null;
let currentChatStatus  = null;
let activityHeartbeat  = null;
let currentSupportType = null;

const ordersSidebar       = document.getElementById('ordersSidebar');
const ordersContent       = document.getElementById('ordersContent');
const chatMessages        = document.getElementById('chatMessages');
const chatForm            = document.getElementById('chatForm');
const messageInput        = document.getElementById('messageInput');
const sendBtn             = document.getElementById('sendBtn');
const liveChatModal       = document.getElementById('liveChatModal');
const chatModeIndicator   = document.getElementById('chatModeIndicator');
const headerAvatar        = document.getElementById('headerAvatar');
const headerTitle         = document.getElementById('headerTitle');
const headerStatus        = document.getElementById('headerStatus');
const supportTypeSelector = document.getElementById('supportTypeSelector');
const chatInputContainer  = document.getElementById('chatInputContainer');
const endChatBtn          = document.getElementById('endChatBtn');

// ── Helpers ──────────────────────────────────────────────────────
function csrfToken()      { return document.querySelector('meta[name="csrf-token"]').content; }
function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
function getTimestamp()   { return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }); }
function escapeHtml(t)    { const d = document.createElement('div'); d.textContent = t || ''; return d.innerHTML; }
function fmtPrice(v)      { return parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

// ── Image URL is already a correct full URL from the controller ──
function resolveImageUrl(raw) {
    return raw || '';
}

// ── Image Modal — identical behaviour to checkout page ───────────
function openImageModal(src, title) {
    document.getElementById('imgModalImg').src           = src;
    document.getElementById('imgModalTitle').textContent = title || 'Product Image';
    document.getElementById('imgModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImageModal(event) {
    // null  → called from X button or Escape key; always close
    // event → called from overlay click; only close if the backdrop itself was clicked
    if (event && event.target !== document.getElementById('imgModalOverlay')) return;
    document.getElementById('imgModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(null); });

// ── Build product card HTML ──────────────────────────────────────
// Uses resolveImageUrl to handle any DB path format, with imgError fallback chain.
function buildProductCard(productData) {
    if (!productData || !productData.image_url) return '';

    const rawPrice    = parseFloat(productData.price) || 0;
    const priceLbl    = fmtPrice(rawPrice);
    const resolvedSrc = resolveImageUrl(productData.image_url);
    const safeSrc     = resolvedSrc.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    const safeTitle   = (productData.name || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");

    return `
        <div class="product-card-bubble">
            <img class="product-card-img"
                 src="${escapeHtml(resolvedSrc)}"
                 alt="${escapeHtml(productData.name)}"
                 onclick="openImageModal('${safeSrc}', '${safeTitle}')"
                 onerror="this.style.display='none'">
            <div class="product-card-info">
                <div class="product-card-name">${escapeHtml(productData.name)}</div>
                <div class="product-card-price">₱${priceLbl}</div>
            </div>
        </div>`;
}

// ── Heartbeat ────────────────────────────────────────────────────
function sendActivityHeartbeat() {
    if (!liveChatSessionId || !isLiveChatMode) return;
    fetch('{{ route("livechat.heartbeat") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        body: JSON.stringify({ session_id: liveChatSessionId })
    }).catch(() => {});
}
function startActivityHeartbeat() {
    if (activityHeartbeat) clearInterval(activityHeartbeat);
    activityHeartbeat = setInterval(sendActivityHeartbeat, 10000);
    sendActivityHeartbeat();
}
function stopActivityHeartbeat() {
    if (activityHeartbeat) { clearInterval(activityHeartbeat); activityHeartbeat = null; }
}
document.addEventListener('visibilitychange', () => {
    if (document.hidden && isLiveChatMode) stopActivityHeartbeat();
    else if (!document.hidden && isLiveChatMode) startActivityHeartbeat();
});

// ── Render a chat message ────────────────────────────────────────
// productData = { name, price (raw number), image_url }
function addMessage(msgText, type = 'staff', senderName = null, productData = null) {
    const div  = document.createElement('div');
    div.className = `message ${type}`;
    const time = getTimestamp();

    const avatarMap = { user: '👤', staff: '👨‍💼', delivery: '🚚' };
    const avatar    = avatarMap[type] || '👨‍💼';

    const productHtml = buildProductCard(productData);
    const textHtml    = msgText    ? `<div>${escapeHtml(msgText)}</div>` : '';
    const senderHtml  = senderName ? `<div class="sender-name">${escapeHtml(senderName)}</div>` : '';
    const inner       = `${senderHtml}${textHtml}${productHtml}<div class="timestamp">${time}</div>`;

    if (type === 'system') {
        div.innerHTML = `<div class="message-content"><div>${escapeHtml(msgText)}</div><div class="timestamp">${time}</div></div>`;
    } else if (type === 'user') {
        div.innerHTML = `<div class="message-content">${inner}</div><div class="message-avatar">${avatar}</div>`;
    } else {
        div.innerHTML = `<div class="message-avatar">${avatar}</div><div class="message-content">${inner}</div>`;
    }

    chatMessages.appendChild(div);
    scrollToBottom();
    if (isLiveChatMode && (type === 'staff' || type === 'delivery')) markMessagesAsRead();
}

// ── Orders / product sidebar ─────────────────────────────────────
async function loadCustomerOrders() {
    if (!isLiveChatMode || currentSupportType !== 'delivery') {
        ordersSidebar.classList.remove('active'); return;
    }
    try {
        const res  = await fetch('{{ route("customer.orders.ongoing") }}', {
            headers: { 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();
        if (data.success && data.orders && data.orders.length > 0) {
            renderOrders(data.orders);
            ordersSidebar.classList.add('active');
        } else {
            ordersContent.innerHTML = `<div class="no-orders"><i class="fas fa-box-open"></i><p>No ongoing orders</p></div>`;
            ordersSidebar.classList.add('active');
        }
    } catch {
        ordersContent.innerHTML = `<div class="no-orders"><i class="fas fa-exclamation-triangle"></i><p>Failed to load orders</p></div>`;
    }
}

function renderOrders(orders) {
    ordersContent.innerHTML = orders.map(order => {
        const statusClass = order.delivery_status === 'Pending' ? 'status-pending' : 'status-out_for_delivery';
        const products    = (order.product_images || []);
        const hasProducts = products.length > 0;

        // Thumbnails — single click opens image modal (matches checkout pattern)
        const thumbsHtml = products.map(p => {
            const resolvedSrc = resolveImageUrl(p.image_url);
            const safeSrc     = resolvedSrc.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const safeTitle   = (p.name      || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const rawPrice    = parseFloat(p.price) || 0;
            return `
                <div class="product-thumb">
                    <img src="${escapeHtml(resolvedSrc)}"
                         alt="${escapeHtml(p.name)}"
                         onclick="openImageModal('${safeSrc}', '${safeTitle}')"
                         onerror="this.style.display='none'">
                    <span class="thumb-tooltip">${escapeHtml(p.name)}<br>₱${fmtPrice(rawPrice)}</span>
                </div>`;
        }).join('');

        // Per-product share buttons — always pass raw numeric price
        const shareRowHtml = products.map(p => {
            const pData = JSON.stringify({
                name:      p.name,
                price:     parseFloat(p.price) || 0,   // raw number
                image_url: p.image_url
            }).replace(/'/g, "&#39;");
            const shortName = p.name.length > 14 ? p.name.substring(0, 14) + '…' : p.name;
            return `
                <button onclick='shareProduct(${pData})' style="
                    background:none;border:1px solid #4CAF50;border-radius:8px;
                    padding:3px 8px;font-size:11px;color:#4CAF50;cursor:pointer;
                    margin:2px;transition:all .2s;"
                    onmouseover="this.style.background='#4CAF50';this.style.color='white'"
                    onmouseout="this.style.background='none';this.style.color='#4CAF50'">
                    <i class="fas fa-share-alt"></i> ${escapeHtml(shortName)}
                </button>`;
        }).join('');

        // "Share all" — array with raw numeric prices
        const allProductsJson = JSON.stringify(
            products.map(p => ({ name: p.name, price: parseFloat(p.price) || 0, image_url: p.image_url }))
        ).replace(/'/g, "&#39;");

        return `
            <div class="order-card">
                <div class="order-number"><i class="fas fa-receipt"></i> ${order.order_number}</div>
                <span class="order-status ${statusClass}">${order.delivery_status}</span>
                <div class="order-detail"><i class="fas fa-shopping-cart"></i> ${order.items_count} item(s)</div>
                <div class="order-detail"><i class="fas fa-peso-sign"></i> ₱${fmtPrice(order.total)}</div>
                ${order.address ? `<div class="order-detail"><i class="fas fa-map-marker-alt"></i> ${order.address.substring(0, 40)}...</div>` : ''}
                ${hasProducts ? `
                    <div class="order-products">${thumbsHtml}</div>
                    <p class="product-hint"><i class="fas fa-eye"></i> Click image to preview &nbsp;•&nbsp; Use buttons below to share</p>
                    <div style="margin-bottom:8px;">${shareRowHtml}</div>
                    <button class="send-products-btn" onclick='shareAllProducts(${allProductsJson})'>
                        <i class="fas fa-paper-plane"></i> Share all products
                    </button>` : ''}
                <button class="reference-btn" onclick="referenceOrder('${order.order_number}')">
                    <i class="fas fa-comment-dots"></i> Reference in Chat
                </button>
            </div>`;
    }).join('');
}

// ── Share one product as a message with product card ─────────────
async function shareProduct(product) {
    if (!isLiveChatMode || !liveChatSessionId) return;

    const rawPrice = parseFloat(product.price) || 0;   // always treat as number
    const msgText  = `📦 ${product.name} — ₱${fmtPrice(rawPrice)}`;

    // Show in UI immediately — pass raw numeric price
    addMessage(msgText, 'user', null, {
        name:      product.name,
        price:     rawPrice,
        image_url: product.image_url
    });

    try {
        await fetch('{{ route("livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({
                session_id:    liveChatSessionId,
                message:       msgText,
                product_name:  product.name,
                product_price: rawPrice,          // send raw number to server
                product_image: product.image_url
            })
        });
    } catch { /* silent */ }
}

async function shareAllProducts(products) {
    for (const p of products) {
        await shareProduct(p);
        await new Promise(r => setTimeout(r, 250));
    }
}

function referenceOrder(orderNumber) {
    messageInput.value = `I have a question about order ${orderNumber}: `;
    messageInput.focus();
}

// ── Send text ─────────────────────────────────────────────────────
async function sendMessage(text) {
    if (!isLiveChatMode || !liveChatSessionId) { alert('Please start a live chat session first.'); return; }
    if (!text) return;

    sendBtn.disabled = true;
    messageInput.disabled = true;
    addMessage(text, 'user');
    messageInput.value = '';

    try {
        await fetch('{{ route("livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({ session_id: liveChatSessionId, message: text })
        });
    } catch { addMessage('Failed to send. Please try again.', 'system'); }

    sendBtn.disabled = false;
    messageInput.disabled = false;
    messageInput.focus();
}

chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const text = messageInput.value.trim();
    if (text) sendMessage(text);
});
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); chatForm.dispatchEvent(new Event('submit')); }
});

// ── Start chat ────────────────────────────────────────────────────
async function startLiveChatWithType(type) {
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    if (!isAuthenticated) {
        alert('Please login to use live chat support.');
        window.location.href = '{{ route("home") }}';
        return;
    }

    currentSupportType = type;
    try {
        const route = type === 'delivery'
            ? '{{ route("livechat.request-delivery") }}'
            : '{{ route("livechat.request") }}';

        const res  = await fetch(route, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();

        if (res.ok) {
            liveChatSessionId = data.session_id;
            isLiveChatMode    = true;
            currentChatStatus = 'waiting';

            supportTypeSelector.classList.remove('active');
            chatInputContainer.style.display = 'block';
            endChatBtn.classList.add('active');
            messageInput.disabled = false;
            sendBtn.disabled = false;

            if (type === 'delivery') await loadCustomerOrders();

            updateUIForLiveChat('waiting', data.queue_position);
            addMessage(
                `🎫 You've been added to the queue. A ${type === 'delivery' ? 'delivery coordinator' : 'staff member'} will be with you shortly...`,
                'system'
            );
            startPolling();
            messageInput.focus();
        } else {
            alert(data.message || 'Failed to start live chat.');
        }
    } catch {
        alert('Failed to connect. Please check your internet connection.');
    }
}

// ── UI state ──────────────────────────────────────────────────────
function updateUIForLiveChat(status, queuePosition) {
    if (status === 'waiting') {
        headerAvatar.textContent = '⏱️';
        headerTitle.textContent  = 'Waiting for Support';
        headerStatus.innerHTML   = `<span class="live-chat-status">You are #${queuePosition} in queue</span>`;
        chatModeIndicator.innerHTML = `<span>⏱️</span><span>Waiting for support to join...</span>`;
        chatModeIndicator.className = 'chat-mode-indicator ' + (currentSupportType === 'delivery' ? 'delivery' : 'live');
    } else if (status === 'active') {
        headerAvatar.textContent = currentSupportType === 'delivery' ? '🚚' : '👨‍💼';
        headerTitle.textContent  = currentSupportType === 'delivery' ? 'Delivery Support' : 'Live Support';
        headerStatus.innerHTML   = '<span class="live-chat-status">Connected to support</span>';
        chatModeIndicator.innerHTML = `<span>${currentSupportType === 'delivery' ? '🚚' : '👨‍💼'}</span><span>Live chat with support</span>`;
        chatModeIndicator.className = 'chat-mode-indicator ' + (currentSupportType === 'delivery' ? 'delivery' : 'live');
    }
}

// ── Poll for new messages ──────────────────────────────────────────
function startPolling() {
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(async () => {
        if (!liveChatSessionId) return;
        try {
            const res  = await fetch(`{{ url('livechat/poll') }}/${liveChatSessionId}`);
            const data = await res.json();

            if (data.status === 'active' && currentChatStatus !== 'active') {
                currentChatStatus = 'active';
                updateUIForLiveChat('active');
                addMessage('✓ A support member has joined the chat!', 'system');
                startActivityHeartbeat();
            } else if (data.status === 'waiting' && currentChatStatus !== 'waiting') {
                currentChatStatus = 'waiting';
                updateUIForLiveChat('waiting', data.queue_position);
            }

            (data.new_messages || []).forEach(msg => {
                if (msg.sender_type === 'system') { addMessage(msg.message, 'system'); return; }
                if (msg.sender_type === 'customer') return;   // skip own messages echoed back

                const mType   = currentSupportType === 'delivery' ? 'delivery' : 'staff';
                // Always parse price as float from server — it could be a string like "1500.00"
                const product = msg.product_image ? {
                    name:      msg.product_name,
                    price:     parseFloat(msg.product_price) || 0,
                    image_url: msg.product_image
                } : null;
                addMessage(msg.message || null, mType, msg.sender_name || 'Support', product);
            });

            if (data.status === 'closed') endLiveChat();
        } catch { /* silent */ }
    }, 3000);
}

function markMessagesAsRead() {
    if (!liveChatSessionId) return;
    fetch(`{{ url('livechat/mark-read') }}/${liveChatSessionId}`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken() }
    }).catch(() => {});
}

// ── End chat ──────────────────────────────────────────────────────
function showEndChatModal()  { liveChatModal.style.display = 'block'; }
function closeEndChatModal() { liveChatModal.style.display = 'none'; }
async function confirmEndChat() { closeEndChatModal(); await endLiveChatSession(); }

async function endLiveChatSession() {
    try {
        stopActivityHeartbeat();
        await fetch('{{ route("livechat.end") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({ session_id: liveChatSessionId })
        });
    } catch { /* ignore */ }
    endLiveChat();
}

function endLiveChat() {
    if (pollingInterval) clearInterval(pollingInterval);
    stopActivityHeartbeat();
    isLiveChatMode = false; liveChatSessionId = null; currentChatStatus = null; currentSupportType = null;
    ordersSidebar.classList.remove('active');
    supportTypeSelector.classList.add('active');
    chatInputContainer.style.display = 'none';
    endChatBtn.classList.remove('active');
    messageInput.disabled = true; sendBtn.disabled = true;
    headerAvatar.textContent = '💬';
    headerTitle.textContent  = 'Live Support';
    headerStatus.textContent = 'Choose a support type to begin';
    chatModeIndicator.innerHTML = '<span>💬</span><span>Start a live chat to send messages</span>';
    chatModeIndicator.className = 'chat-mode-indicator';
    addMessage('Chat session ended. Choose a support type to start a new session.', 'system');
}

function goToHomepage() {
    if (isLiveChatMode && liveChatSessionId) {
        if (confirm('You are in an active live chat. Are you sure you want to leave?'))
            window.location.href = '{{ route("home") }}';
    } else {
        window.location.href = '{{ route("home") }}';
    }
}

// ── Restore session on page load ──────────────────────────────────
window.addEventListener('load', async () => {
    scrollToBottom();
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    if (!isAuthenticated) return;
    try {
        const res  = await fetch('{{ route("livechat.active-session") }}', {
            headers: { 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();
        if (data.success && data.has_session) {
            liveChatSessionId  = data.session.session_id;
            isLiveChatMode     = true;
            currentSupportType = data.session.chat_type || 'staff';

            supportTypeSelector.classList.remove('active');
            chatInputContainer.style.display = 'block';
            endChatBtn.classList.add('active');
            messageInput.disabled = false;
            sendBtn.disabled = false;
            currentChatStatus = data.session.status;

            if (currentChatStatus === 'waiting') {
                updateUIForLiveChat('waiting', data.session.queue_position);
                addMessage('🎫 Reconnected to queue. You are #' + data.session.queue_position + ' in line.', 'system');
            } else if (currentChatStatus === 'active') {
                updateUIForLiveChat('active');
                addMessage('✓ Reconnected to your active chat session!', 'system');
                await loadChatHistory(data.session.session_id);
                startActivityHeartbeat();
            }

            if (currentSupportType === 'delivery') await loadCustomerOrders();
            startPolling();
            messageInput.focus();
        }
    } catch { /* ignore */ }
});

async function loadChatHistory(sessionId) {
    try {
        const res  = await fetch(`{{ url('livechat/history') }}/${sessionId}`, {
            headers: { 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();
        (data.messages || []).forEach(msg => {
            // Always parse price as float from server response
            const product = msg.product_image ? {
                name:      msg.product_name,
                price:     parseFloat(msg.product_price) || 0,
                image_url: msg.product_image
            } : null;

            if (msg.sender_type === 'customer') {
                addMessage(msg.message || null, 'user', null, product);
            } else if (msg.sender_type === 'admin' || msg.sender_type === 'delivery') {
                const mType = currentSupportType === 'delivery' ? 'delivery' : 'staff';
                addMessage(msg.message || null, mType, msg.sender_name || 'Support', product);
            } else if (msg.sender_type === 'system') {
                addMessage(msg.message, 'system');
            }
        });
    } catch { /* ignore */ }
}
</script>
</body>
</html>