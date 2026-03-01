<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit — {{ $customization->customization_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            max-width: 860px;
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

        /* ── Page Header ── */
        .page-header {
            margin-bottom: 28px;
            animation: fadeUp 0.4s ease both;
        }
        .page-header-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--rose-pale); border: 1.5px solid var(--rose-soft);
            border-radius: 50px; padding: 5px 14px;
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--rose); margin-bottom: 12px;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 700; color: var(--ink); line-height: 1.2; margin-bottom: 6px;
        }
        .page-header h1 em { font-style: italic; color: var(--rose); }
        .page-header p { font-size: 14px; color: var(--muted); }

        /* ── Alerts ── */
        .alert {
            padding: 14px 18px; border-radius: 12px;
            display: flex; align-items: flex-start; gap: 11px;
            font-size: 14px; font-weight: 500; margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
        .alert-success { background: var(--green-pale); color: var(--green); border: 1.5px solid #86efac; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1.5px solid #fca5a5; }
        .alert i { margin-top: 2px; flex-shrink: 0; }

        /* ── Pending Warning ── */
        .pending-notice {
            background: var(--amber-pale); border: 1.5px solid var(--amber-light);
            border-radius: 14px; padding: 14px 18px; margin-bottom: 24px;
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 13px; color: #92400e; line-height: 1.6;
            animation: fadeUp 0.4s ease 0.05s both;
        }
        .pending-notice i { color: #f59e0b; flex-shrink: 0; margin-top: 2px; }
        .pending-notice strong { font-weight: 700; display: block; margin-bottom: 2px; }

        /* ── Card ── */
        .card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            animation: fadeUp 0.5s ease both;
        }
        .card:nth-child(1) { animation-delay: 0.05s; }
        .card:nth-child(2) { animation-delay: 0.10s; }
        .card:nth-child(3) { animation-delay: 0.15s; }
        .card:nth-child(4) { animation-delay: 0.20s; }

        .card-header {
            padding: 16px 22px;
            border-bottom: 1.5px solid var(--rose-pale);
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, white, var(--petal));
        }
        .card-icon {
            width: 32px; height: 32px; border-radius: 9px;
            background: var(--rose-pale);
            display: flex; align-items: center; justify-content: center;
            color: var(--rose); font-size: 13px; flex-shrink: 0;
        }
        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 600; color: var(--ink);
        }
        .card-header .card-header-note {
            margin-left: auto; font-size: 11px; color: var(--muted);
            font-style: italic;
        }
        .card-body { padding: 24px; }

        /* ── Reference Product Strip ── */
        .product-strip {
            display: flex; align-items: center; gap: 16px;
            background: var(--rose-pale); border: 1.5px solid var(--rose-soft);
            border-radius: 14px; padding: 14px 18px; margin-bottom: 24px;
        }
        .product-strip img {
            width: 64px; height: 64px; object-fit: cover;
            border-radius: 10px; border: 2px solid white;
            box-shadow: var(--shadow-sm); flex-shrink: 0;
        }
        .product-strip-info { flex: 1; min-width: 0; }
        .product-strip-label {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--rose); margin-bottom: 3px;
        }
        .product-strip-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem; font-weight: 600; color: var(--ink);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .product-strip-cat { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .product-strip-badge {
            background: white; border: 1.5px solid var(--amber-light);
            border-radius: 8px; padding: 6px 12px; flex-shrink: 0;
            font-size: 11px; font-weight: 700; color: var(--amber);
        }

        /* ── Form Elements ── */
        .form-group { margin-bottom: 20px; }
        .form-group:last-child { margin-bottom: 0; }

        .form-label {
            display: block; font-size: 12px; font-weight: 700;
            letter-spacing: 0.5px; text-transform: uppercase;
            color: var(--muted); margin-bottom: 8px;
        }
        .form-label .req { color: var(--rose); margin-left: 3px; }

        .form-input, .form-textarea, .form-select {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid var(--border); border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: var(--ink);
            background: var(--cream);
            transition: all 0.2s ease;
            appearance: none;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--rose);
            background: white;
            box-shadow: 0 0 0 3px rgba(232,99,122,0.1);
        }
        .form-textarea { min-height: 130px; resize: vertical; line-height: 1.7; }
        .form-hint {
            font-size: 11px; color: var(--muted); margin-top: 6px;
            display: flex; align-items: center; gap: 5px;
        }

        /* ── Image Upload ── */
        .img-current {
            display: flex; align-items: flex-start; gap: 16px;
            background: var(--rose-pale); border: 1.5px solid var(--rose-soft);
            border-radius: 14px; padding: 16px; margin-bottom: 16px;
        }
        .img-current img {
            width: 100px; height: 100px; object-fit: cover;
            border-radius: 10px; border: 2px solid white;
            box-shadow: var(--shadow-sm); flex-shrink: 0;
        }
        .img-current-info { flex: 1; }
        .img-current-label {
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--rose); margin-bottom: 5px;
        }
        .img-current-note { font-size: 13px; color: var(--muted); line-height: 1.6; }

        .upload-zone {
            border: 2px dashed var(--rose-light);
            border-radius: 16px; padding: 36px 24px;
            text-align: center; cursor: pointer;
            background: var(--rose-pale);
            transition: all 0.25s ease;
        }
        .upload-zone:hover {
            border-color: var(--rose);
            background: var(--rose-soft);
        }
        .upload-zone input[type="file"] { display: none; }
        .upload-zone-icon {
            width: 52px; height: 52px; border-radius: 14px;
            background: white; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 12px;
            color: var(--rose); font-size: 1.3rem;
            box-shadow: var(--shadow-sm);
        }
        .upload-zone-title { font-size: 14px; font-weight: 600; color: var(--ink); margin-bottom: 4px; }
        .upload-zone-sub { font-size: 12px; color: var(--muted); }

        /* New image preview */
        .img-preview-wrap {
            display: none; position: relative;
            border-radius: 14px; overflow: hidden;
            border: 1.5px solid var(--border); box-shadow: var(--shadow-sm);
        }
        .img-preview-wrap img {
            width: 100%; max-height: 300px; object-fit: cover; display: block;
        }
        .img-preview-remove {
            position: absolute; top: 12px; right: 12px;
            background: rgba(255,255,255,0.95); border: none;
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #dc2626; font-size: 14px;
            box-shadow: var(--shadow); transition: all 0.2s;
        }
        .img-preview-remove:hover { background: #dc2626; color: white; }

        /* ── Materials Table ── */
        .materials-table-wrap {
            border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden;
            margin-bottom: 14px;
        }
        .materials-table { width: 100%; border-collapse: collapse; }
        .materials-table thead tr {
            background: linear-gradient(135deg, var(--rose), var(--rose-dark));
        }
        .materials-table th {
            padding: 10px 14px; font-size: 11px; font-weight: 700;
            color: white; text-align: left; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .materials-table th:last-child { width: 44px; }
        .materials-table td {
            padding: 8px 10px; border-top: 1px solid var(--rose-pale);
        }
        .materials-table td input {
            width: 100%; padding: 8px 10px;
            border: 1.5px solid var(--border); border-radius: 8px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--ink);
            background: var(--cream); transition: all 0.2s;
        }
        .materials-table td input:focus {
            outline: none; border-color: var(--rose); background: white;
            box-shadow: 0 0 0 3px rgba(232,99,122,0.08);
        }
        .materials-table tfoot td {
            padding: 10px 14px; background: var(--amber-pale);
            font-size: 12px; font-weight: 700; color: var(--amber);
            border-top: 1.5px solid var(--amber-light);
        }
        .materials-table tfoot td:last-child { text-align: right; }

        .btn-remove-row {
            width: 30px; height: 30px; border-radius: 8px;
            background: #fee2e2; color: #dc2626; border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 12px; transition: all 0.2s;
        }
        .btn-remove-row:hover { background: #dc2626; color: white; }

        .btn-add-row {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--rose-pale); color: var(--rose);
            border: 1.5px solid var(--rose-light); border-radius: 10px;
            padding: 9px 16px; font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-add-row:hover { background: var(--rose); color: white; border-color: var(--rose); }

        .subtotal-live {
            font-size: 12px; font-weight: 700; color: var(--rose-dark); white-space: nowrap;
        }

        /* ── Live Estimated Total ── */
        .total-display {
            background: linear-gradient(135deg, var(--amber-pale), #fffde7);
            border: 1.5px solid var(--amber-light); border-radius: 14px;
            padding: 16px 20px; display: flex; align-items: center;
            justify-content: space-between; margin-top: 14px;
        }
        .total-display-label {
            font-size: 12px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: var(--amber);
        }
        .total-display-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem; font-weight: 700; color: #92400e;
        }

        /* ── Action Row ── */
        .action-row {
            display: flex; gap: 12px; margin-top: 28px; flex-wrap: wrap;
            animation: fadeUp 0.5s ease 0.25s both;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px; border: none; border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; font-weight: 600; cursor: pointer;
            text-decoration: none; transition: all 0.22s ease; white-space: nowrap;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn-primary {
            background: linear-gradient(135deg, var(--rose), var(--rose-dark));
            color: white; box-shadow: 0 4px 16px rgba(232,99,122,0.3);
            flex: 1; justify-content: center;
        }
        .btn-primary:hover { box-shadow: 0 8px 28px rgba(232,99,122,0.45); }
        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-ghost {
            background: white; color: var(--muted);
            border: 1.5px solid var(--border);
        }
        .btn-ghost:hover { color: var(--ink); border-color: var(--rose-light); box-shadow: none; transform: none; }

        /* ── Animation ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .product-strip { flex-wrap: wrap; }
            .product-strip-badge { width: 100%; text-align: center; }
            .action-row { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .materials-table th:nth-child(3),
            .materials-table td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>

@include('components.customization-navbar')

<div class="page-wrap">

    <a href="{{ route('customization.show', $customization->id) }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Design
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-eyebrow">
            <i class="fas fa-edit"></i> Edit Request
        </div>
        <h1>Update Your <em>Custom Design</em></h1>
        <p>Only pending requests can be edited. Changes will be re-reviewed by our florists.</p>
    </div>

    <!-- Pending Notice -->
    <div class="pending-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Pending Status</strong>
            This request is currently awaiting review. Editing it will keep it in the pending queue — our florists will see your latest version.
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul style="margin-top: 8px; padding-left: 18px; line-height: 1.8;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('customization.update', $customization->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <!-- ── 1. Reference Product ── -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon"><i class="fas fa-seedling"></i></div>
                <h3>Reference Product</h3>
                <span class="card-header-note">Base for your customization</span>
            </div>
            <div class="card-body">
                <div class="product-strip">
                    <img
                        src="{{ asset('asset/images/' . $customization->product->image) }}"
                        alt="{{ $customization->product->name }}"
                        onerror="this.src='{{ asset('asset/images/placeholder.jpg') }}'"
                    >
                    <div class="product-strip-info">
                        <div class="product-strip-label">Selected Product</div>
                        <div class="product-strip-name">{{ $customization->product->name }}</div>
                        <div class="product-strip-cat">{{ $customization->product->category->name ?? '' }}</div>
                    </div>
                    <div class="product-strip-badge">
                        <i class="fas fa-tag" style="margin-right:4px;"></i>
                        ₱{{ number_format($customization->product->price, 2) }}
                    </div>
                </div>

                <!-- Hidden fields to preserve product/category -->
                <input type="hidden" name="category_id" value="{{ $customization->category_id }}">
                <input type="hidden" name="product_id"  value="{{ $customization->product_id }}">
                <p class="form-hint">
                    <i class="fas fa-lock"></i>
                    The reference product cannot be changed after submission. Contact us if you need a different base.
                </p>
            </div>
        </div>

        <!-- ── 2. Design Details ── -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon"><i class="fas fa-paint-brush"></i></div>
                <h3>Design Details</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Design Name <span class="req">*</span></label>
                    <input type="text" class="form-input" name="customization_name"
                           placeholder="e.g., Romantic Red Rose Bouquet"
                           value="{{ old('customization_name', $customization->customization_name) }}" required>
                    <div class="form-hint"><i class="fas fa-lightbulb"></i> Give your design a memorable name</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Detailed Description <span class="req">*</span></label>
                    <textarea class="form-textarea" name="customization_details"
                              placeholder="Describe your vision — flowers, colors, arrangement style, size, occasion…"
                              required>{{ old('customization_details', $customization->customization_details) }}</textarea>
                    <div class="form-hint"><i class="fas fa-info-circle"></i> The more detail you provide, the better our florists can match your vision</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Special Instructions <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:11px;">(optional)</span></label>
                    <textarea class="form-textarea" name="special_instructions"
                              style="min-height: 90px;"
                              placeholder="Allergies, delivery notes, card messages, wrapping preferences…">{{ old('special_instructions', $customization->special_instructions) }}</textarea>
                </div>

            </div>
        </div>

        <!-- ── 3. Add-Ons / Materials ── -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                <h3>Add-Ons &amp; Materials</h3>
                <span class="card-header-note">Optional — helps us estimate your price</span>
            </div>
            <div class="card-body">

                <div class="materials-table-wrap" id="materialsWrap">
                    <table class="materials-table" id="materialsTable">
                        <thead>
                            <tr>
                                <th>Item / Material</th>
                                <th style="width:80px;">Qty</th>
                                <th style="width:120px;">Unit Price (₱)</th>
                                <th style="width:100px;">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="materialsBody">
                            @php
                                $existingMaterials = [];
                                if ($customization->options) {
                                    foreach ($customization->options->where('option_type', 'material') as $opt) {
                                        $d = json_decode($opt->option_value, true) ?? [];
                                        $existingMaterials[] = [
                                            'label'      => $d['label']      ?? '',
                                            'quantity'   => $d['quantity']   ?? 1,
                                            'unit_price' => $d['unit_price'] ?? 0,
                                        ];
                                    }
                                }
                            @endphp

                            @if(count($existingMaterials))
                                @foreach($existingMaterials as $i => $mat)
                                <tr class="material-row">
                                    <td>
                                        <input type="text" name="materials[{{ $i }}][label]"
                                               value="{{ $mat['label'] }}" placeholder="e.g., Red Roses">
                                    </td>
                                    <td>
                                        <input type="number" name="materials[{{ $i }}][quantity]"
                                               value="{{ $mat['quantity'] }}" min="1" step="1"
                                               oninput="recalcRow(this)">
                                    </td>
                                    <td>
                                        <input type="number" name="materials[{{ $i }}][unit_price]"
                                               value="{{ $mat['unit_price'] }}" min="0" step="0.01"
                                               oninput="recalcRow(this)">
                                    </td>
                                    <td>
                                        <span class="subtotal-live">
                                            ₱{{ number_format($mat['quantity'] * $mat['unit_price'], 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <!-- one empty row by default -->
                                <tr class="material-row">
                                    <td><input type="text" name="materials[0][label]" placeholder="e.g., Red Roses"></td>
                                    <td><input type="number" name="materials[0][quantity]" value="1" min="1" step="1" oninput="recalcRow(this)"></td>
                                    <td><input type="number" name="materials[0][unit_price]" value="0" min="0" step="0.01" oninput="recalcRow(this)"></td>
                                    <td><span class="subtotal-live">₱0.00</span></td>
                                    <td>
                                        <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><i class="fas fa-calculator" style="margin-right:5px;"></i>Add-Ons Estimated Total</td>
                                <td id="grandTotal">₱0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="button" class="btn-add-row" onclick="addRow()">
                    <i class="fas fa-plus"></i> Add Item
                </button>

                <!-- Live total display -->
                <div class="total-display">
                    <div class="total-display-label"><i class="fas fa-tag" style="margin-right:5px;"></i>Your Estimated Add-Ons Total</div>
                    <div class="total-display-amount" id="liveTotalDisplay">₱0.00</div>
                </div>

                <div class="form-hint" style="margin-top: 10px;">
                    <i class="fas fa-info-circle"></i>
                    These are reference prices to help our florists understand your budget. The final price will be set by your florist.
                </div>
            </div>
        </div>

        <!-- ── 4. Reference Image ── -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon"><i class="fas fa-image"></i></div>
                <h3>Reference Image</h3>
                <span class="card-header-note">Optional</span>
            </div>
            <div class="card-body">

                @if($customization->custom_image)
                <div class="img-current">
                    <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" alt="Current image">
                    <div class="img-current-info">
                        <div class="img-current-label">Current Image</div>
                        <div class="img-current-note">This is the inspiration image you previously uploaded. Upload a new one below to replace it, or leave blank to keep this one.</div>
                    </div>
                </div>
                @endif

                <!-- Upload Zone -->
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('custom_image').click()">
                    <input type="file" id="custom_image" name="custom_image" accept="image/*" onchange="previewImage(this)">
                    <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-zone-title">
                        {{ $customization->custom_image ? 'Upload replacement image' : 'Upload inspiration image' }}
                    </div>
                    <div class="upload-zone-sub">JPG, PNG, GIF · Max 5MB</div>
                </div>

                <!-- Preview -->
                <div class="img-preview-wrap" id="previewWrap" style="margin-top:14px;">
                    <img id="imgPreview" src="" alt="New image preview">
                    <button type="button" class="img-preview-remove" onclick="removePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- ── Actions ── -->
        <div class="action-row">
            <a href="{{ route('customization.show', $customization->id) }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>

    </form>
</div>

<script>
    // ── Materials row counter
    let rowCount = {{ count($existingMaterials) > 0 ? count($existingMaterials) : 1 }};

    function addRow() {
        const tbody = document.getElementById('materialsBody');
        const tr = document.createElement('tr');
        tr.className = 'material-row';
        tr.innerHTML = `
            <td><input type="text"   name="materials[${rowCount}][label]"      placeholder="e.g., Ribbon"></td>
            <td><input type="number" name="materials[${rowCount}][quantity]"   value="1"  min="1"  step="1"    oninput="recalcRow(this)"></td>
            <td><input type="number" name="materials[${rowCount}][unit_price]" value="0"  min="0"  step="0.01" oninput="recalcRow(this)"></td>
            <td><span class="subtotal-live">₱0.00</span></td>
            <td><button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Remove"><i class="fas fa-times"></i></button></td>
        `;
        tbody.appendChild(tr);
        rowCount++;
        updateTotal();
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.material-row');
        if (rows.length === 1) {
            // Clear the last row instead of removing
            const row = btn.closest('tr');
            row.querySelector('input[name*="label"]').value = '';
            row.querySelector('input[name*="quantity"]').value = '1';
            row.querySelector('input[name*="unit_price"]').value = '0';
            row.querySelector('.subtotal-live').textContent = '₱0.00';
        } else {
            btn.closest('tr').remove();
        }
        updateTotal();
    }

    function recalcRow(input) {
        const row  = input.closest('tr');
        const qty  = parseFloat(row.querySelector('input[name*="quantity"]').value)   || 0;
        const up   = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
        const sub  = qty * up;
        row.querySelector('.subtotal-live').textContent = '₱' + sub.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.material-row').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="quantity"]')?.value)   || 0;
            const up  = parseFloat(row.querySelector('input[name*="unit_price"]')?.value) || 0;
            total += qty * up;
        });
        const formatted = '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('grandTotal').textContent       = formatted;
        document.getElementById('liveTotalDisplay').textContent = formatted;
    }

    // ── Image preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('imgPreview').src = e.target.result;
                document.getElementById('previewWrap').style.display = 'block';
                document.getElementById('uploadZone').style.display  = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview() {
        document.getElementById('custom_image').value     = '';
        document.getElementById('previewWrap').style.display  = 'none';
        document.getElementById('uploadZone').style.display   = 'block';
    }

    // ── Submit loading state
    document.getElementById('editForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
    });

    // ── Init totals on load
    document.querySelectorAll('.material-row').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name*="quantity"]')?.value)   || 0;
        const up  = parseFloat(row.querySelector('input[name*="unit_price"]')?.value) || 0;
        const sub = qty * up;
        const span = row.querySelector('.subtotal-live');
        if (span) span.textContent = '₱' + sub.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    });
    updateTotal();
</script>

</body>
</html>