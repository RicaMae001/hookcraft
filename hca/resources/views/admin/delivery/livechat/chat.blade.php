@extends('admin.delivery.layouts.delivery-layout')

@section('title', 'Live Chat - ' . $session->customer_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Live Chat</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('delivery.livechat.index') }}">Chats</a></li>
                <li class="breadcrumb-item active">{{ $session->customer_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
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

<style>
/* ── Product thumbnails in sidebar ── */
.product-thumb-wrap {
    position: relative;
    cursor: zoom-in;
    display: inline-block;
}
.product-thumb-wrap img {
    width: 54px; height: 54px; object-fit: cover;
    border-radius: 9px; border: 2px solid #dee2e6;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
    display: block;
}
.product-thumb-wrap:hover img {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.18);
    border-color: #0d6efd;
}

/* ── Product card inside message bubble ── */
.product-card-bubble {
    display: flex; gap: 10px; align-items: center;
    border-radius: 10px; padding: 8px 10px; margin-top: 8px;
}
.product-card-bubble.outgoing {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.4);
}
.product-card-bubble.incoming {
    background: rgba(13,110,253,0.07);
    border: 1px solid rgba(13,110,253,0.18);
}
.product-card-img {
    width: 56px; height: 56px; object-fit: cover;
    border-radius: 8px; flex-shrink: 0;
    cursor: zoom-in;
    border: 2px solid rgba(255,255,255,0.5);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: block;
}
.product-card-img:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.22);
}
.product-card-img.incoming-img { border-color: rgba(13,110,253,0.3); }
.product-card-name  { font-size: 13px; font-weight: 700; }
.product-card-price { font-size: 12px; opacity: 0.85; margin-top: 2px; }

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

.message { animation: fadeIn .3s ease; }
@keyframes fadeIn {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}
#chatMessages::-webkit-scrollbar { width:5px; }
#chatMessages::-webkit-scrollbar-thumb { background:#ccc; border-radius:3px; }
</style>

