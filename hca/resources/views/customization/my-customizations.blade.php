<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Custom Designs — Hookcraft Avenue</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --rose:        #E8637A;
            --rose-dark:   #C94E65;
            --rose-light:  #F4A7B5;
            --rose-pale:   #FDF0F3;
            --rose-soft:   #FAD9E0;
            --petal:       #FFF5F7;
            --amber:       #D97706;
            --amber-pale:  #FFFBEB;
            --amber-light: #FDE68A;
            --green:       #15803D;
            --green-pale:  #F0FDF4;
            --green-mid:   #86efac;
            --blue:        #2563EB;
            --blue-pale:   #EFF6FF;
            --blue-light:  #BFDBFE;
            --cream:       #FDFAF7;
            --ink:         #2C2038;
            --muted:       #8C7A88;
            --border:      #EDD9E0;
            --shadow-sm:   0 2px 10px rgba(44,32,56,0.06);
            --shadow:      0 4px 24px rgba(44,32,56,0.09);
            --shadow-md:   0 8px 40px rgba(44,32,56,0.13);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { overflow-x: hidden; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            color: var(--ink);
        }

        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                radial-gradient(ellipse 800px 600px at 0% 0%, rgba(244,167,181,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 600px 400px at 100% 100%, rgba(123,166,138,0.08) 0%, transparent 70%);
        }

        nav { position: sticky; top: 0; z-index: 9999; }

        /* ── Page Wrap ── */
        .page-wrap {
            max-width: 1240px;
            margin: 0 auto;
            padding: 40px 24px 80px;
            position: relative; z-index: 1;
        }

        /* ── Page Header ── */
        .page-header {
            text-align: center;
            margin-bottom: 48px;
            animation: fadeUp 0.5s ease both;
        }
        .page-header-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--rose-pale); border: 1.5px solid var(--rose-soft);
            border-radius: 50px; padding: 6px 16px;
            font-size: 11px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--rose);
            margin-bottom: 16px;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700; color: var(--ink);
            line-height: 1.15; margin-bottom: 12px;
        }
        .page-header h1 em {
            font-style: italic; color: var(--rose);
        }
        .page-header p {
            font-size: 15px; color: var(--muted);
            max-width: 480px; margin: 0 auto 24px;
            line-height: 1.7;
        }
        .new-design-btn {
            display: inline-flex; align-items: center; gap: 9px;
            background: linear-gradient(135deg, var(--rose), var(--rose-dark));
            color: white; padding: 13px 28px; border-radius: 50px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            box-shadow: 0 6px 24px rgba(232,99,122,0.35);
            transition: all 0.25s ease;
        }
        .new-design-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(232,99,122,0.45);
        }

        /* ── Alerts ── */
        .alert {
            max-width: 700px; margin: 0 auto 28px;
            padding: 14px 20px; border-radius: 12px;
            display: flex; align-items: center; gap: 12px;
            font-weight: 600; font-size: 14px;
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-success { background: var(--green-pale); color: var(--green); border: 1.5px solid var(--green-mid); }
        .alert-info    { background: var(--blue-pale);  color: var(--blue);  border: 1.5px solid var(--blue-light); }
        .alert-icon    { font-size: 1.1rem; flex-shrink: 0; }

        /* ── Stats Bar ── */
        .stats-bar {
            display: flex; gap: 14px; margin-bottom: 36px; flex-wrap: wrap;
            animation: fadeUp 0.5s ease 0.1s both;
        }
        .stat-chip {
            display: flex; align-items: center; gap: 8px;
            background: white; border: 1.5px solid var(--border);
            border-radius: 50px; padding: 8px 18px;
            font-size: 13px; font-weight: 600; color: var(--muted);
            cursor: pointer; transition: all 0.2s ease;
        }
        .stat-chip .dot {
            width: 8px; height: 8px; border-radius: 50%;
        }
        .stat-chip.active, .stat-chip:hover {
            border-color: var(--rose); color: var(--rose);
            box-shadow: 0 2px 12px rgba(232,99,122,0.15);
        }
        .dot-all      { background: var(--muted); }
        .dot-pending  { background: #f59e0b; }
        .dot-approved { background: #22c55e; }
        .dot-rejected { background: #ef4444; }
        .dot-completed { background: #3b82f6; }

        /* ── Cards Grid ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 24px;
        }

        /* ── Card ── */
        .design-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            animation: fadeUp 0.5s ease both;
            display: flex; flex-direction: column;
        }
        .design-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: var(--rose-light);
        }
        .design-card:nth-child(1) { animation-delay: 0.05s; }
        .design-card:nth-child(2) { animation-delay: 0.10s; }
        .design-card:nth-child(3) { animation-delay: 0.15s; }
        .design-card:nth-child(4) { animation-delay: 0.20s; }
        .design-card:nth-child(5) { animation-delay: 0.25s; }
        .design-card:nth-child(6) { animation-delay: 0.30s; }

        /* ── Card Image ── */
        .card-img-wrap {
            position: relative; height: 220px; overflow: hidden;
            background: var(--rose-pale);
            flex-shrink: 0;
        }
        .card-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.5s ease;
        }
        .design-card:hover .card-img-wrap img { transform: scale(1.06); }

        .card-img-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(44,32,56,0.55) 100%);
            pointer-events: none;
        }

        .card-status-pill {
            position: absolute; top: 14px; right: 14px;
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 50px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.5px;
            text-transform: uppercase; backdrop-filter: blur(8px);
        }
        .pill-pending  { background: rgba(254,243,199,0.95); color: #92400e; border: 1.5px solid #fde68a; }
        .pill-approved { background: rgba(209,250,229,0.95); color: #065f46; border: 1.5px solid #6ee7b7; }
        .pill-rejected { background: rgba(254,226,226,0.95); color: #991b1b; border: 1.5px solid #fca5a5; }
        .pill-completed { background: rgba(219,234,254,0.95); color: #1e40af; border: 1.5px solid #93c5fd; }

        .card-img-bottom {
            position: absolute; bottom: 14px; left: 14px; right: 14px;
        }
        .card-img-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem; font-weight: 700; color: white;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .card-img-sub {
            font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 3px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ── Card Body ── */
        .card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; gap: 16px; }

        /* ── Meta Row ── */
        .card-meta {
            display: flex; align-items: center; gap: 16px;
            font-size: 12px; color: var(--muted); font-weight: 500;
        }
        .card-meta span { display: flex; align-items: center; gap: 5px; }
        .card-meta i { color: var(--rose-light); font-size: 11px; }

        /* ── Price Section ── */
        .price-section { }

        /* Pending estimated price */
        .est-price-box {
            background: linear-gradient(135deg, var(--amber-pale), #fffde7);
            border: 1.5px solid var(--amber-light);
            border-radius: 14px; padding: 14px 16px;
            position: relative; overflow: hidden;
        }
        .est-price-box::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2.5px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #fde68a);
        }
        .est-price-eyebrow {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--amber);
            margin-bottom: 4px; display: flex; align-items: center; gap: 5px;
        }
        .est-price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem; font-weight: 700; color: #92400e; line-height: 1;
        }
        .est-price-sub { font-size: 11px; color: #b45309; margin-top: 4px; }

        /* Approved final price */
        .final-price-box {
            background: linear-gradient(135deg, var(--green-pale), #dcfce7);
            border: 1.5px solid var(--green-mid);
            border-radius: 14px; padding: 14px 16px;
            position: relative; overflow: hidden;
        }
        .final-price-box::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2.5px;
            background: linear-gradient(90deg, #22c55e, #4ade80, #86efac);
        }
        .final-price-eyebrow {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--green);
            margin-bottom: 4px; display: flex; align-items: center; gap: 5px;
        }
        .final-price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem; font-weight: 700; color: #166534; line-height: 1;
        }

        /* Ordered price */
        .ordered-price-box {
            background: var(--blue-pale);
            border: 1.5px solid var(--blue-light);
            border-radius: 14px; padding: 14px 16px;
        }
        .ordered-price-eyebrow {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--blue);
            margin-bottom: 4px;
        }
        .ordered-price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem; font-weight: 700; color: var(--blue); line-height: 1;
        }

        /* ── Add-Ons Breakdown (Collapsible) ── */
        .breakdown-section {
            border: 1.5px solid var(--border);
            border-radius: 14px; overflow: hidden;
        }
        .breakdown-toggle {
            width: 100%; background: var(--rose-pale);
            border: none; cursor: pointer;
            padding: 11px 14px;
            display: flex; align-items: center; justify-content: space-between;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--rose);
            letter-spacing: 0.5px; text-transform: uppercase;
            transition: background 0.2s;
        }
        .breakdown-toggle:hover { background: var(--rose-soft); }
        .breakdown-toggle .toggle-left { display: flex; align-items: center; gap: 7px; }
        .breakdown-toggle .chevron {
            transition: transform 0.3s ease; font-size: 10px;
        }
        .breakdown-toggle.open .chevron { transform: rotate(180deg); }

        .breakdown-body {
            max-height: 0; overflow: hidden;
            transition: max-height 0.35s ease;
        }
        .breakdown-body.open { max-height: 500px; }

        .breakdown-table { width: 100%; border-collapse: collapse; }
        .breakdown-table th {
            padding: 8px 12px; background: #f8f4f6;
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: var(--muted); text-align: left;
        }
        .breakdown-table th:last-child { text-align: right; }
        .breakdown-table td {
            padding: 9px 12px; font-size: 12px; color: var(--ink);
            border-top: 1px solid var(--rose-pale);
        }
        .breakdown-table td:last-child { text-align: right; font-weight: 600; color: var(--rose-dark); }
        .breakdown-table tfoot td {
            padding: 10px 12px; background: var(--amber-pale);
            font-size: 12px; font-weight: 700; color: var(--amber);
            border-top: 1.5px solid var(--amber-light);
        }
        .breakdown-table tfoot td:last-child { text-align: right; }

        /* Official breakdown (approved) */
        .official-breakdown-section {
            border: 1.5px solid var(--green-mid);
            border-radius: 14px; overflow: hidden;
        }
        .official-toggle {
            width: 100%; background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: none; cursor: pointer;
            padding: 11px 14px;
            display: flex; align-items: center; justify-content: space-between;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--green);
            letter-spacing: 0.5px; text-transform: uppercase;
            transition: background 0.2s;
        }
        .official-toggle .toggle-left { display: flex; align-items: center; gap: 7px; }
        .official-toggle .chevron { transition: transform 0.3s ease; font-size: 10px; }
        .official-toggle.open .chevron { transform: rotate(180deg); }
        .official-body { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
        .official-body.open { max-height: 500px; }

        .official-table { width: 100%; border-collapse: collapse; }
        .official-table th {
            padding: 8px 12px; background: #f0fdf4;
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: #15803d; text-align: left;
        }
        .official-table th:last-child { text-align: right; }
        .official-table td {
            padding: 9px 12px; font-size: 12px; color: var(--ink);
            border-top: 1px solid #dcfce7;
        }
        .official-table td:last-child { text-align: right; font-weight: 600; color: var(--green); }
        .official-table tfoot td {
            padding: 10px 12px; background: #dcfce7;
            font-size: 12px; font-weight: 700; color: var(--green);
            border-top: 1.5px solid var(--green-mid);
        }
        .official-table tfoot td:last-child { text-align: right; }

        /* ── Status Message ── */
        .status-msg {
            border-radius: 12px; padding: 12px 14px;
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 13px; line-height: 1.6;
        }
        .status-msg.pending  { background: var(--amber-pale); border: 1.5px solid var(--amber-light); color: #92400e; }
        .status-msg.rejected { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }
        .status-msg.ordered  { background: var(--blue-pale); border: 1.5px solid var(--blue-light); color: var(--blue); }
        .status-msg-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
        .status-msg-text { font-weight: 500; }
        .status-msg-text strong { display: block; font-weight: 700; margin-bottom: 2px; }

        /* Admin note */
        .admin-note {
            background: #fffbeb; border: 1.5px solid #fcd34d;
            border-radius: 10px; padding: 10px 13px; margin-top: 8px;
        }
        .admin-note-label {
            font-size: 9px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--amber); margin-bottom: 4px;
        }
        .admin-note-text { font-size: 12px; color: #78350f; line-height: 1.6; }

        /* ── Action Buttons ── */
        .card-actions { display: flex; gap: 9px; flex-wrap: wrap; margin-top: auto; padding-top: 4px; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 18px; border: none; border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            text-decoration: none; transition: all 0.22s ease;
            white-space: nowrap; flex: 1; justify-content: center;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
        .btn-view     { background: var(--rose-pale); color: var(--rose); border: 1.5px solid var(--rose-light); }
        .btn-view:hover { background: var(--rose); color: white; border-color: var(--rose); }
        .btn-edit     { background: #f0f9ff; color: #0369a1; border: 1.5px solid #bae6fd; }
        .btn-edit:hover { background: #0ea5e9; color: white; border-color: #0ea5e9; }
        .btn-cart     { background: var(--green-pale); color: var(--green); border: 1.5px solid var(--green-mid); }
        .btn-cart:hover { background: var(--green); color: white; border-color: var(--green); }
        .btn-checkout { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border: none; }
        .btn-checkout:hover { box-shadow: 0 6px 20px rgba(217,119,6,0.4); }

        /* ── Empty State ── */
        .empty-state {
            text-align: center; padding: 80px 32px;
            background: white; border: 2px dashed var(--border);
            border-radius: 24px; animation: fadeUp 0.5s ease both;
        }
        .empty-icon {
            width: 80px; height: 80px; border-radius: 50%;
            background: var(--rose-pale); display: flex; align-items: center;
            justify-content: center; margin: 0 auto 20px;
            font-size: 2rem;
        }
        .empty-state h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem; font-weight: 700; color: var(--ink); margin-bottom: 10px;
        }
        .empty-state p { font-size: 15px; color: var(--muted); margin-bottom: 28px; line-height: 1.7; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .cards-grid { grid-template-columns: 1fr; }
            .page-wrap  { padding: 28px 16px 60px; }
            .stats-bar  { gap: 8px; }
            .stat-chip  { padding: 6px 14px; font-size: 12px; }
        }
    </style>
</head>
<body>

{{-- Include Navigation Bar --}}
@include('components.customization-navbar')

<div class="page-wrap">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-eyebrow">
            <i class="fas fa-paint-brush"></i> Hookcraft Avenue
        </div>
        <h1>My <em>Custom</em> Designs</h1>
        <p>Track and manage all your personalized flower arrangements, from request to delivery.</p>
        <a href="{{ route('customization.create') }}" class="new-design-btn">
            <i class="fas fa-plus"></i> New Custom Design
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">
            <span class="alert-icon"><i class="fas fa-info-circle"></i></span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if($customizations->count())

        <!-- Stats / Filter Bar -->
        <div class="stats-bar">
            @php
                $total    = $customizations->count();
                $pending  = $customizations->where('status', 'Pending')->count();
                $approved = $customizations->where('status', 'Approved')->count();
                $rejected = $customizations->where('status', 'Rejected')->count();
                $completed = $customizations->where('status', 'Completed')->count();
            @endphp
            <div class="stat-chip active">
                <span class="dot dot-all"></span> All ({{ $total }})
            </div>
            @if($pending)
            <div class="stat-chip">
                <span class="dot dot-pending"></span> Pending ({{ $pending }})
            </div>
            @endif
            @if($approved)
            <div class="stat-chip">
                <span class="dot dot-approved"></span> Approved ({{ $approved }})
            </div>
            @endif
            @if($rejected)
            <div class="stat-chip">
                <span class="dot dot-rejected"></span> Rejected ({{ $rejected }})
            </div>
            @endif
            @if($completed)
            <div class="stat-chip">
                <span class="dot dot-completed"></span> Completed ({{ $completed }})
            </div>
            @endif
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid">
            @foreach($customizations as $custom)

            @php
                /*
                 * Compute estimated total strictly from JSON unit_price × quantity.
                 * Never fall back to additional_price (which already stores the subtotal).
                 */
                $materialRows   = [];
                $estimatedTotal = 0;
                $hasMaterials   = $custom->options ? $custom->options->where('option_type', 'material')->count() > 0 : false;

                if ($custom->options) {
                    foreach ($custom->options->where('option_type', 'material') as $opt) {
                        $d         = json_decode($opt->option_value, true) ?? [];
                        $label     = $d['label']      ?? 'Add-on';
                        $qty       = (float)($d['quantity']   ?? 1);
                        $unitPrice = (float)($d['unit_price']  ?? 0);
                        $sub       = $qty * $unitPrice;
                        $estimatedTotal += $sub;
                        $materialRows[] = compact('label', 'qty', 'unitPrice', 'sub');
                    }
                }

                $statusLower = strtolower($custom->status);
            @endphp

            <div class="design-card">

                <!-- Image -->
                <div class="card-img-wrap">
                    <img
                        src="{{ $custom->custom_image ? asset('uploads/customizations/' . $custom->custom_image) : asset('asset/images/' . $custom->product->image) }}"
                        alt="{{ $custom->customization_name }}"
                        loading="lazy"
                    >
                    <div class="card-img-overlay"></div>

                    <!-- Status pill on image -->
                    <span class="card-status-pill pill-{{ $statusLower }}">
                        @if($statusLower === 'pending')   <i class="fas fa-clock"></i>
                        @elseif($statusLower === 'approved')  <i class="fas fa-check-circle"></i>
                        @elseif($statusLower === 'rejected')  <i class="fas fa-times-circle"></i>
                        @else <i class="fas fa-check-double"></i>
                        @endif
                        {{ $custom->status }}
                    </span>

                    <!-- Title overlay at bottom of image -->
                    <div class="card-img-bottom">
                        <div class="card-img-title">{{ $custom->customization_name }}</div>
                        <div class="card-img-sub">
                            <i class="fas fa-seedling"></i>
                            {{ $custom->product->name ?? 'Custom' }}
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">

                    <!-- Meta -->
                    <div class="card-meta">
                        <span><i class="fas fa-folder"></i> {{ $custom->product->category->name ?? 'N/A' }}</span>
                        <span><i class="fas fa-calendar-alt"></i> {{ $custom->created_at->format('M d, Y') }}</span>
                        @if($custom->order_id)
                        <span><i class="fas fa-receipt"></i> Order #{{ $custom->order_id }}</span>
                        @endif
                    </div>

                    <!-- ══ PRICE SECTION ══ -->

                    @if($custom->isApproved() && !$custom->order_id)
                    {{-- APPROVED: show final admin price --}}
                    <div class="final-price-box">
                        <div class="final-price-eyebrow">
                            <i class="fas fa-check-circle"></i> Final Price (Approved)
                        </div>
                        <div class="final-price-amount">₱{{ number_format($custom->admin_price, 2) }}</div>
                    </div>

                    <!-- Official breakdown if available -->
                    @if($custom->price_breakdown && count($custom->price_breakdown) > 0)
                    <div class="official-breakdown-section">
                        <button class="official-toggle" onclick="toggleBreakdown(this)">
                            <span class="toggle-left">
                                <i class="fas fa-receipt"></i> Official Price Breakdown
                            </span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </button>
                        <div class="official-body">
                            <table class="official-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($custom->price_breakdown as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td>{{ $row['quantity'] }}</td>
                                        <td>₱{{ number_format($row['unit_price'], 2) }}</td>
                                        <td>₱{{ number_format($row['subtotal'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><i class="fas fa-equals" style="margin-right:5px;"></i>Total</td>
                                        <td>₱{{ number_format($custom->admin_price, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($custom->admin_notes)
                    <div class="admin-note">
                        <div class="admin-note-label"><i class="fas fa-comment-dots"></i> Florist Note</div>
                        <div class="admin-note-text">{{ $custom->admin_notes }}</div>
                    </div>
                    @endif

                    @elseif($custom->isPending())
                    {{-- PENDING: show estimated price --}}
                    <div class="est-price-box">
                        <div class="est-price-eyebrow">
                            <i class="fas fa-calculator"></i> Estimated Price
                        </div>
                        <div class="est-price-amount">
                            @if($estimatedTotal > 0)
                                ₱{{ number_format($estimatedTotal, 2) }}
                            @else
                                —
                            @endif
                        </div>
                        <div class="est-price-sub">
                            @if($estimatedTotal > 0)
                                Based on {{ count($materialRows) }} add-on{{ count($materialRows) !== 1 ? 's' : '' }} · Florist will confirm final price
                            @else
                                No add-ons submitted · Florist will provide a full quote
                            @endif
                        </div>
                    </div>

                    <!-- Add-Ons Breakdown (collapsible) -->
                    @if($hasMaterials)
                    <div class="breakdown-section">
                        <button class="breakdown-toggle" onclick="toggleBreakdown(this)">
                            <span class="toggle-left">
                                <i class="fas fa-layer-group"></i> Add-Ons Breakdown ({{ count($materialRows) }})
                            </span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </button>
                        <div class="breakdown-body">
                            <table class="breakdown-table">
                                <thead>
                                    <tr>
                                        <th>Add-On</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materialRows as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td>{{ $row['qty'] }}</td>
                                        <td>₱{{ number_format($row['unitPrice'], 2) }}</td>
                                        <td>₱{{ number_format($row['sub'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><i class="fas fa-tag" style="margin-right:4px;"></i>Add-Ons Total</td>
                                        <td>₱{{ number_format($estimatedTotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="status-msg pending">
                        <span class="status-msg-icon">⏳</span>
                        <div class="status-msg-text">
                            <strong>Under Review</strong>
                            Awaiting florist pricing — typically within 24 hours.
                        </div>
                    </div>

                    @elseif($custom->isRejected())
                    {{-- REJECTED --}}
                    <div class="status-msg rejected">
                        <span class="status-msg-icon">❌</span>
                        <div class="status-msg-text">
                            <strong>Request Not Approved</strong>
                            Unfortunately we couldn't fulfill this request.
                        </div>
                    </div>
                    @if($custom->admin_notes)
                    <div class="admin-note">
                        <div class="admin-note-label"><i class="fas fa-comment-dots"></i> Florist Note</div>
                        <div class="admin-note-text">{{ $custom->admin_notes }}</div>
                    </div>
                    @endif

                    @elseif($custom->order_id)
                    {{-- ORDERED --}}
                    <div class="ordered-price-box">
                        <div class="ordered-price-eyebrow"><i class="fas fa-shopping-bag"></i> Amount Paid</div>
                        <div class="ordered-price-amount">₱{{ number_format($custom->admin_price, 2) }}</div>
                    </div>
                    <div class="status-msg ordered">
                        <span class="status-msg-icon"><i class="fas fa-box"></i></span>
                        <div class="status-msg-text">
                            <strong>Order Placed</strong>
                            Order #{{ $custom->order_id }} is being processed.
                        </div>
                    </div>
                    @endif

                    <!-- ══ ACTION BUTTONS ══ -->
                    <div class="card-actions">
                        <a href="{{ route('customization.show', $custom->id) }}" class="btn btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>

                        @if(!$custom->order_id && $custom->isPending())
                        <a href="{{ route('customization.edit', $custom->id) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif

                        @if($custom->isApproved() && !$custom->order_id)
                        <form action="{{ route('customization.add-to-cart', $custom->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-cart" style="width:100%;">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                        <form action="{{ route('customization.proceed-checkout', $custom->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-checkout" style="width:100%;">
                                <i class="fas fa-bolt"></i> Checkout
                            </button>
                        </form>
                        @endif
                    </div>

                </div>{{-- end card-body --}}
            </div>{{-- end design-card --}}

            @endforeach
        </div>{{-- end cards-grid --}}

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon">🎨</div>
        <h2>No Designs Yet</h2>
        <p>Start creating your first personalized flower arrangement and let our florists bring your vision to life.</p>
        <a href="{{ route('customization.create') }}" class="new-design-btn">
            <i class="fas fa-plus"></i> Start Customizing
        </a>
    </div>
    @endif

</div>

<script>
    function toggleBreakdown(btn) {
        const parent  = btn.parentElement;
        const body    = parent.querySelector('.breakdown-body, .official-body');
        const chevron = btn.querySelector('.chevron');

        if (!body) return;

        const isOpen = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
    }
</script>

</body>
</html> 