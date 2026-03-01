@extends('admin.layouts.admin')

@section('title', 'Customization Details')

@push('styles')
<style>
/* ── Base ── */
.cust-detail { font-family: 'Inter', -apple-system, sans-serif; }

.detail-card {
    background: var(--card-bg);
    border-radius: 16px; padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.detail-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 2rem; padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}
.detail-title { font-size: 22px; font-weight: 700; color: var(--text-primary); }

.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;
}
.status-badge.pending   { background: #fef3c7; color: #d97706; }
.status-badge.approved  { background: #d1fae5; color: #059669; }
.status-badge.rejected  { background: #fee2e2; color: #dc2626; }
.status-badge.completed { background: #cffafe; color: #0891b2; }

.detail-row   { margin-bottom: 1.5rem; }
.detail-label { font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.5rem; }
.detail-value { font-size: 14px; color: var(--text-primary); line-height: 1.6; }

.customer-info { display: flex; align-items: center; gap: 1rem; }
.customer-avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: linear-gradient(135deg, #ec4899, #be185d); color: white;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 16px; flex-shrink: 0;
}
.customer-name  { font-weight: 600; font-size: 15px; color: var(--text-primary); }
.customer-email { font-size: 13px; color: var(--text-secondary); }

.product-display { display: flex; align-items: center; gap: 1rem; }
.product-thumb { width: 72px; height: 72px; border-radius: 12px; object-fit: cover; border: 2px solid var(--border-color); }
.product-name  { font-weight: 600; font-size: 15px; color: var(--text-primary); }
.product-price { font-size: 17px; font-weight: 700; color: #10b981; margin-top: 4px; }

.detail-text-box {
    background: var(--light-bg); padding: 1rem; border-radius: 12px;
    border: 1px solid var(--border-color); white-space: pre-wrap; font-size: 14px;
}
.image-preview { max-width: 100%; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

/* ── Customer Materials ── */
.customer-materials-card {
    border: 2px solid #8b5cf6; border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem;
}
.customer-materials-header {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white; padding: 13px 18px; font-weight: 700; font-size: 14px;
    display: flex; align-items: center; gap: 8px;
}
.cm-table { width: 100%; border-collapse: collapse; }
.cm-table th { padding: 9px 14px; background: #f5f3ff; font-size: 11px; font-weight: 700; color: #6d28d9; text-transform: uppercase; text-align: left; }
.cm-table th:last-child { text-align: right; }
.cm-table td { padding: 10px 14px; border-bottom: 1px solid #ede9fe; font-size: 13px; color: var(--text-primary); }
.cm-table td:last-child { text-align: right; font-weight: 600; color: #6d28d9; }
.cm-table tfoot td { background: #ede9fe; font-weight: 700; color: #6d28d9; padding: 11px 14px; }
.cm-table tfoot td:last-child { text-align: right; }

/* (populate banner removed — admin starts fresh) */

/* ── Admin review form ── */
.form-group  { margin-bottom: 1.4rem; }
.form-label  { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
.form-label.required::after { content: ' *'; color: #ef4444; }
.form-control {
    width: 100%; padding: 11px 14px;
    border: 2px solid var(--border-color); border-radius: 12px;
    font-size: 13px; background: var(--card-bg); color: var(--text-primary);
    transition: all 0.2s; font-family: inherit;
}
.form-control:focus { outline: none; border-color: #ec4899; box-shadow: 0 0 0 3px rgba(236,72,153,0.1); }
.form-help { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

/* ── Breakdown builder ── */
.breakdown-section { margin-top: 4px; }
.breakdown-title {
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: #be185d; margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}

.breakdown-table-wrap {
    border: 1.5px solid #fce7f3; border-radius: 12px; overflow: hidden; margin-bottom: 10px;
}
.bd-table { width: 100%; border-collapse: collapse; }
.bd-table thead tr { background: linear-gradient(135deg, #ec4899, #be185d); }
.bd-table th { padding: 9px 10px; font-size: 11px; font-weight: 600; color: white; text-align: left; }
.bd-table th:first-child { border-radius: 0; }
.bd-table td { padding: 7px 6px; border-bottom: 1px solid #fce7f3; background: white; }
.bd-table tbody tr:last-child td { border-bottom: none; }

/* Static (read-only) cells */
.bd-static-cell {
    font-size: 13px; color: var(--text-primary); padding: 9px 10px !important;
    font-weight: 500;
}
.bd-qty { color: var(--text-secondary); font-weight: 600; text-align: center; }

.bd-input {
    width: 100%; padding: 7px 9px; border: 1.5px solid #f9a8d4; border-radius: 8px;
    font-size: 12px; font-family: inherit; background: white; color: var(--text-primary);
    transition: all 0.2s;
}
.bd-input:focus { outline: none; border-color: #ec4899; box-shadow: 0 0 0 2px rgba(236,72,153,0.1); }

.bd-subtotal { font-weight: 700; font-size: 13px; color: #be185d; padding-left: 10px !important; }

.grand-total-box {
    background: linear-gradient(135deg, #fdf2f8, #fce7f3);
    border: 1.5px solid #f9a8d4; border-radius: 12px;
    padding: 14px 16px; margin-top: 12px;
    display: flex; justify-content: space-between; align-items: center;
}
.grand-total-label { font-size: 13px; font-weight: 700; color: #be185d; display: flex; align-items: center; gap: 7px; }
.grand-total-value { font-size: 1.35rem; font-weight: 800; color: #be185d; }

/* ── Buttons ── */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 600;
    border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; width: 100%;
    font-family: inherit;
}
.btn-primary  { background: linear-gradient(135deg, #ec4899, #be185d); color: white; box-shadow: 0 3px 10px rgba(236,72,153,0.25); }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(236,72,153,0.35); }
.btn-danger   { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
.btn-secondary { background: var(--light-bg); color: var(--text-primary); border: 2px solid var(--border-color); }
.btn-secondary:hover { background: var(--hover-bg); }

/* ── Alerts ── */
.alert { display: flex; align-items: flex-start; gap: 10px; padding: 14px 16px; border-radius: 12px; margin-bottom: 1.5rem; font-size: 13px; }
.alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border-left: 4px solid #10b981; }
.alert-danger  { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border-left: 4px solid #ef4444; }
.alert i { font-size: 16px; margin-top: 1px; flex-shrink: 0; }

.price-summary {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 12px; padding: 1.2rem; margin-top: 1rem;
    display: flex; justify-content: space-between; align-items: center;
}
.price-summary-label { font-size: 13px; font-weight: 600; color: #92400e; }
.price-summary-value { font-size: 1.3rem; font-weight: 800; color: #92400e; }

.admin-notes-display {
    background: #e0f2fe; border-left: 4px solid #0ea5e9;
    padding: 12px 14px; border-radius: 10px; font-size: 13px; color: #075985;
    display: flex; gap: 8px; align-items: flex-start;
}
</style>
@endpush

@section('content')
<div class="cust-detail">

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i><div><strong>Success!</strong> {{ session('success') }}</div></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i><div><strong>Error!</strong> {{ session('error') }}</div></div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin:6px 0 0 16px;padding:0;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- ═══════════════════════════════════
             LEFT — Customization Details
        ═══════════════════════════════════ -->
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="detail-header">
                    <h2 class="detail-title">Customization Request #{{ $customization->id }}</h2>
                    <span class="status-badge {{ strtolower($customization->status) }}">
                        @if($customization->status === 'Pending')   <i class="fas fa-clock"></i>
                        @elseif($customization->status === 'Approved') <i class="fas fa-check-circle"></i>
                        @elseif($customization->status === 'Rejected') <i class="fas fa-times-circle"></i>
                        @else <i class="fas fa-check-double"></i>
                        @endif
                        {{ $customization->status }}
                    </span>
                </div>

                <!-- Customer -->
                <div class="detail-row">
                    <div class="detail-label">Customer</div>
                    <div class="customer-info">
                        <div class="customer-avatar">{{ substr($customization->user->name ?? 'N', 0, 1) }}</div>
                        <div>
                            <div class="customer-name">{{ $customization->user->name ?? 'Unknown' }}</div>
                            <div class="customer-email">{{ $customization->user->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <hr>

                <!-- Product -->
                @if($customization->product)
                <div class="detail-row">
                    <div class="detail-label">Base Product</div>
                    <div class="product-display">
                        <img src="{{ asset('asset/images/' . $customization->product->image) }}"
                             alt="{{ $customization->product->name }}" class="product-thumb">
                        <div>
                            <div class="product-name">{{ $customization->product->name }}</div>
                            <div class="product-price">₱{{ number_format($customization->product->price, 2) }}</div>
                        </div>
                    </div>
                </div>
                <hr>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Customization Name</div>
                    <div class="detail-value">{{ $customization->customization_name }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Customization Details</div>
                    <div class="detail-text-box">{{ $customization->customization_details }}</div>
                </div>

                @if($customization->special_instructions)
                <div class="detail-row">
                    <div class="detail-label">Special Instructions</div>
                    <div class="detail-text-box">{{ $customization->special_instructions }}</div>
                </div>
                @endif

              

                <!-- Customer submitted materials -->
                @if($customization->options->where('option_type', 'material')->count() > 0)
                <div class="detail-row">
                    <div class="detail-label">Customer's Submitted Materials</div>
                    <div class="customer-materials-card">
                        <div class="customer-materials-header">
                            <i class="fas fa-list-alt"></i> Materials Selected by Customer
                        </div>
                        <table class="cm-table">
                            <thead>
                                <tr><th>Material</th><th>Qty</th><th>Est. Unit Price</th><th>Est. Subtotal</th></tr>
                            </thead>
                            <tbody>
                                @php $custTotal = 0; @endphp
                                @foreach($customization->options->where('option_type', 'material') as $opt)
                                    @php
                                        $d   = json_decode($opt->option_value, true);
                                        $sub = ($d['quantity'] ?? 1) * ($d['unit_price'] ?? $opt->additional_price);
                                        $custTotal += $sub;
                                    @endphp
                                    <tr>
                                        <td>{{ $d['label'] ?? $opt->option_value }}</td>
                                        <td>{{ $d['quantity'] ?? 1 }}</td>
                                        <td>₱{{ number_format($d['unit_price'] ?? $opt->additional_price, 2) }}</td>
                                        <td>₱{{ number_format($sub, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align:right;font-size:12px;">Customer's Estimate</td>
                                    <td>₱{{ number_format($custTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif
                  @if($customization->custom_image)
                <div class="detail-row">
                    <div class="detail-label">Reference Image</div>
                    <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}"
                         alt="Custom Image" class="image-preview">
                </div>
                @endif

                @if($customization->admin_notes)
                <div class="detail-row">
                    <div class="detail-label">Admin Notes</div>
                    <div class="admin-notes-display">
                        <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0;"></i>
                        <div>{{ $customization->admin_notes }}</div>
                    </div>
                </div>
                @endif

                <div class="detail-row" style="margin-bottom:0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Created</div>
                            <div class="detail-value">{{ $customization->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">{{ $customization->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════
             RIGHT — Admin Review Panel
        ═══════════════════════════════════ -->
        <div class="col-lg-4">
            <div class="detail-card">
                <h5 class="detail-title" style="font-size:17px;margin-bottom:1.5rem;">
                    <i class="fas fa-edit" style="color:#ec4899;"></i> Update Status & Pricing
                </h5>

                <form action="{{ route('admin.customizations.update', $customization->id) }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label required">Status</label>
                        <select name="status" class="form-control" id="statusSelect" required>
                            <option value="Pending"   {{ $customization->status === 'Pending'   ? 'selected' : '' }}>Pending Review</option>
                            <option value="Approved"  {{ $customization->status === 'Approved'  ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected"  {{ $customization->status === 'Rejected'  ? 'selected' : '' }}>Rejected</option>
                            <option value="Completed" {{ $customization->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Admin Notes -->
                    <div class="form-group">
                        <label class="form-label">
                            Admin Notes
                            <small style="font-weight:400;color:var(--text-secondary);">(visible to customer)</small>
                        </label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                                  placeholder="Add notes for the customer...">{{ old('admin_notes', $customization->admin_notes) }}</textarea>
                    </div>

                    <!-- ═══════════════════════════════════
                         PRICE BREAKDOWN SECTION
                         (visible when Approved / Completed)
                    ═══════════════════════════════════ -->
                    <div id="priceBreakdownSection" style="{{ in_array($customization->status, ['Approved','Completed']) ? '' : 'display:none;' }}">

                        <div class="breakdown-section">
                            <div class="breakdown-title">
                                <i class="fas fa-receipt" style="color:#ec4899;"></i>
                                Official Price Breakdown
                            </div>

                            {{-- Build the source rows: prefer saved price_breakdown, fallback to customer options --}}
                            @php
                                $bdRows = [];
                                if ($customization->price_breakdown && count($customization->price_breakdown) > 0) {
                                    foreach ($customization->price_breakdown as $row) {
                                        $bdRows[] = [
                                            'label'      => $row['label'],
                                            'quantity'   => $row['quantity'],
                                            'unit_price' => $row['unit_price'],
                                            'subtotal'   => $row['subtotal'],
                                        ];
                                    }
                                } else {
                                    foreach ($customization->options->where('option_type', 'material') as $opt) {
                                        $d = json_decode($opt->option_value, true);
                                        $qty   = $d['quantity']   ?? 1;
                                        $price = $d['unit_price'] ?? $opt->additional_price;
                                        $bdRows[] = [
                                            'label'      => $d['label'] ?? $opt->option_value,
                                            'quantity'   => $qty,
                                            'unit_price' => 0,   // admin fills this in
                                            'subtotal'   => 0,
                                        ];
                                    }
                                }
                            @endphp

                            <div class="breakdown-table-wrap">
                                <table class="bd-table">
                                    <thead>
                                        <tr>
                                            <th style="width:38%;">Item / Material</th>
                                            <th style="width:14%;">Qty</th>
                                            <th style="width:26%;">Unit Price (₱)</th>
                                            <th style="width:22%;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="breakdownBody">
                                        @foreach($bdRows as $i => $row)
                                        <tr data-qty="{{ $row['quantity'] }}">
                                            {{-- Static: label & qty --}}
                                            <input type="hidden" name="breakdown[{{ $i }}][label]"    value="{{ $row['label'] }}">
                                            <input type="hidden" name="breakdown[{{ $i }}][quantity]" value="{{ $row['quantity'] }}">

                                            <td class="bd-static-cell">{{ $row['label'] }}</td>
                                            <td class="bd-static-cell bd-qty">{{ $row['quantity'] }}</td>

                                            {{-- Editable: unit price only --}}
                                            <td>
                                                <input type="number"
                                                       name="breakdown[{{ $i }}][unit_price]"
                                                       class="bd-input price-bd"
                                                       value="{{ $row['unit_price'] > 0 ? $row['unit_price'] : '' }}"
                                                       min="0" step="0.01"
                                                       placeholder="0.00"
                                                       oninput="calcBdRow(this)"
                                                       required>
                                            </td>

                                            {{-- Auto-calculated subtotal --}}
                                            <td class="bd-subtotal">
                                                ₱{{ $row['subtotal'] > 0 ? number_format($row['subtotal'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if(empty($bdRows))
                            <p style="font-size:12px;color:var(--text-secondary);padding:10px 0;">
                                No materials were submitted by the customer.
                            </p>
                            @endif

                            <div class="grand-total-box">
                                <span class="grand-total-label"><i class="fas fa-calculator"></i> Grand Total</span>
                                <span class="grand-total-value" id="bdGrandTotal">
                                    ₱{{ number_format(collect($bdRows)->sum('subtotal'), 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Final Price -->
                        <div class="form-group" style="margin-top:14px;">
                            <label class="form-label required" id="priceLabel">Final Price for Customer (₱)</label>
                            <input type="number" name="admin_price" id="adminPriceInput"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('admin_price', $customization->admin_price) }}"
                                   placeholder="0.00">
                            <small class="form-help">Auto-filled from breakdown total. Edit to override.</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-bottom:1rem;">
                        <i class="fas fa-save"></i> Update Customization
                    </button>
                </form>

                <hr style="margin:1.2rem 0;">

                <form action="{{ route('admin.customizations.destroy', $customization->id) }}" method="POST"
                      onsubmit="return confirm('Delete this customization? This cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="margin-bottom:1rem;">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </form>

                @if($customization->admin_price)
                <div class="price-summary">
                    <span class="price-summary-label"><i class="fas fa-tag"></i> Current Set Price</span>
                    <span class="price-summary-value">₱{{ number_format($customization->admin_price, 2) }}</span>
                </div>
                @endif

                <a href="{{ route('admin.customizations.index') }}" class="btn btn-secondary" style="margin-top:1rem;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Show/hide breakdown section ──────────────────────────────────
const statusSelect = document.getElementById('statusSelect');
const priceSection = document.getElementById('priceBreakdownSection');
const priceLabel   = document.getElementById('priceLabel');
const priceInput   = document.getElementById('adminPriceInput');

function togglePriceSection() {
    const show = statusSelect.value === 'Approved' || statusSelect.value === 'Completed';
    priceSection.style.display = show ? '' : 'none';

    if (statusSelect.value === 'Approved') {
        priceLabel.classList.add('required');
        priceInput.setAttribute('required', 'required');
    } else {
        priceLabel.classList.remove('required');
        priceInput.removeAttribute('required');
    }
}

statusSelect.addEventListener('change', togglePriceSection);
document.addEventListener('DOMContentLoaded', togglePriceSection);

// ── Row calculation — qty is fixed (data-qty on <tr>) ────────────
function calcBdRow(input) {
    const row  = input.closest('tr');
    const qty  = parseFloat(row.dataset.qty)  || 0;
    const unit = parseFloat(input.value)       || 0;
    row.querySelector('.bd-subtotal').textContent = '₱' + (qty * unit).toFixed(2);
    updateBdTotal();
}

function updateBdTotal() {
    let total = 0;
    document.querySelectorAll('#breakdownBody .bd-subtotal').forEach(cell => {
        total += parseFloat(cell.textContent.replace('₱', '')) || 0;
    });
    document.getElementById('bdGrandTotal').textContent = '₱' + total.toFixed(2);
    document.getElementById('adminPriceInput').value    = total.toFixed(2);
}
</script>
@endpush