<div class="row g-4">

    {{-- ── Left Sidebar ── --}}
    <div class="col-lg-4">

        {{-- Customer Info --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;flex-shrink:0;">
                        <i class="bi bi-person fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $session->customer_name }}</h5>
                        @if($session->customer_email)
                            <p class="text-muted mb-0 small">{{ $session->customer_email }}</p>
                        @endif
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <p class="text-muted mb-1 small">Status</p>
                            @if($session->status === 'active')
                                <span class="badge bg-success"><i class="bi bi-circle-fill me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-circle me-1"></i>Closed</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <p class="text-muted mb-1 small">Started</p>
                            <p class="mb-0 fw-semibold small">{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ongoing Orders with Product Images --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Ongoing Orders</h6>
                <span class="badge bg-primary">{{ $ongoingOrders->count() }}</span>
            </div>
            <div class="card-body p-0" style="max-height:380px;overflow-y:auto;">
                @forelse($ongoingOrders as $order)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $order->order_number }}</h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, h:i A') }}</small>
                        </div>
                        <span class="badge bg-{{ $order->delivery_status === 'Pending' ? 'warning text-dark' : 'success' }}">
                            {{ $order->delivery_status }}
                        </span>
                    </div>

                    @if(isset($order->product_images) && $order->product_images->count())
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        @foreach($order->product_images as $product)
                        <div class="product-thumb-wrap"
                             title="{{ $product->name }} — ₱{{ number_format($product->price,2) }}"
                             onclick="openImageModal('{{ $product->image_url }}', '{{ addslashes($product->name) }}')">
                            <img src="{{ $product->image_url }}"
                                 alt="{{ $product->name }}"
                                 onerror="this.style.display='none'">
                        </div>
                        @endforeach
                    </div>
                    <p class="text-muted mb-2" style="font-size:11px;">
                        <i class="bi bi-eye me-1"></i>Click image to preview
                    </p>

                    <div class="d-flex flex-wrap gap-1 mb-2">
                        @foreach($order->product_images as $product)
                        <button type="button"
                                class="btn btn-sm btn-outline-success"
                                style="font-size:11px;padding:3px 8px;"
                                onclick='shareProduct({{ json_encode(["name"=>$product->name,"price"=>floatval($product->price),"image_url"=>$product->image_url]) }})'>
                            <i class="bi bi-share me-1"></i>{{ Str::limit($product->name, 14) }}
                        </button>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-primary w-100 mb-2"
                            onclick="shareAllProducts({{ json_encode($order->product_images->map(fn($p) => ['name'=>$p->name,'price'=>floatval($p->price),'image_url'=>$p->image_url])) }})">
                        <i class="bi bi-paper-plane me-1"></i>Share All Products
                    </button>
                    @endif

                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Items:</small>
                        <small class="fw-semibold">{{ $order->items_count }} item(s)</small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Total:</small>
                        <small class="fw-semibold">₱{{ number_format($order->total, 2) }}</small>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-success w-100"
                            onclick="insertOrderReference('{{ $order->order_number }}')">
                        <i class="bi bi-chat-left-text me-1"></i>Reference in Chat
                    </button>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-box-seam display-6 text-muted mb-2"></i>
                    <p class="text-muted mb-0">No ongoing orders</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Messages --}}
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

    {{-- ── Chat Interface ── --}}
    <div class="col-lg-8">
        <div class="card d-flex flex-column" style="min-height:600px;">
            <div class="card-body p-0 d-flex flex-column flex-grow-1">

                {{-- Messages --}}
                <div class="flex-grow-1 p-4" id="chatMessages"
                     style="overflow-y:auto;height:calc(100vh - 420px);min-height:300px;">
                    @forelse($messages as $message)

                        @if($message->sender_type === 'system')
                            <div class="text-center my-3 message">
                                <span class="badge bg-info px-3 py-2">
                                    <i class="bi bi-info-circle me-1"></i>{{ $message->message }}
                                </span>
                                <div class="text-muted mt-1" style="font-size:11px;">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                </div>
                            </div>

                        @elseif($message->sender_type === 'customer')
                            {{-- Customer message (left) --}}
                            <div class="message mb-4">
                                <div class="d-flex">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:38px;height:38px;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="bg-light rounded p-3">
                                            <div class="mb-1">
                                                <span class="fw-semibold">{{ $session->customer_name }}</span>
                                                <span class="text-muted ms-2" style="font-size:11px;">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                                </span>
                                            </div>
                                            @if($message->message)
                                                <p class="mb-1">{{ $message->message }}</p>
                                            @endif
                                            @if(!empty($message->product_image))
                                            <div class="product-card-bubble incoming">
                                                <img class="product-card-img incoming-img"
                                                     src="{{ $message->product_image }}"
                                                     alt="{{ $message->product_name }}"
                                                     onclick="openImageModal('{{ $message->product_image }}', '{{ addslashes($message->product_name) }}')"
                                                     onerror="this.style.display='none'">
                                                <div>
                                                    <div class="product-card-name text-dark">{{ $message->product_name }}</div>
                                                    <div class="product-card-price text-muted">₱{{ number_format($message->product_price, 2) }}</div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            {{-- Delivery staff message (right) --}}
                            <div class="message mb-4">
                                <div class="d-flex justify-content-end">
                                    <div class="flex-grow-1 me-3">
                                        <div class="bg-primary text-white rounded p-3">
                                            <div class="mb-1">
                                                <span class="fw-semibold">You</span>
                                                <span class="opacity-75 ms-2" style="font-size:11px;">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                                                </span>
                                            </div>
                                            @if($message->message)
                                                <p class="mb-1">{{ $message->message }}</p>
                                            @endif
                                            @if(!empty($message->product_image))
                                            <div class="product-card-bubble outgoing">
                                                <img class="product-card-img"
                                                     src="{{ $message->product_image }}"
                                                     alt="{{ $message->product_name }}"
                                                     onclick="openImageModal('{{ $message->product_image }}', '{{ addslashes($message->product_name) }}')"
                                                     onerror="this.style.display='none'">
                                                <div>
                                                    <div class="product-card-name">{{ $message->product_name }}</div>
                                                    <div class="product-card-price">₱{{ number_format($message->product_price, 2) }}</div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:38px;height:38px;background:linear-gradient(135deg,#4CAF50,#45a049);">
                                        <i class="bi bi-truck text-white"></i>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-text display-6 text-muted mb-2"></i>
                            <h5 class="text-muted">No messages yet</h5>
                            <p class="text-muted">Start the conversation</p>
                        </div>
                    @endforelse
                </div>

                {{-- Text-only Input --}}
                <div class="border-top p-3">
                    @if($session->status === 'active')
                    <form id="chatForm">
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" class="form-control rounded-pill" id="messageInput"
                                   placeholder="Type your message..." autocomplete="off">
                            <button type="submit"
                                    class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center"
                                    style="width:42px;height:42px;flex-shrink:0;" id="sendBtn">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                        <div class="mt-1 text-muted ps-1" style="font-size:11px;">
                            Press Enter to send &nbsp;•&nbsp; Use the sidebar to share product images
                        </div>
                    </form>
                    @else
                    <div class="alert alert-secondary mb-0">
                        <h6 class="mb-1"><i class="bi bi-chat-square-text me-2"></i>Chat Session Closed</h6>
                        <p class="mb-0 small">This chat session has ended.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Image Modal — identical structure to checkout page ── --}}
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
const sessionId     = {{ $session->id }};
const sessionStatus = '{{ $session->status }}';
let pollingInterval = null;

