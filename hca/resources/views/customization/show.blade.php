<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customization->customization_name }} — Hookcraft Avenue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

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
                radial-gradient(ellipse 700px 500px at 0% 0%,   rgba(244,167,181,0.10) 0%, transparent 70%),
                radial-gradient(ellipse 600px 400px at 100% 100%, rgba(123,166,138,0.07) 0%, transparent 70%);
        }

        nav { position: sticky; top: 0; z-index: 9999; }

        .page-wrap {
            max-width: 1160px;
            margin: 0 auto;
            padding: 36px 24px 80px;
            position: relative; z-index: 1;
        }

        /* ── Back link ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--muted); font-size: 13px; font-weight: 500;
            text-decoration: none; margin-bottom: 24px; transition: color 0.2s;
        }
        .back-link:hover { color: var(--rose); }

        /* ── Page header card ── */
        .page-header {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 24px;
            padding: 28px 32px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 20px;
            animation: fadeUp 0.4s ease both;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.4rem, 3.5vw, 1.9rem);
            font-weight: 700; color: var(--ink);
            line-height: 1.25; margin-bottom: 10px;
        }
        .header-meta {
            display: flex; flex-wrap: wrap; gap: 14px;
            font-size: 12px; color: var(--muted);
        }
        .header-meta span { display: flex; align-items: center; gap: 5px; }
        .header-meta i { color: var(--rose-light); }

        /* Status pill */
        .status-pill {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; border-radius: 50px;
            font-size: 13px; font-weight: 700; white-space: nowrap; flex-shrink: 0;
        }
        .status-pill.pending   { background: #fef3c7; color: #92400e;  border: 1.5px solid #fde68a; }
        .status-pill.approved  { background: #d1fae5; color: #065f46;  border: 1.5px solid #6ee7b7; }
        .status-pill.rejected  { background: #fee2e2; color: #991b1b;  border: 1.5px solid #fca5a5; }
        .status-pill.completed { background: #cffafe; color: #164e63;  border: 1.5px solid #67e8f9; }

        /* ── Two-column layout ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            animation: fadeUp 0.5s ease 0.1s both;
        }

        /* ── Card ── */
        .card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 14px 20px;
            border-bottom: 1.5px solid var(--rose-pale);
            display: flex; align-items: center; gap: 10px;
        }
        .card-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--rose-pale);
            display: flex; align-items: center; justify-content: center;
            color: var(--rose); font-size: 12px; flex-shrink: 0;
        }
        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 0.95rem; font-weight: 600; color: var(--ink);
        }
        .card-body { padding: 20px; }

        /* ── Image ── */
        .img-frame {
            width: 100%; aspect-ratio: 1;
            border-radius: 12px; overflow: hidden;
            box-shadow: var(--shadow); margin-bottom: 16px;
        }
        .img-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* ── Info rows ── */
        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 9px 0; border-bottom: 1px solid var(--rose-pale); font-size: 13px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: var(--muted); }
        .info-value { color: var(--ink); font-weight: 500; text-align: right; max-width: 60%; }

        /* ── Description ── */
        .field-label {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--rose); margin-bottom: 7px;
        }
        .desc-box {
            background: var(--rose-pale); border-radius: 10px;
            padding: 14px 16px; font-size: 13px; line-height: 1.75;
            color: var(--ink); white-space: pre-line; margin-bottom: 16px;
        }

        /* ── Add-ons table ── */
        .addons-table { width: 100%; border-collapse: collapse; }
        .addons-table thead tr { background: linear-gradient(135deg, var(--rose), var(--rose-dark)); }
        .addons-table th {
            padding: 10px 14px; font-size: 11px; font-weight: 700;
            color: white; text-align: left; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .addons-table th:last-child { text-align: right; }
        .addons-table td {
            padding: 11px 14px; border-bottom: 1px solid var(--rose-pale);
            font-size: 13px; color: var(--ink);
        }
        .addons-table td:last-child { text-align: right; font-weight: 600; color: var(--rose-dark); }
        .addons-table tbody tr:last-child td { border-bottom: none; }
        .addons-table tbody tr:hover { background: var(--petal); }
        .addons-table tfoot td {
            padding: 12px 14px; font-weight: 800; font-size: 13px;
            background: var(--amber-pale); color: var(--amber);
            border-top: 1.5px solid var(--amber-light);
        }
        .addons-table tfoot td:last-child { text-align: right; font-size: 1rem; }

        /* ── Estimate note bar ── */
        .estimate-note {
            display: flex; align-items: flex-start; gap: 9px;
            background: var(--amber-pale); border-top: 1px solid var(--amber-light);
            padding: 12px 16px; font-size: 12px; color: #92400e; line-height: 1.6;
        }
        .estimate-note i { color: #f59e0b; margin-top: 2px; flex-shrink: 0; }

        /* ── Status banner ── */
        .status-banner {
            border-radius: 16px; padding: 16px 18px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 13px;
        }
        .banner-emoji { font-size: 1.4rem; flex-shrink: 0; line-height: 1.3; }
        .status-banner h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700; margin-bottom: 4px;
        }
        .status-banner p { font-size: 13px; line-height: 1.6; }
        .status-banner.approved { background: var(--green-pale);  border: 1.5px solid #86efac; color: var(--green); }
        .status-banner.pending  { background: var(--amber-pale);  border: 1.5px solid #fcd34d; color: #92400e; }
        .status-banner.rejected { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }

        /* ── Estimated price box (pending) ── */
        .est-price-box {
            background: white;
            border: 1.5px solid var(--amber-light);
            border-radius: 16px;
            padding: 20px 22px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .est-price-box::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #fde68a);
        }
        .est-price-eyebrow {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--amber);
            margin-bottom: 8px; display: flex; align-items: center; gap: 6px;
        }
        .est-price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem; font-weight: 700;
            color: #92400e; line-height: 1; margin-bottom: 10px;
        }
        .est-price-note { font-size: 12px; color: #b45309; line-height: 1.65; }

        /* ── Official breakdown table ── */
        .official-table { width: 100%; border-collapse: collapse; }
        .official-table thead tr { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .official-table th {
            padding: 10px 14px; font-size: 11px; font-weight: 700;
            color: white; text-align: left; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .official-table th:last-child { text-align: right; }
        .official-table td {
            padding: 11px 14px; border-bottom: 1px solid #f0fdf4;
            font-size: 13px; color: var(--ink);
        }
        .official-table td:last-child { text-align: right; font-weight: 600; color: #15803d; }
        .official-table tbody tr:last-child td { border-bottom: none; }
        .official-table tbody tr:hover { background: #f0fdf4; }
        .official-table tfoot td {
            padding: 14px; font-weight: 800; font-size: 1rem;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: var(--green); border-top: 2px solid #86efac;
        }
        .official-table tfoot td:last-child { text-align: right; font-size: 1.2rem; }

        /* ── Florist note ── */
        .florist-note {
            background: #fffbeb; border: 1.5px solid #fcd34d;
            border-radius: 14px; padding: 15px 18px; margin-top: 16px;
        }
        .florist-note-label {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--amber);
            margin-bottom: 7px; display: flex; align-items: center; gap: 6px;
        }
        .florist-note p { font-size: 13px; color: #78350f; line-height: 1.7; }

        /* ── No add-ons ── */
        .no-addons {
            background: #f8fafc; border: 1.5px dashed #cbd5e1;
            border-radius: 14px; padding: 28px 20px; margin-bottom: 20px;
            text-align: center; color: #94a3b8; font-size: 13px; line-height: 1.7;
        }
        .no-addons i { font-size: 1.8rem; margin-bottom: 10px; display: block; color: #cbd5e1; }

        /* ── Ordered notice ── */
        .ordered-notice {
            background: var(--green-pale); border: 1.5px solid #86efac;
            border-radius: 12px; padding: 13px 16px; margin-top: 14px;
            color: var(--green); font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── Buttons ── */
        .btn {
            padding: 11px 22px; border: none; border-radius: 11px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: all 0.22s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn-primary  { background: linear-gradient(135deg, var(--rose), var(--rose-dark)); color: white; }
        .btn-secondary { background: white; color: var(--muted); border: 1.5px solid var(--border); }
        .btn-secondary:hover { color: var(--ink); border-color: var(--rose-light); box-shadow: none; transform: none; }
        .btn-danger    { background: #fee2e2; color: #dc2626; border: 1.5px solid #fca5a5; }
        .btn-danger:hover { background: #dc2626; color: white; }
        .btn-success   { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        .btn-checkout  { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 13px 26px; }
        .action-row    { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 900px) {
            .grid-2 { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; }
        }
    </style>
</head>
<body>
    @include('components.customization-navbar')

    @php
        /*
         * Compute estimated total STRICTLY from JSON unit_price.
         * DO NOT fall back to $opt->additional_price — that column stores
         * the subtotal (qty × price), so using it as a unit_price causes
         * double-multiplication and a wrong total.
         */
        $materialRows   = [];
        $estimatedTotal = 0;
        $hasMaterials   = $customization->options->where('option_type', 'material')->count() > 0;

        foreach ($customization->options->where('option_type', 'material') as $opt) {
            $d         = json_decode($opt->option_value, true) ?? [];
            $label     = $d['label']     ?? $opt->option_value;
            $qty       = (int)   ($d['quantity']   ?? 1);
            $unitPrice = (float) ($d['unit_price']  ?? 0);  // strict — no fallback
            $sub       = $qty * $unitPrice;
            $estimatedTotal += $sub;
            $materialRows[]  = compact('label', 'qty', 'unitPrice', 'sub');
        }
    @endphp

    <div class="page-wrap">

        <a href="{{ route('customization.my-customizations') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> My Customizations
        </a>

        {{-- ── HEADER ── --}}
        <div class="page-header">
            <div>
                <h1>{{ $customization->customization_name }}</h1>
                <div class="header-meta">
                    <span><i class="fas fa-tag"></i> {{ $customization->product->category->name ?? 'N/A' }}</span>
                    <span><i class="fas fa-cube"></i> {{ $customization->product->name }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $customization->created_at->format('M d, Y') }}</span>
                    @if($customization->order_id)
                    <span><i class="fas fa-receipt"></i> Order #{{ $customization->order_id }}</span>
                    @endif
                </div>
            </div>
            <span class="status-pill {{ strtolower($customization->status) }}">
                @if($customization->status === 'Pending')    <i class="fas fa-clock"></i>
                @elseif($customization->status === 'Approved')  <i class="fas fa-check-circle"></i>
                @elseif($customization->status === 'Rejected')  <i class="fas fa-times-circle"></i>
                @else <i class="fas fa-check-double"></i>
                @endif
                {{ $customization->status }}
            </span>
        </div>

        {{-- ── BODY ── --}}
        <div class="grid-2">

            {{-- ═══ LEFT ═══ --}}
            <div>
                {{-- Design Preview --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fas fa-paint-brush"></i></div>
                        <h3>Design Preview</h3>
                    </div>
                    <div class="card-body">
                        <div class="img-frame">
                            @if($customization->custom_image)
                                <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" alt="Preview">
                            @else
                                <img src="{{ asset('asset/images/' . $customization->product->image) }}" alt="{{ $customization->product->name }}">
                            @endif
                        </div>
                        <div class="info-row">
                            <span class="info-label">Submitted</span>
                            <span class="info-value">{{ $customization->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ $customization->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Add-Ons Table --}}
                @if($hasMaterials)
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fas fa-list-alt"></i></div>
                        <h3>Your Submitted Add-Ons</h3>
                    </div>
                    <table class="addons-table">
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
                                <td colspan="3">Your Estimated Total</td>
                                <td>₱{{ number_format($estimatedTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="estimate-note">
                        <i class="fas fa-info-circle"></i>
                        <span>These are the reference prices you selected when submitting. Your florist will confirm the official price.</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- ═══ RIGHT ═══ --}}
            <div>
                {{-- Customization Description --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fas fa-file-alt"></i></div>
                        <h3>Customization Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="field-label">Description</div>
                        <div class="desc-box">{{ $customization->customization_details }}</div>
                        @if($customization->special_instructions)
                        <div class="field-label">Special Instructions</div>
                        <div class="desc-box" style="margin-bottom:0;">{{ $customization->special_instructions }}</div>
                        @endif
                    </div>
                </div>

                {{-- ══ STATUS BLOCK ══ --}}

                @if($customization->isApproved())
                {{-- ─── APPROVED ─── --}}
                <div class="status-banner approved">
                    <div class="banner-emoji">🎉</div>
                    <div>
                        <h4>Request Approved!</h4>
                        <p>Our florists reviewed your request and set the official price below. Add it to your cart or checkout now.</p>
                    </div>
                </div>

                @if($customization->price_breakdown && count($customization->price_breakdown) > 0)
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom-color:#86efac;">
                        <div class="card-icon" style="background:#dcfce7;color:#15803d;"><i class="fas fa-receipt"></i></div>
                        <h3 style="color:#15803d;">Official Price Breakdown</h3>
                    </div>
                    <table class="official-table">
                        <thead>
                            <tr>
                                <th>Item / Material</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customization->price_breakdown as $row)
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
                                <td colspan="3">Total Amount</td>
                                <td>{{ $customization->formatted_price }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="card">
                    <div class="card-body" style="text-align:center;padding:28px;">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#15803d;margin-bottom:8px;">Final Price</div>
                        <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--green);">{{ $customization->formatted_price }}</div>
                    </div>
                </div>
                @endif

                @if($customization->admin_notes)
                <div class="florist-note">
                    <div class="florist-note-label"><i class="fas fa-comment-dots"></i> Florist Note</div>
                    <p>{{ $customization->admin_notes }}</p>
                </div>
                @endif

                @if($customization->canCheckout())
                <div class="action-row">
                    <a href="{{ route('customization.add-to-cart', $customization->id) }}" class="btn btn-success">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </a>
                    <a href="{{ route('customization.proceed-checkout', $customization->id) }}" class="btn btn-checkout">
                        <i class="fas fa-bolt"></i> Checkout Now
                    </a>
                </div>
                @elseif($customization->order_id)
                <div class="ordered-notice">
                    <i class="fas fa-check-double"></i>
                    This customization has been ordered (#{{ $customization->order_id }})
                </div>
                @endif

                @elseif($customization->isRejected())
                {{-- ─── REJECTED ─── --}}
                <div class="status-banner rejected">
                    <div class="banner-emoji">😔</div>
                    <div>
                        <h4>Request Not Approved</h4>
                        <p>Unfortunately we're unable to fulfill this request as submitted.
                        @if($customization->admin_notes) See the florist note below for details.@endif</p>
                    </div>
                </div>
                @if($customization->admin_notes)
                <div class="florist-note">
                    <div class="florist-note-label"><i class="fas fa-comment-dots"></i> Florist Note</div>
                    <p>{{ $customization->admin_notes }}</p>
                </div>
                @endif

                @elseif($customization->isCompleted())
                {{-- ─── COMPLETED ─── --}}
                <div class="status-banner approved">
                    <div class="banner-emoji">✅</div>
                    <div>
                        <h4>Order Completed!</h4>
                        <p>Your custom bouquet has been completed. Thank you for choosing Hookcraft Avenue!</p>
                    </div>
                </div>
                @if($customization->price_breakdown && count($customization->price_breakdown) > 0)
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom-color:#86efac;">
                        <div class="card-icon" style="background:#dcfce7;color:#15803d;"><i class="fas fa-receipt"></i></div>
                        <h3 style="color:#15803d;">Final Price Breakdown</h3>
                    </div>
                    <table class="official-table">
                        <thead>
                            <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($customization->price_breakdown as $row)
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
                                <td colspan="3">Total Paid</td>
                                <td>{{ $customization->formatted_price }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                @else
                {{-- ─── PENDING ─── --}}
                <div class="status-banner pending">
                    <div class="banner-emoji">⏳</div>
                    <div>
                        <h4>Under Review</h4>
                        <p>Our florists are reviewing your request. You'll receive an official price quote within 24 hours.</p>
                    </div>
                </div>

                @if($hasMaterials)
                {{-- Estimated price — strictly unit_price × qty from JSON, no additional_price fallback --}}
                <div class="est-price-box">
                    <div class="est-price-eyebrow">
                        <i class="fas fa-calculator"></i> Your Estimated Price
                    </div>
                    <div class="est-price-amount">₱{{ number_format($estimatedTotal, 2) }}</div>
                    <div class="est-price-note">
                        Based on {{ count($materialRows) }} add-on{{ count($materialRows) !== 1 ? 's' : '' }} you selected
                        — see the full breakdown on the left. The florist will confirm the final price, which may differ slightly.
                    </div>
                </div>
                @else
                <div class="no-addons">
                    <i class="fas fa-tag"></i>
                    No add-ons were submitted with this request.<br>
                    Our florists will provide a full price quote after reviewing your description.
                </div>
                @endif
                @endif

                {{-- Edit / Delete (only when pending) --}}
                @if($customization->canEdit())
                <div class="action-row">
                    <a href="{{ route('customization.edit', $customization->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Request
                    </a>
                    <form action="{{ route('customization.destroy', $customization->id) }}" method="POST"
                          style="display:inline;" onsubmit="return confirm('Delete this customization?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                @endif

            </div>{{-- end right --}}
        </div>{{-- end grid --}}
    </div>
</body>
</html>