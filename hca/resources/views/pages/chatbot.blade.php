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
            max-width: 1050px;
            display: flex;
            gap: 20px;
        }

        /* ── Orders Sidebar ── */
        .orders-sidebar {
            width: 370px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(255,105,180,0.25);
            overflow: hidden;
            display: none;
            flex-direction: column;
        }
        .orders-sidebar.active { display: flex; }

        .orders-header {
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            color: white;
            padding: 18px 20px;
            flex-shrink: 0;
        }
        .orders-header.delivery-mode {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }
        .orders-header h3 { font-size: 1.05em; margin-bottom: 3px; }
        .orders-header small { opacity: 0.85; font-size: 11px; }

        /* Tabs */
        .sidebar-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            flex-shrink: 0;
        }
        .sidebar-tab {
            flex: 1; padding: 10px 6px;
            background: none; border: none;
            font-size: 12px; font-weight: 600;
            color: #888; cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s; text-align: center;
            margin-bottom: -2px;
        }
        .sidebar-tab.active { color: #4CAF50; border-bottom-color: #4CAF50; background: white; }
        .sidebar-tab i { display: block; font-size: 16px; margin-bottom: 3px; }

        .sidebar-panel { display: none; }
        .sidebar-panel.active { display: block; }

        .orders-content {
            padding: 12px;
            max-height: 520px;
            overflow-y: auto;
        }

        /* Order card */
        .order-card {
            background: #f8f9fa;
            border-radius: 14px;
            padding: 13px;
            margin-bottom: 12px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .order-card:hover { border-color: #4CAF50; box-shadow: 0 4px 15px rgba(76,175,80,0.15); }
        .order-number { font-weight: bold; color: #4CAF50; margin-bottom: 6px; font-size: 13px; }

        .order-status {
            display: inline-block;
            padding: 3px 10px; border-radius: 10px;
            font-size: 11px; font-weight: 600; margin-bottom: 7px;
        }
        .status-pending          { background: #FFF3CD; color: #856404; }
        .status-out_for_delivery { background: #D4EDDA; color: #155724; }

        .order-detail { font-size: 12px; color: #666; margin: 4px 0; }
        .order-detail i { width: 15px; color: #4CAF50; }

        /* Product thumbnails */
        .order-products { display: flex; gap: 7px; flex-wrap: wrap; margin: 9px 0 3px; }
        .product-thumb { position: relative; cursor: zoom-in; }
        .product-thumb img {
            width: 54px; height: 54px; object-fit: cover;
            border-radius: 9px; border: 2px solid #e0e0e0;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s; display: block;
        }
        .product-thumb:hover img { transform: scale(1.08); box-shadow: 0 4px 12px rgba(0,0,0,0.18); border-color: #4CAF50; }
        .product-thumb .thumb-tooltip {
            display: none; position: absolute;
            bottom: calc(100% + 6px); left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.78); color: white; font-size: 11px;
            padding: 5px 9px; border-radius: 7px;
            white-space: nowrap; z-index: 10; pointer-events: none; text-align: center;
        }
        .product-thumb:hover .thumb-tooltip { display: block; }
        .product-hint { font-size: 11px; color: #aaa; margin: 3px 0 7px; }

        .share-row { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 7px; }
        .share-product-btn {
            background: none; border: 1px solid #4CAF50; border-radius: 7px;
            padding: 3px 8px; font-size: 11px; color: #4CAF50; cursor: pointer;
            transition: all 0.2s;
        }
        .share-product-btn:hover { background: #4CAF50; color: white; }

        .send-products-btn {
            width: 100%; padding: 7px;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white; border: none; border-radius: 9px;
            font-size: 12px; font-weight: 600; cursor: pointer;
            margin-bottom: 5px; transition: all 0.3s;
        }
        .send-products-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(33,150,243,0.35); }

        .reference-btn {
            width: 100%; padding: 7px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white; border: none; border-radius: 9px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .reference-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(76,175,80,0.35); }

        /* ── Customize Cards ── */
        .customize-card {
            background: linear-gradient(135deg, #FFF8F0 0%, #FFF3E0 100%);
            border-radius: 14px;
            padding: 13px;
            margin-bottom: 12px;
            border: 2px solid #FFE0B2;
            transition: all 0.3s;
        }
        .customize-card:hover { border-color: #FF9800; box-shadow: 0 4px 15px rgba(255,152,0,0.2); }

        .customize-ref {
            font-weight: bold; color: #E65100; margin-bottom: 5px; font-size: 13px;
        }
        .customize-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 10px;
            font-size: 11px; font-weight: 600; margin-bottom: 8px;
        }
        .cstatus-pending    { background: #FFF3CD; color: #856404; }
        .cstatus-reviewing  { background: #CCE5FF; color: #004085; }
        .cstatus-approved   { background: #D4EDDA; color: #155724; }
        .cstatus-rejected   { background: #F8D7DA; color: #721C24; }
        .cstatus-completed  { background: #D4EDDA; color: #155724; }

        .customize-img-wrap {
            margin: 8px 0;
            position: relative; display: inline-block; cursor: zoom-in;
        }
        .customize-img-wrap img {
            width: 100%; max-height: 120px; object-fit: cover;
            border-radius: 10px; border: 2px solid #FFB74D;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .customize-img-wrap:hover img { transform: scale(1.02); box-shadow: 0 4px 14px rgba(255,152,0,0.3); }

        .customize-detail { font-size: 12px; color: #666; margin: 4px 0; }
        .customize-detail i { width: 15px; color: #FF9800; }
        .customize-detail strong { color: #444; }

        .share-customize-btn {
            width: 100%; padding: 7px; margin-top: 8px;
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white; border: none; border-radius: 9px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .share-customize-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,152,0,0.35); }

        .ref-customize-btn {
            width: 100%; padding: 7px; margin-top: 4px;
            background: linear-gradient(135deg, #795548 0%, #5D4037 100%);
            color: white; border: none; border-radius: 9px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .ref-customize-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(121,85,72,0.35); }

        .no-orders { text-align: center; padding: 35px 15px; color: #999; }
        .no-orders i { font-size: 40px; margin-bottom: 12px; display: block; }

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
        .home-btn:hover { transform: translateY(-2px); }

        .end-chat-btn {
            background: #FF4444; color: white; border: none;
            padding: 8px 16px; border-radius: 20px; font-weight: 600;
            display: none; align-items: center; gap: 8px;
            cursor: pointer; transition: all 0.3s;
        }
        .end-chat-btn.active { display: flex; }

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
        .message-content { max-width: 72%; padding: 14px 18px; border-radius: 18px; }
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

        /* Product card in bubble */
        .product-card-bubble {
            display: flex; gap: 10px; align-items: center;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 12px; padding: 8px 10px; margin-top: 8px;
        }
        .product-card-img {
            width: 54px; height: 54px; object-fit: cover;
            border-radius: 8px; flex-shrink: 0; cursor: zoom-in;
            border: 2px solid rgba(255,255,255,0.45);
            transition: transform 0.2s, box-shadow 0.2s; display: block;
        }
        .product-card-img:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(0,0,0,0.22); }
        .product-card-info    { flex: 1; min-width: 0; }
        .product-card-name    { font-size: 13px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .product-card-price   { font-size: 12px; opacity: 0.88; margin-top: 2px; }

        /* Customize card in bubble */
        .customize-card-bubble {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 12px; padding: 10px; margin-top: 8px;
        }
        .customize-bubble-header {
            display: flex; align-items: center; gap: 8px; margin-bottom: 7px;
        }
        .customize-bubble-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }
        .customize-bubble-title { font-size: 13px; font-weight: 700; }
        .customize-bubble-ref   { font-size: 11px; opacity: 0.8; }
        .customize-bubble-img {
            width: 100%; max-height: 110px; object-fit: cover;
            border-radius: 8px; margin: 6px 0;
            cursor: zoom-in; display: block;
            border: 1px solid rgba(255,255,255,0.3);
            transition: transform 0.2s;
        }
        .customize-bubble-img:hover { transform: scale(1.02); }
        .customize-bubble-row { font-size: 11px; opacity: 0.9; margin: 3px 0; }
        .customize-bubble-status {
            display: inline-block; padding: 2px 8px; border-radius: 8px;
            font-size: 11px; font-weight: 700; margin-top: 5px;
            background: rgba(255,255,255,0.25);
        }

        /* Chat input */
        .chat-input-container { padding: 18px 25px 20px; background: white; border-top: 2px solid #FFE4E8; }
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

        /* Image Modal */
        .img-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.75); z-index: 9999;
            align-items: center; justify-content: center;
            padding: 1.5rem; backdrop-filter: blur(4px);
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
        .img-modal-title { font-size: 0.9rem; font-weight: 600; color: #212529; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%; }
        .img-modal-close { background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #6c757d; line-height: 1; padding: 0 0.25rem; transition: color 0.15s; }
        .img-modal-close:hover { color: #dc3545; }
        .img-modal-body { padding: 1rem; text-align: center; background: #f9f9f9; }
        .img-modal-body img { max-width: 100%; max-height: 420px; object-fit: contain; border-radius: 8px; display: block; margin: 0 auto; }

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

        /* Pending notice badge */
        .pending-notice {
            background: linear-gradient(135deg, #FFF3CD, #FFE69C);
            border: 1px solid #FFD700; border-radius: 10px;
            padding: 8px 12px; font-size: 12px; color: #856404;
            margin-bottom: 8px; display: flex; align-items: center; gap: 6px;
        }

        @media (max-width: 950px) {
            .main-container { flex-direction: column; width: 90%; }
            .orders-sidebar { width: 100%; max-height: 320px; }
        }
        @media (max-width: 768px) {
            .chat-container { height: 100vh; border-radius: 0; width: 380px; }
            .message-content { max-width: 88%; }
            .support-type-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<div class="main-container">

    <!-- Orders / Customize Sidebar (shown for all chat types) -->
    <div class="orders-sidebar" id="ordersSidebar">
        <div class="orders-header" id="sidebarHeader">
            <h3 id="sidebarTitle"><i class="fas fa-layer-group"></i> Orders &amp; Customizations</h3>
            <small id="sidebarSubtitle">Click image to preview • Use buttons to share in chat</small>
        </div>

        <!-- Tabs -->
        <div class="sidebar-tabs">
            <button class="sidebar-tab active" id="tabOrders" onclick="switchSidebarTab('orders')">
                <i class="fas fa-shopping-bag"></i> Orders
            </button>
            <button class="sidebar-tab" id="tabCustomize" onclick="switchSidebarTab('customize')">
                <i class="fas fa-paint-brush"></i> Customize
            </button>
        </div>

        <!-- Orders Panel -->
        <div class="sidebar-panel active" id="panelOrders">
            <div class="orders-content" id="ordersContent">
                <div class="no-orders"><i class="fas fa-box-open"></i><p>Loading orders...</p></div>
            </div>
        </div>

        <!-- Customize Panel -->
        <div class="sidebar-panel" id="panelCustomize">
            <div class="orders-content" id="customizeContent">
                <div class="no-orders"><i class="fas fa-paint-brush"></i><p>Loading customizations...</p></div>
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

        <!-- Chat Input -->
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

<!-- Image Modal -->
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
const customizeContent    = document.getElementById('customizeContent');
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
function resolveImageUrl(raw) { return raw || ''; }

// ── Image Modal ──────────────────────────────────────────────────
function openImageModal(src, title) {
    document.getElementById('imgModalImg').src           = src;
    document.getElementById('imgModalTitle').textContent = title || 'Image';
    document.getElementById('imgModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImageModal(event) {
    if (event && event.target !== document.getElementById('imgModalOverlay')) return;
    document.getElementById('imgModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(null); });

// ── Sidebar tab switch ───────────────────────────────────────────
function switchSidebarTab(tab) {
    document.getElementById('tabOrders').classList.toggle('active', tab === 'orders');
    document.getElementById('tabCustomize').classList.toggle('active', tab === 'customize');
    document.getElementById('panelOrders').classList.toggle('active', tab === 'orders');
    document.getElementById('panelCustomize').classList.toggle('active', tab === 'customize');
}

// ── Build product card in message bubble ─────────────────────────
function buildProductCard(productData) {
    if (!productData || !productData.image_url) return '';
    const rawPrice    = parseFloat(productData.price) || 0;
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
                <div class="product-card-price">₱${fmtPrice(rawPrice)}</div>
            </div>
        </div>`;
}

// ── Build customize card in message bubble ───────────────────────
function buildCustomizeCard(customizeData) {
    if (!customizeData) return '';
    const imgHtml = customizeData.image_url
        ? `<img class="customize-bubble-img"
                src="${escapeHtml(customizeData.image_url)}"
                alt="Customize Image"
                onclick="openImageModal('${customizeData.image_url.replace(/'/g,"\\'")}','${(customizeData.name||'Customize').replace(/'/g,"\\'")}')">` 
        : '';
    const priceHtml = customizeData.price 
        ? `<div class="customize-bubble-row">💰 Price: ₱${fmtPrice(customizeData.price)}</div>` : '';
    const detailHtml = customizeData.details
        ? `<div class="customize-bubble-row">📝 ${escapeHtml(customizeData.details.substring(0,80))}${customizeData.details.length>80?'...':''}</div>` : '';
    const instrHtml = customizeData.instructions
        ? `<div class="customize-bubble-row">💬 ${escapeHtml(customizeData.instructions.substring(0,60))}${customizeData.instructions.length>60?'...':''}</div>` : '';
    return `
        <div class="customize-card-bubble">
            <div class="customize-bubble-header">
                <div class="customize-bubble-icon">🎨</div>
                <div>
                    <div class="customize-bubble-title">${escapeHtml(customizeData.name || 'Custom Order')}</div>
                    <div class="customize-bubble-ref">${escapeHtml(customizeData.ref || '')}</div>
                </div>
            </div>
            ${imgHtml}
            ${priceHtml}
            ${detailHtml}
            ${instrHtml}
            <span class="customize-bubble-status">${escapeHtml(customizeData.status || 'Pending')}</span>
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
function addMessage(msgText, type = 'staff', senderName = null, productData = null, customizeData = null) {
    const div  = document.createElement('div');
    div.className = `message ${type}`;
    const time = getTimestamp();
    const avatarMap = { user: '👤', staff: '👨‍💼', delivery: '🚚' };
    const avatar    = avatarMap[type] || '👨‍💼';

    const productHtml   = buildProductCard(productData);
    const customizeHtml = buildCustomizeCard(customizeData);
    const textHtml    = msgText    ? `<div>${escapeHtml(msgText)}</div>` : '';
    const senderHtml  = senderName ? `<div class="sender-name">${escapeHtml(senderName)}</div>` : '';
    const inner       = `${senderHtml}${textHtml}${productHtml}${customizeHtml}<div class="timestamp">${time}</div>`;

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

// ── Load orders for sidebar ──────────────────────────────────────
async function loadCustomerOrders() {
    if (!isLiveChatMode) {
        ordersSidebar.classList.remove('active'); return;
    }
    ordersSidebar.classList.add('active');

    // Style header and default tab based on chat type
    const header = document.querySelector('.orders-header');
    const sidebarTitle    = document.getElementById('sidebarTitle');
    const sidebarSubtitle = document.getElementById('sidebarSubtitle');
    if (currentSupportType === 'delivery') {
        header.classList.add('delivery-mode');
        sidebarTitle.innerHTML    = '<i class="fas fa-truck"></i> Your Orders';
        sidebarSubtitle.textContent = 'Click image to preview • Use buttons to share in chat';
        switchSidebarTab('orders');
    } else {
        header.classList.remove('delivery-mode');
        sidebarTitle.innerHTML    = '<i class="fas fa-layer-group"></i> Orders &amp; Customizations';
        sidebarSubtitle.textContent = 'Share your order or customize request with support';
        switchSidebarTab('customize'); // staff chat → default to customize tab
    }
    try {
        const res  = await fetch('{{ route("customer.orders.ongoing") }}', { headers: { 'X-CSRF-TOKEN': csrfToken() } });
        const data = await res.json();
        if (data.success && data.orders && data.orders.length > 0) {
            renderOrders(data.orders);
        } else {
            ordersContent.innerHTML = `<div class="no-orders"><i class="fas fa-box-open"></i><p>No ongoing orders</p></div>`;
        }
    } catch {
        ordersContent.innerHTML = `<div class="no-orders"><i class="fas fa-exclamation-triangle"></i><p>Failed to load orders</p></div>`;
    }

    // Load customizations too
    try {
        const res  = await fetch('{{ route("customer.customizations.pending") }}', { headers: { 'X-CSRF-TOKEN': csrfToken() } });
        const data = await res.json();
        if (data.success && data.customizations && data.customizations.length > 0) {
            renderCustomizations(data.customizations);
        } else {
            customizeContent.innerHTML = `<div class="no-orders"><i class="fas fa-paint-brush"></i><p>No pending customizations</p></div>`;
        }
    } catch {
        customizeContent.innerHTML = `<div class="no-orders"><i class="fas fa-exclamation-triangle"></i><p>Failed to load customizations</p></div>`;
    }
}

function renderOrders(orders) {
    ordersContent.innerHTML = orders.map(order => {
        const statusClass = order.delivery_status === 'Pending' ? 'status-pending' : 'status-out_for_delivery';
        const products    = (order.product_images || []);

        const thumbsHtml = products.map(p => {
            const resolvedSrc = resolveImageUrl(p.image_url);
            const safeSrc     = resolvedSrc.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const safeTitle   = (p.name || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            return `
                <div class="product-thumb">
                    <img src="${escapeHtml(resolvedSrc)}" alt="${escapeHtml(p.name)}"
                         onclick="openImageModal('${safeSrc}','${safeTitle}')"
                         onerror="this.style.display='none'">
                    <span class="thumb-tooltip">${escapeHtml(p.name)}<br>₱${fmtPrice(parseFloat(p.price)||0)}</span>
                </div>`;
        }).join('');

        const shareRowHtml = products.map(p => {
            const pData = JSON.stringify({ name: p.name, price: parseFloat(p.price)||0, image_url: p.image_url }).replace(/'/g,"&#39;");
            const shortName = p.name.length > 14 ? p.name.substring(0,14)+'…' : p.name;
            return `<button class="share-product-btn" onclick='shareProduct(${pData})'>
                        <i class="fas fa-share-alt"></i> ${escapeHtml(shortName)}
                    </button>`;
        }).join('');

        const allJson = JSON.stringify(products.map(p=>({name:p.name,price:parseFloat(p.price)||0,image_url:p.image_url}))).replace(/'/g,"&#39;");

        return `
            <div class="order-card">
                <div class="order-number"><i class="fas fa-receipt"></i> ${order.order_number}</div>
                <span class="order-status ${statusClass}">${order.delivery_status}</span>
                <div class="order-detail"><i class="fas fa-shopping-cart"></i> ${order.items_count} item(s)</div>
                <div class="order-detail"><i class="fas fa-peso-sign"></i> ₱${fmtPrice(order.total)}</div>
                ${order.address ? `<div class="order-detail"><i class="fas fa-map-marker-alt"></i> ${order.address.substring(0,40)}...</div>` : ''}
                ${products.length > 0 ? `
                    <div class="order-products">${thumbsHtml}</div>
                    <p class="product-hint"><i class="fas fa-eye"></i> Click to preview • Share below</p>
                    <div class="share-row">${shareRowHtml}</div>
                    <button class="send-products-btn" onclick='shareAllProducts(${allJson})'>
                        <i class="fas fa-paper-plane"></i> Share all products
                    </button>` : ''}
                <button class="reference-btn" onclick="referenceOrder('${order.order_number}')">
                    <i class="fas fa-comment-dots"></i> Reference in Chat
                </button>
            </div>`;
    }).join('');
}

function renderCustomizations(customizations) {
    customizeContent.innerHTML = customizations.map(c => {
        const statusMap = {
            'Pending':   'cstatus-pending',
            'Reviewing': 'cstatus-reviewing',
            'Approved':  'cstatus-approved',
            'Rejected':  'cstatus-rejected',
            'Completed': 'cstatus-completed'
        };
        const statusClass = statusMap[c.status] || 'cstatus-pending';
        const isPending   = c.status === 'Pending' || c.status === 'Reviewing';

        const imgHtml = c.image_url ? `
            <div class="customize-img-wrap" style="width:100%">
                <img src="${escapeHtml(c.image_url)}" alt="Custom Image"
                     onclick="openImageModal('${c.image_url.replace(/'/g,"\\'")}','${(c.customization_name||'').replace(/'/g,"\\'")}')">
            </div>` : '';

        const pendingNotice = isPending ? `
            <div class="pending-notice">
                <i class="fas fa-clock"></i>
                <span>Awaiting admin approval</span>
            </div>` : '';

        const cData = JSON.stringify({
            ref:          c.ref,
            name:         c.customization_name,
            details:      c.customization_details,
            instructions: c.special_instructions,
            status:       c.status,
            price:        parseFloat(c.admin_price || c.total_price) || 0,
            image_url:    c.image_url
        }).replace(/'/g, "&#39;");

        return `
            <div class="customize-card">
                <div class="customize-ref"><i class="fas fa-fingerprint"></i> ${escapeHtml(c.ref)}</div>
                <span class="customize-status ${statusClass}">
                    <i class="fas fa-circle" style="font-size:8px"></i> ${escapeHtml(c.status)}
                </span>
                ${pendingNotice}
                ${imgHtml}
                <div class="customize-detail"><i class="fas fa-tag"></i> <strong>${escapeHtml(c.customization_name)}</strong></div>
                <div class="customize-detail"><i class="fas fa-align-left"></i> ${escapeHtml((c.customization_details||'').substring(0,60))}${(c.customization_details||'').length>60?'...':''}</div>
                ${c.special_instructions ? `<div class="customize-detail"><i class="fas fa-sticky-note"></i> ${escapeHtml(c.special_instructions.substring(0,50))}${c.special_instructions.length>50?'...':''}</div>` : ''}
                ${(parseFloat(c.admin_price||c.total_price)||0) > 0 ? `<div class="customize-detail"><i class="fas fa-peso-sign"></i> ₱${fmtPrice(parseFloat(c.admin_price||c.total_price))}</div>` : ''}
                <button class="share-customize-btn" onclick='shareCustomizeRequest(${cData})'>
                    <i class="fas fa-paper-plane"></i> Share in Chat
                </button>
                <button class="ref-customize-btn" onclick="referenceCustomize('${escapeHtml(c.ref)}')">
                    <i class="fas fa-comment-dots"></i> Reference in Chat
                </button>
            </div>`;
    }).join('');
}

// ── Share product ─────────────────────────────────────────────────
async function shareProduct(product) {
    if (!isLiveChatMode || !liveChatSessionId) return;
    const rawPrice = parseFloat(product.price) || 0;
    const msgText  = `📦 ${product.name} — ₱${fmtPrice(rawPrice)}`;
    addMessage(msgText, 'user', null, { name: product.name, price: rawPrice, image_url: product.image_url });
    try {
        await fetch('{{ route("livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({ session_id: liveChatSessionId, message: msgText,
                product_name: product.name, product_price: rawPrice, product_image: product.image_url })
        });
    } catch { /* silent */ }
}

async function shareAllProducts(products) {
    for (const p of products) { await shareProduct(p); await new Promise(r=>setTimeout(r,250)); }
}

function referenceOrder(orderNumber) {
    messageInput.value = `I have a question about order ${orderNumber}: `;
    messageInput.focus();
}

// ── Share customize request ───────────────────────────────────────
async function shareCustomizeRequest(cData) {
    if (!isLiveChatMode || !liveChatSessionId) return;

    const statusEmoji = { 'Pending':'⏳', 'Reviewing':'🔍', 'Approved':'✅', 'Rejected':'❌', 'Completed':'🎉' };
    const emoji = statusEmoji[cData.status] || '🎨';
    const msgText = `${emoji} Customize Request [${cData.ref}] — ${cData.name} — Status: ${cData.status}`;

    // Show in UI with full customize card
    addMessage(msgText, 'user', null, null, cData);

    try {
        await fetch('{{ route("livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({
                session_id:          liveChatSessionId,
                message:             msgText,
                customize_ref:       cData.ref,
                customize_name:      cData.name,
                customize_details:   cData.details,
                customize_instructions: cData.instructions,
                customize_status:    cData.status,
                customize_price:     cData.price,
                customize_image:     cData.image_url
            })
        });
    } catch { /* silent */ }
}

function referenceCustomize(ref) {
    messageInput.value = `I have a question about my customize request [${ref}]: `;
    messageInput.focus();
}

// ── Send text message ─────────────────────────────────────────────
async function sendMessage(text) {
    if (!isLiveChatMode || !liveChatSessionId) { alert('Please start a live chat session first.'); return; }
    if (!text) return;
    sendBtn.disabled = true; messageInput.disabled = true;
    addMessage(text, 'user');
    messageInput.value = '';
    try {
        await fetch('{{ route("livechat.send") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body:    JSON.stringify({ session_id: liveChatSessionId, message: text })
        });
    } catch { addMessage('Failed to send. Please try again.', 'system'); }
    sendBtn.disabled = false; messageInput.disabled = false; messageInput.focus();
}

chatForm.addEventListener('submit', e => { e.preventDefault(); const text = messageInput.value.trim(); if (text) sendMessage(text); });
messageInput.addEventListener('keydown', e => { if (e.key==='Enter'&&!e.shiftKey){ e.preventDefault(); chatForm.dispatchEvent(new Event('submit')); }});

// ── Start chat ────────────────────────────────────────────────────
async function startLiveChatWithType(type) {
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    if (!isAuthenticated) { alert('Please login to use live chat support.'); window.location.href='{{ route("home") }}'; return; }
    currentSupportType = type;
    try {
        const route = type==='delivery' ? '{{ route("livechat.request-delivery") }}' : '{{ route("livechat.request") }}';
        const res   = await fetch(route, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken()} });
        const data  = await res.json();
        if (res.ok) {
            liveChatSessionId = data.session_id; isLiveChatMode = true; currentChatStatus = 'waiting';
            supportTypeSelector.classList.remove('active');
            chatInputContainer.style.display = 'block';
            endChatBtn.classList.add('active');
            messageInput.disabled = false; sendBtn.disabled = false;
            await loadCustomerOrders();
            updateUIForLiveChat('waiting', data.queue_position);
            addMessage(`🎫 You've been added to the queue. A ${type==='delivery'?'delivery coordinator':'staff member'} will be with you shortly...`, 'system');
            startPolling();
            messageInput.focus();
        } else { alert(data.message || 'Failed to start live chat.'); }
    } catch { alert('Failed to connect. Please check your internet connection.'); }
}

// ── UI helpers ────────────────────────────────────────────────────
function updateUIForLiveChat(status, queuePosition) {
    if (status === 'waiting') {
        headerAvatar.textContent = '⏱️';
        headerTitle.textContent  = 'Waiting for Support';
        headerStatus.innerHTML   = `<span class="live-chat-status">You are #${queuePosition} in queue</span>`;
        chatModeIndicator.innerHTML = `<span>⏱️</span><span>Waiting for support to join...</span>`;
        chatModeIndicator.className = 'chat-mode-indicator '+(currentSupportType==='delivery'?'delivery':'live');
    } else if (status === 'active') {
        headerAvatar.textContent = currentSupportType==='delivery'?'🚚':'👨‍💼';
        headerTitle.textContent  = currentSupportType==='delivery'?'Delivery Support':'Live Support';
        headerStatus.innerHTML   = '<span class="live-chat-status">Connected to support</span>';
        chatModeIndicator.innerHTML = `<span>${currentSupportType==='delivery'?'🚚':'👨‍💼'}</span><span>Live chat with support</span>`;
        chatModeIndicator.className = 'chat-mode-indicator '+(currentSupportType==='delivery'?'delivery':'live');
    }
}

// ── Poll ──────────────────────────────────────────────────────────
function startPolling() {
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(async () => {
        if (!liveChatSessionId) return;
        try {
            const res  = await fetch(`{{ url('livechat/poll') }}/${liveChatSessionId}`);
            const data = await res.json();
            if (data.status==='active' && currentChatStatus!=='active') {
                currentChatStatus = 'active';
                updateUIForLiveChat('active');
                addMessage('✓ A support member has joined the chat!','system');
                startActivityHeartbeat();
            } else if (data.status==='waiting' && currentChatStatus!=='waiting') {
                currentChatStatus = 'waiting';
                updateUIForLiveChat('waiting', data.queue_position);
            }
            (data.new_messages || []).forEach(msg => {
                if (msg.sender_type==='system') { addMessage(msg.message,'system'); return; }
                if (msg.sender_type==='customer') return;
                const mType = currentSupportType==='delivery'?'delivery':'staff';
                const product = msg.product_image ? { name: msg.product_name, price: parseFloat(msg.product_price)||0, image_url: msg.product_image } : null;
                const customize = msg.customize_image ? {
                    ref: msg.customize_ref, name: msg.customize_name, details: msg.customize_details,
                    instructions: msg.customize_instructions, status: msg.customize_status,
                    price: parseFloat(msg.customize_price)||0, image_url: msg.customize_image
                } : null;
                addMessage(msg.message||null, mType, msg.sender_name||'Support', product, customize);
            });
            if (data.status==='closed') endLiveChat();
        } catch { /* silent */ }
    }, 3000);
}

function markMessagesAsRead() {
    if (!liveChatSessionId) return;
    fetch(`{{ url('livechat/mark-read') }}/${liveChatSessionId}`, { method:'POST', headers:{'X-CSRF-TOKEN':csrfToken()} }).catch(()=>{});
}

// ── End chat ──────────────────────────────────────────────────────
function showEndChatModal()  { liveChatModal.style.display = 'block'; }
function closeEndChatModal() { liveChatModal.style.display = 'none'; }
async function confirmEndChat() { closeEndChatModal(); await endLiveChatSession(); }

async function endLiveChatSession() {
    try {
        stopActivityHeartbeat();
        await fetch('{{ route("livechat.end") }}', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken()},
            body: JSON.stringify({ session_id: liveChatSessionId })
        });
    } catch { /* ignore */ }
    endLiveChat();
}

function endLiveChat() {
    if (pollingInterval) clearInterval(pollingInterval);
    stopActivityHeartbeat();
    isLiveChatMode=false; liveChatSessionId=null; currentChatStatus=null; currentSupportType=null;
    ordersSidebar.classList.remove('active');
    supportTypeSelector.classList.add('active');
    chatInputContainer.style.display='none';
    endChatBtn.classList.remove('active');
    messageInput.disabled=true; sendBtn.disabled=true;
    headerAvatar.textContent='💬'; headerTitle.textContent='Live Support';
    headerStatus.textContent='Choose a support type to begin';
    chatModeIndicator.innerHTML='<span>💬</span><span>Start a live chat to send messages</span>';
    chatModeIndicator.className='chat-mode-indicator';
    addMessage('Chat session ended. Choose a support type to start a new session.','system');
}

function goToHomepage() {
    if (isLiveChatMode && liveChatSessionId) {
        if (confirm('You are in an active live chat. Are you sure you want to leave?'))
            window.location.href = '{{ route("home") }}';
    } else { window.location.href = '{{ route("home") }}'; }
}

// ── Restore session on load ───────────────────────────────────────
window.addEventListener('load', async () => {
    scrollToBottom();
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    if (!isAuthenticated) return;
    try {
        const res  = await fetch('{{ route("livechat.active-session") }}', { headers:{'X-CSRF-TOKEN':csrfToken()} });
        const data = await res.json();
        if (data.success && data.has_session) {
            liveChatSessionId  = data.session.session_id;
            isLiveChatMode     = true;
            currentSupportType = data.session.chat_type || 'staff';
            supportTypeSelector.classList.remove('active');
            chatInputContainer.style.display = 'block';
            endChatBtn.classList.add('active');
            messageInput.disabled = false; sendBtn.disabled = false;
            currentChatStatus = data.session.status;
            if (currentChatStatus==='waiting') {
                updateUIForLiveChat('waiting', data.session.queue_position);
                addMessage('🎫 Reconnected to queue. You are #'+data.session.queue_position+' in line.','system');
            } else if (currentChatStatus==='active') {
                updateUIForLiveChat('active');
                addMessage('✓ Reconnected to your active chat session!','system');
                await loadChatHistory(data.session.session_id);
                startActivityHeartbeat();
            }
            await loadCustomerOrders();
            startPolling(); messageInput.focus();
        }
    } catch { /* ignore */ }
});

async function loadChatHistory(sessionId) {
    try {
        const res  = await fetch(`{{ url('livechat/history') }}/${sessionId}`, { headers:{'X-CSRF-TOKEN':csrfToken()} });
        const data = await res.json();
        (data.messages || []).forEach(msg => {
            const product = msg.product_image ? {
                name: msg.product_name, price: parseFloat(msg.product_price)||0, image_url: msg.product_image
            } : null;
            const customize = msg.customize_image ? {
                ref: msg.customize_ref, name: msg.customize_name, details: msg.customize_details,
                instructions: msg.customize_instructions, status: msg.customize_status,
                price: parseFloat(msg.customize_price)||0, image_url: msg.customize_image
            } : null;
            if (msg.sender_type==='customer') {
                addMessage(msg.message||null,'user',null,product,customize);
            } else if (msg.sender_type==='admin'||msg.sender_type==='delivery') {
                const mType = currentSupportType==='delivery'?'delivery':'staff';
                addMessage(msg.message||null,mType,msg.sender_name||'Support',product,customize);
            } else if (msg.sender_type==='system') {
                addMessage(msg.message,'system');
            }
        });
    } catch { /* ignore */ }
}
</script>
</body>
</html>