const chatMessagesEl = document.getElementById('chatMessages');
const messageInput   = document.getElementById('messageInput');
const sendBtn        = document.getElementById('sendBtn');
const chatForm       = document.getElementById('chatForm');

// ── Helpers ────────────────────────────────────────────────────
function csrfToken()      { return document.querySelector('meta[name="csrf-token"]').content; }
function scrollToBottom() { chatMessagesEl.scrollTop = chatMessagesEl.scrollHeight; }
function formatTime(d)    { return new Date(d).toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit', hour12:true }); }
function escapeHtml(t)    { const d = document.createElement('div'); d.textContent = t || ''; return d.innerHTML; }
function fmtPrice(v)      { return parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 }); }

// ── Image URL is already a correct full URL from the controller ──
function resolveImageUrl(raw) {
    return raw || '';
}

// ── Image Modal — matches checkout page exactly ───────────────
function openImageModal(src, title) {
    document.getElementById('imgModalImg').src           = src;
    document.getElementById('imgModalTitle').textContent = title || 'Product Image';
    document.getElementById('imgModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImageModal(event) {
    if (event && event.target !== document.getElementById('imgModalOverlay')) return;
    document.getElementById('imgModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(null); });

// ── Build product card HTML (matches checkout bubble style) ───
function buildProductCard(productData, isOutgoing) {
    if (!productData || !productData.image_url) return '';

    const rawPrice   = parseFloat(productData.price) || 0;
    const priceLabel = fmtPrice(rawPrice);
    const resolvedSrc = resolveImageUrl(productData.image_url);
    const safeSrc    = resolvedSrc.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    const safeTitle  = (productData.name || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");

    const cardClass = isOutgoing ? 'outgoing' : 'incoming';
    const imgClass  = isOutgoing ? 'product-card-img' : 'product-card-img incoming-img';
    const nameStyle = isOutgoing ? '' : 'color:#212529;';
    const priceStyle= isOutgoing ? '' : 'color:#6c757d;';

    return `
        <div class="product-card-bubble ${cardClass}">
            <img class="${imgClass}"
                 src="${escapeHtml(resolvedSrc)}"
                 alt="${escapeHtml(productData.name)}"
                 onclick="openImageModal('${safeSrc}', '${safeTitle}')"
                 onerror="this.style.display='none'">
            <div>
                <div class="product-card-name" style="${nameStyle}">${escapeHtml(productData.name)}</div>
                <div class="product-card-price" style="${priceStyle}">₱${priceLabel}</div>
            </div>
        </div>`;
}

// ── Add system message to DOM ──────────────────────────────────
function addSystemMessage(msg) {
    const d = document.createElement('div');
    d.className = 'text-center my-3 message';
    d.innerHTML = `<span class="badge bg-info px-3 py-2"><i class="bi bi-info-circle me-1"></i>${escapeHtml(msg)}</span>
                   <div class="text-muted mt-1" style="font-size:11px;">${formatTime(new Date())}</div>`;
    chatMessagesEl.appendChild(d);
    scrollToBottom();
}

// ── Add message to DOM ─────────────────────────────────────────
// type: 'delivery' (outgoing/right) | 'customer' (incoming/left)
function addMessage(msg, type, productData) {
    const time        = formatTime(new Date());
    const d           = document.createElement('div');
    d.className       = 'message mb-4';
    const isOutgoing  = (type === 'delivery');
    const productHtml = buildProductCard(productData, isOutgoing);
    const textHtml    = msg ? `<p class="mb-1">${escapeHtml(msg)}</p>` : '';

    if (type === 'customer') {
        d.innerHTML = `
            <div class="d-flex">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;">
                    <i class="bi bi-person text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="bg-light rounded p-3">
                        <div class="mb-1">
                            <span class="fw-semibold">{{ $session->customer_name }}</span>
                            <span class="text-muted ms-2" style="font-size:11px;">${time}</span>
                        </div>
                        ${textHtml}${productHtml}
                    </div>
                </div>
            </div>`;
    } else {
        d.innerHTML = `
            <div class="d-flex justify-content-end">
                <div class="flex-grow-1 me-3">
                    <div class="bg-primary text-white rounded p-3">
                        <div class="mb-1">
                            <span class="fw-semibold">You</span>
                            <span class="opacity-75 ms-2" style="font-size:11px;">${time}</span>
                        </div>
                        ${textHtml}${productHtml}
                    </div>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:38px;height:38px;background:linear-gradient(135deg,#4CAF50,#45a049);">
                    <i class="bi bi-truck text-white"></i>
                </div>
            </div>`;
    }
    chatMessagesEl.appendChild(d);
    scrollToBottom();
}

// ── Share a single product from sidebar ───────────────────────
async function shareProduct(product) {
    if (sessionStatus !== 'active') return;

    const rawPrice = parseFloat(product.price) || 0;
    const msgText  = `📦 ${product.name} — ₱${fmtPrice(rawPrice)}`;

    // Show immediately in UI
    addMessage(msgText, 'delivery', {
        name:      product.name,
        price:     rawPrice,          // always raw number
        image_url: product.image_url
    });

    try {
        await fetch('{{ route("delivery.livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({
                session_id:    sessionId,
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

function insertOrderReference(orderNumber) {
    messageInput.value = `Regarding order ${orderNumber}: `;
    messageInput.focus();
}

function sendQuickMessage(msg) {
    if (sessionStatus !== 'active') return;
    messageInput.value = msg;
    messageInput.focus();
}

// ── Send text message ──────────────────────────────────────────
if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = messageInput.value.trim();
        if (!msg) { messageInput.focus(); return; }

        sendBtn.disabled = true;
        messageInput.disabled = true;

        try {
            const res  = await fetch('{{ route("delivery.livechat.send") }}', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                body:    JSON.stringify({ session_id: sessionId, message: msg })
            });
            const data = await res.json();
            if (res.ok && data.success) {
                addMessage(msg, 'delivery', null);
                messageInput.value = '';
            } else {
                alert(data.message || 'Failed to send message');
            }
        } catch { alert('Network error. Please try again.'); }
        finally {
            sendBtn.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        }
    });

    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
}

// ── Poll for new customer messages ─────────────────────────────
function startPolling() {
    if (sessionStatus !== 'active') return;
    pollingInterval = setInterval(async () => {
        try {
            const res  = await fetch(`{{ url('delivery/livechat/poll') }}/${sessionId}`);
            const data = await res.json();

            if (data.success && data.new_messages && data.new_messages.length > 0) {
                data.new_messages.forEach(msg => {
                    // Always parse price as float from server response
                    const product = msg.product_image ? {
                        name:      msg.product_name,
                        price:     parseFloat(msg.product_price) || 0,
                        image_url: msg.product_image
                    } : null;
                    addMessage(msg.message || null, 'customer', product);
                });
            }

            if (data.status === 'closed') {
                clearInterval(pollingInterval);
                addSystemMessage('Chat ended by customer.');
                setTimeout(() => location.reload(), 2500);
            }
        } catch { /* silent */ }
    }, 3000);
}

// ── End chat ───────────────────────────────────────────────────
async function endChat() {
    if (!confirm('Are you sure you want to end this chat session?')) return;
    try {
        const res = await fetch(`{{ url('delivery/livechat/end') }}/${sessionId}`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() }
        });
        if (res.ok) window.location.href = '{{ route("delivery.livechat.index") }}';
        else alert('Failed to end chat');
    } catch { alert('Failed to end chat'); }
}

window.addEventListener('load', () => {
    scrollToBottom();
    if (sessionStatus === 'active') {
        startPolling();
        messageInput?.focus();
    }
});
window.addEventListener('beforeunload', () => {
    if (pollingInterval) clearInterval(pollingInterval);
});
</script>
@endsection