<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Details - {{ $customization->customization_name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            padding: 30px 40px;
        }
        .header h1 { font-size: 2em; margin-bottom: 10px; }

        .status-badge {
            display: inline-block; padding: 5px 15px; border-radius: 20px;
            font-size: 0.9em; font-weight: 600; margin-top: 10px;
        }
        .status-pending   { background: #ffd93d; color: #333; }
        .status-approved  { background: #6bcf7f; color: white; }
        .status-rejected  { background: #fc5c65; color: white; }
        .status-completed { background: #26de81; color: white; }

        .content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; padding: 40px; }

        .section { background: #f8f9fa; padding: 25px; border-radius: 15px; margin-bottom: 20px; }
        .section h3 { color: #FF6B9D; margin-bottom: 15px; font-size: 1.3em; display: flex; align-items: center; gap: 8px; }

        .canvas-preview {
            width: 100%; aspect-ratio: 1; border-radius: 15px; overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 20px;
        }
        .canvas-preview img { width: 100%; height: 100%; object-fit: cover; }

        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #555; }
        .info-value { color: #333; }

        /* ── Price Breakdown ── */
        .breakdown-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .breakdown-header {
            padding: 18px 22px;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .breakdown-header.approved { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        .breakdown-header.pending  { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .breakdown-header.rejected { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }

        .breakdown-table { width: 100%; border-collapse: collapse; }
        .breakdown-table thead tr { background: #f1f5f9; }
        .breakdown-table th { padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
        .breakdown-table th:last-child { text-align: right; }
        .breakdown-table td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
        .breakdown-table td:last-child { text-align: right; font-weight: 600; color: #1e293b; }
        .breakdown-table tbody tr:hover { background: #fafafa; }
        .breakdown-total-row { background: linear-gradient(135deg, #f0fdf4, #dcfce7) !important; }
        .breakdown-total-row td { padding: 16px; font-weight: 800 !important; color: #15803d !important; font-size: 1.05rem !important; border-bottom: none !important; }
        .breakdown-total-row td:last-child { font-size: 1.25rem !important; }

        /* Customer materials (submitted estimate) */
        .customer-estimate-card {
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 20px;
        }
        .customer-estimate-header {
            padding: 16px 22px; background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; font-weight: 700; font-size: 1rem;
            display: flex; align-items: center; gap: 10px;
        }
        .customer-estimate-table { width: 100%; border-collapse: collapse; }
        .customer-estimate-table th { padding: 11px 16px; background: #f8fafc; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .customer-estimate-table th:last-child { text-align: right; }
        .customer-estimate-table td { padding: 11px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .customer-estimate-table td:last-child { text-align: right; font-weight: 600; color: #667eea; }
        .customer-estimate-total { background: #f5f3ff; padding: 14px 16px; display: flex; justify-content: space-between; font-weight: 700; }
        .customer-estimate-total span:last-child { color: #667eea; font-size: 1.1rem; }

        /* Status banners */
        .status-banner {
            border-radius: 14px; padding: 20px 22px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 14px;
        }
        .status-banner i { font-size: 1.6rem; margin-top: 2px; flex-shrink: 0; }
        .status-banner.approved { background: #f0fdf4; border: 2px solid #22c55e; color: #15803d; }
        .status-banner.pending  { background: #fffbeb; border: 2px solid #f59e0b; color: #92400e; }
        .status-banner.rejected { background: #fef2f2; border: 2px solid #ef4444; color: #991b1b; }
        .status-banner h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: 5px; }
        .status-banner p  { font-size: 0.9rem; line-height: 1.5; }

        /* Admin notes */
        .admin-notes-box {
            background: #fffbeb; border: 2px solid #fbbf24; border-radius: 12px;
            padding: 16px 20px; margin-top: 12px;
        }
        .admin-notes-box .note-label { font-size: 12px; font-weight: 700; color: #92400e; text-transform: uppercase; margin-bottom: 6px; }
        .admin-notes-box p { color: #78350f; font-size: 14px; line-height: 1.6; }

        /* Action buttons */
        .btn {
            padding: 12px 25px; border: none; border-radius: 10px; font-size: 1em;
            font-weight: 600; cursor: pointer; transition: all 0.3s ease;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-right: 10px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .btn-primary { background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%); color: white; }
        .btn-secondary { background: #667eea; color: white; }
        .btn-danger { background: #fc5c65; color: white; }
        .btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        .btn-checkout { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-size: 1.05rem; padding: 15px 30px; }
        .action-buttons { margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; }

        @media (max-width: 968px) { .content { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('components.customization-navbar')

    <div class="container">
        <div class="header">
            <h1>{{ $customization->customization_name }}</h1>
            <span class="status-badge status-{{ strtolower($customization->status) }}">
                {{ $customization->status }}
            </span>
        </div>

        <div class="content">
            <!-- LEFT COLUMN -->
            <div>
                <!-- Design Preview -->
                <div class="section">
                    <h3><i class="fas fa-paint-brush"></i> Design Preview</h3>
                    <div class="canvas-preview">
                        @if($customization->custom_image)
                            <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" alt="Customization Preview">
                        @else
                            <img src="{{ asset('asset/images/' . $customization->product->image) }}" alt="{{ $customization->product->name }}">
                        @endif
                    </div>

                    <div class="info-row">
                        <span class="info-label">Category:</span>
                        <span class="info-value">{{ $customization->product->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference Product:</span>
                        <span class="info-value">{{ $customization->product->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Submitted:</span>
                        <span class="info-value">{{ $customization->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">{{ $customization->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($customization->order_id)
                    <div class="info-row">
                        <span class="info-label">Order ID:</span>
                        <span class="info-value">#{{ $customization->order_id }}</span>
                    </div>
                    @endif
                </div>

                <!-- Customer Submitted Materials -->
                @if($customization->options->where('option_type', 'material')->count() > 0)
                <div class="customer-estimate-card">
                    <div class="customer-estimate-header">
                        <i class="fas fa-list-alt"></i> Your Submitted Materials
                    </div>
                    <table class="customer-estimate-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $customerTotal = 0; @endphp
                            @foreach($customization->options->where('option_type', 'material') as $opt)
                                @php
                                    $data  = json_decode($opt->option_value, true);
                                    $sub   = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? $opt->additional_price);
                                    $customerTotal += $sub;
                                @endphp
                                <tr>
                                    <td>{{ $data['label'] ?? $opt->option_value }}</td>
                                    <td>{{ $data['quantity'] ?? 1 }}</td>
                                    <td>₱{{ number_format($data['unit_price'] ?? $opt->additional_price, 2) }}</td>
                                    <td>₱{{ number_format($sub, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="customer-estimate-total">
                        <span>Your Estimated Total</span>
                        <span>₱{{ number_format($customerTotal, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT COLUMN -->
            <div>
                <!-- Customization Details -->
                <div class="section">
                    <h3><i class="fas fa-file-alt"></i> Customization Details</h3>
                    <div style="margin-bottom:16px;">
                        <p style="font-weight:600;color:#555;margin-bottom:8px;">Description:</p>
                        <p style="color:#333;line-height:1.7;white-space:pre-line;">{{ $customization->customization_details }}</p>
                    </div>
                    @if($customization->special_instructions)
                    <div>
                        <p style="font-weight:600;color:#555;margin-bottom:8px;">Special Instructions:</p>
                        <p style="color:#333;line-height:1.7;white-space:pre-line;">{{ $customization->special_instructions }}</p>
                    </div>
                    @endif
                </div>

                <!-- ── STATUS SECTION ── -->

                @if($customization->isApproved())
                    <!-- APPROVED: show official price breakdown -->
                    <div class="status-banner approved">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h4>Request Approved! 🎉</h4>
                            <p>Our florists have reviewed your request and provided an official price breakdown below. You can now add it to your cart.</p>
                        </div>
                    </div>

                    @if($customization->price_breakdown && count($customization->price_breakdown) > 0)
                    <div class="breakdown-card">
                        <div class="breakdown-header approved">
                            <i class="fas fa-receipt"></i> Official Price Breakdown
                        </div>
                        <table class="breakdown-table">
                            <thead>
                                <tr>
                                    <th>Item / Material</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
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
                                <tr class="breakdown-total-row">
                                    <td colspan="3">Total Amount</td>
                                    <td>{{ $customization->formatted_price }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <!-- Approved but no breakdown (simple price) -->
                    <div style="background:#f0fdf4;border:2px solid #22c55e;border-radius:14px;padding:20px;margin-bottom:20px;text-align:center;">
                        <p style="font-size:0.9rem;color:#15803d;margin-bottom:6px;">Final Price</p>
                        <p style="font-size:2rem;font-weight:800;color:#15803d;">{{ $customization->formatted_price }}</p>
                    </div>
                    @endif

                    @if($customization->admin_notes)
                    <div class="admin-notes-box">
                        <div class="note-label"><i class="fas fa-comment"></i> Florist Note</div>
                        <p>{{ $customization->admin_notes }}</p>
                    </div>
                    @endif

                    @if($customization->canCheckout())
                    <div class="action-buttons" style="margin-top:20px;">
                        <a href="{{ route('customization.add-to-cart', $customization->id) }}" class="btn btn-success">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </a>
                        <a href="{{ route('customization.proceed-checkout', $customization->id) }}" class="btn btn-checkout">
                            <i class="fas fa-bolt"></i> Checkout Now
                        </a>
                    </div>
                    @elseif($customization->order_id)
                    <div style="background:#f0fdf4;border-radius:10px;padding:14px;margin-top:10px;color:#15803d;font-weight:600;">
                        <i class="fas fa-check-double"></i> This customization has been ordered (#{{ $customization->order_id }})
                    </div>
                    @endif

                @elseif($customization->isRejected())
                    <div class="status-banner rejected">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <h4>Request Not Approved</h4>
                            <p>Unfortunately we're unable to fulfill this request as submitted.
                            @if($customization->admin_notes) See the note below for details. @endif</p>
                        </div>
                    </div>
                    @if($customization->admin_notes)
                    <div class="admin-notes-box">
                        <div class="note-label"><i class="fas fa-comment"></i> Florist Note</div>
                        <p>{{ $customization->admin_notes }}</p>
                    </div>
                    @endif

                @elseif($customization->isCompleted())
                    <div class="status-banner approved">
                        <i class="fas fa-check-double"></i>
                        <div>
                            <h4>Order Completed!</h4>
                            <p>Your custom bouquet has been completed. Thank you for choosing Hookcraft Avenue!</p>
                        </div>
                    </div>
                    @if($customization->price_breakdown && count($customization->price_breakdown) > 0)
                    <div class="breakdown-card">
                        <div class="breakdown-header approved">
                            <i class="fas fa-receipt"></i> Final Price Breakdown
                        </div>
                        <table class="breakdown-table">
                            <thead>
                                <tr><th>Item / Material</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
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
                                <tr class="breakdown-total-row">
                                    <td colspan="3">Total Paid</td>
                                    <td>{{ $customization->formatted_price }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif

                @else
                    <!-- PENDING -->
                    <div class="status-banner pending">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>⏳ Under Review</h4>
                            <p>Our florists are reviewing your request. We'll provide an official price quote within 24 hours.</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons" style="margin-top:24px;">
                    @if($customization->canEdit())
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
                    @endif
                    <a href="{{ route('customization.my-customizations') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to My Customizations
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>