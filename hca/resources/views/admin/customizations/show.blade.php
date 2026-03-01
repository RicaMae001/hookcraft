@extends('admin.layouts.admin')

@section('title', 'Customization Details')

@push('styles')
<style>
.customization-detail-container { font-family: 'Inter', -apple-system; }

.detail-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.detail-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);
}
.detail-title { font-size: 24px; font-weight: 700; color: var(--text-primary); }

.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;
}
.status-badge.pending   { background: #fef3c7; color: #d97706; }
.status-badge.approved  { background: #d1fae5; color: #059669; }
.status-badge.rejected  { background: #fee2e2; color: #dc2626; }
.status-badge.completed { background: #cffafe; color: #0891b2; }

.detail-row { margin-bottom: 1.5rem; }
.detail-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem; }
.detail-value { font-size: 15px; color: var(--text-primary); line-height: 1.6; }

.customer-info { display: flex; align-items: center; gap: 1rem; }
.customer-avatar {
    width: 50px; height: 50px; border-radius: 50%;
    background: linear-gradient(135deg, #ec4899, #be185d); color: white;
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px;
}
.customer-name  { font-weight: 600; font-size: 16px; color: var(--text-primary); }
.customer-email { font-size: 14px; color: var(--text-secondary); }

.product-display { display: flex; align-items: center; gap: 1rem; }
.product-thumbnail { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid var(--border-color); }
.product-info { flex: 1; }
.product-name  { font-weight: 600; font-size: 16px; color: var(--text-primary); }
.product-price { font-size: 18px; font-weight: 700; color: #10b981; margin-top: 4px; }

.detail-text-box {
    background: var(--light-bg); padding: 1rem; border-radius: 12px;
    border: 1px solid var(--border-color); white-space: pre-wrap;
}
.image-preview { max-width: 100%; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
.form-label.required::after { content: ' *'; color: #ef4444; }
.form-control {
    width: 100%; padding: 12px 16px; border: 2px solid var(--border-color); border-radius: 12px;
    font-size: 14px; transition: all 0.2s; background: var(--card-bg); color: var(--text-primary);
}
.form-control:focus { outline: none; border-color: #ec4899; box-shadow: 0 0 0 4px rgba(236,72,153,0.1); }
.form-help { font-size: 12px; color: var(--text-secondary); margin-top: 0.25rem; }

.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 600;
    border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; width: 100%;
}
.btn-primary  { background: linear-gradient(135deg, #ec4899, #be185d); color: white; box-shadow: 0 4px 12px rgba(236,72,153,0.3); }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(236,72,153,0.4); }
.btn-danger   { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
.btn-secondary { background: var(--light-bg); color: var(--text-primary); border: 2px solid var(--border-color); }
.btn-secondary:hover { background: var(--hover-bg); }

.alert { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; }
.alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border-left: 4px solid #10b981; }
.alert-danger  { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border-left: 4px solid #ef4444; }
.alert i { font-size: 20px; }

/* ── Customer Materials submitted ── */
.customer-materials-section {
    background: linear-gradient(135deg, #f5f3ff, #ede9fe);
    border: 2px solid #8b5cf6;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.customer-materials-header {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white; padding: 14px 20px; font-weight: 700; font-size: 0.95rem;
    display: flex; align-items: center; gap: 10px;
}
.customer-materials-table { width: 100%; border-collapse: collapse; }
.customer-materials-table th { padding: 10px 16px; background: #f5f3ff; font-size: 12px; font-weight: 700; color: #6d28d9; text-transform: uppercase; }
.customer-materials-table td { padding: 10px 16px; border-bottom: 1px solid #ede9fe; font-size: 13px; }
.customer-materials-table tfoot td { padding: 12px 16px; background: #ede9fe; font-weight: 700; color: #6d28d9; }

/* ── Admin Price Breakdown Builder ── */
.breakdown-builder {
    background: linear-gradient(135deg, #fdf2f8, #fce7f3);
    border: 2px solid #ec4899;
    border-radius: 14px;
    padding: 20px;
    margin-top: 1rem;
}
.breakdown-builder-title {
    font-weight: 700; font-size: 1rem; color: #be185d;
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}
.breakdown-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
.breakdown-table thead tr { background: linear-gradient(135deg, #ec4899, #be185d); color: white; }
.breakdown-table th { padding: 10px 12px; font-size: 12px; font-weight: 600; text-align: left; }
.breakdown-table th:first-child { border-radius: 8px 0 0 8px; }
.breakdown-table th:last-child  { border-radius: 0 8px 8px 0; }
.breakdown-table td { padding: 8px 6px; border-bottom: 1px solid #fce7f3; }
.breakdown-table tfoot td { padding: 12px 8px; background: #fdf2f8; }

.bd-input {
    width: 100%; padding: 8px 10px; border: 2px solid #f9a8d4; border-radius: 8px;
    font-size: 13px; font-family: inherit; background: white; transition: all 0.2s;
}
.bd-input:focus { outline: none; border-color: #ec4899; box-shadow: 0 0 0 3px rgba(236,72,153,0.1); }

.bd-remove-btn {
    width: 30px; height: 30px; border-radius: 7px; border: none;
    background: #fee2e2; color: #dc2626; cursor: pointer; font-size: 13px;
    display: flex; align-items: center; justify-content: center; transition: all 0.2s; margin: auto;
}
.bd-remove-btn:hover { background: #dc2626; color: white; }

.add-row-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; background: white; border: 2px dashed #ec4899;
    border-radius: 9px; color: #ec4899; font-weight: 600; font-size: 13px;
    cursor: pointer; transition: all 0.2s;
}
.add-row-btn:hover { background: #ec4899; color: white; }

.grand-total-display {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 12px; padding: 14px 18px; margin-top: 14px;
    display: flex; justify-content: space-between; align-items: center;
}
.grand-total-label { font-weight: 700; color: #92400e; font-size: 14px; }
.grand-total-value { font-weight: 800; color: #92400e; font-size: 1.4rem; }

.price-summary {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem;
}
.price-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.price-label { font-size: 14px; font-weight: 600; color: #92400e; }
.price-value { font-size: 20px; font-weight: 700; color: #92400e; }
</style>
@endpush

@section('content')
<div class="customization-detail-container">

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
            <ul style="margin:0.5rem 0 0 1.5rem;padding:0;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- ── LEFT: Customization Details ── -->
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
                             alt="{{ $customization->product->name }}" class="product-thumbnail">
                        <div class="product-info">
                            <div class="product-name">{{ $customization->product->name }}</div>
                            <div class="product-price">₱{{ number_format($customization->product->price, 2) }}</div>
                        </div>
                    </div>
                </div>
                <hr>
                @endif

                <!-- Name -->
                <div class="detail-row">
                    <div class="detail-label">Customization Name</div>
                    <div class="detail-value">{{ $customization->customization_name }}</div>
                </div>

                <!-- Details -->
                <div class="detail-row">
                    <div class="detail-label">Customization Details</div>
                    <div class="detail-text-box">{{ $customization->customization_details }}</div>
                </div>

                <!-- Special Instructions -->
                @if($customization->special_instructions)
                <div class="detail-row">
                    <div class="detail-label">Special Instructions</div>
                    <div class="detail-text-box">{{ $customization->special_instructions }}</div>
                </div>
                @endif

                <!-- Reference Image -->
                @if($customization->custom_image)
                <div class="detail-row">
                    <div class="detail-label">Reference Image</div>
                    <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}"
                         alt="Custom Image" class="image-preview">
                </div>
                @endif

                <!-- Customer Submitted Materials -->
                @if($customization->options->where('option_type', 'material')->count() > 0)
                <div class="detail-row">
                    <div class="detail-label">Customer's Expected Materials</div>
                    <div class="customer-materials-section">
                        <div class="customer-materials-header">
                            <i class="fas fa-list-alt"></i> Materials Submitted by Customer
                        </div>
                        <table class="customer-materials-table">
                            <thead>
                                <tr>
                                    <th>Material</th><th>Qty</th><th>Est. Unit Price</th><th>Est. Subtotal</th>
                                </tr>
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
                                    <td colspan="3" style="text-align:right;">Customer's Estimate</td>
                                    <td>₱{{ number_format($custTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($customization->admin_notes)
                <div class="detail-row">
                    <div class="detail-label">Admin Notes</div>
                    <div class="alert" style="margin:0;background:#e0f2fe;color:#075985;border-left:4px solid #0ea5e9;">
                        <i class="fas fa-info-circle"></i>
                        <div>{{ $customization->admin_notes }}</div>
                    </div>
                </div>
                @endif

                <!-- Timestamps -->
                <div class="detail-row">
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

        <!-- ── RIGHT: Admin Review Form ── -->
        <div class="col-lg-4">
            <div class="detail-card">
                <h5 class="detail-title" style="font-size:18px;margin-bottom:1.5rem;">
                    <i class="fas fa-edit"></i> Update Status & Pricing
                </h5>

                <form action="{{ route('admin.customizations.update', $customization->id) }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label required">Status</label>
                        <select name="status" class="form-control" required id="statusSelect">
                            <option value="Pending"   {{ $customization->status === 'Pending'   ? 'selected' : '' }}>Pending Review</option>
                            <option value="Approved"  {{ $customization->status === 'Approved'  ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected"  {{ $customization->status === 'Rejected'  ? 'selected' : '' }}>Rejected</option>
                            <option value="Completed" {{ $customization->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Admin Notes -->
                    <div class="form-group">
                        <label class="form-label">Admin Notes <small style="font-weight:400;color:#94a3b8;">(visible to customer)</small></label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                                  placeholder="Add notes for the customer...">{{ old('admin_notes', $customization->admin_notes) }}</textarea>
                    </div>

                    <!-- ── PRICE BREAKDOWN BUILDER (shown only when Approved) ── -->
                    <div id="priceBreakdownSection" style="{{ $customization->status === 'Approved' || $customization->status === 'Completed' ? '' : 'display:none' }}">

                        <div class="breakdown-builder">
                            <div class="breakdown-builder-title">
                                <i class="fas fa-receipt"></i> Official Price Breakdown
                            </div>
                            <p style="font-size:13px;color:#9d174d;margin-bottom:14px;">
                                Add each material/labor item. The grand total auto-fills the final price field.
                            </p>

                            <table class="breakdown-table" id="breakdownTable">
                                <thead>
                                    <tr>
                                        <th style="width:38%">Item / Material</th>
                                        <th style="width:15%">Qty</th>
                                        <th style="width:22%">Unit (₱)</th>
                                        <th style="width:18%">Subtotal</th>
                                        <th style="width:7%"></th>
                                    </tr>
                                </thead>
                                <tbody id="breakdownBody">
                                    @if($customization->price_breakdown && count($customization->price_breakdown) > 0)
                                        @foreach($customization->price_breakdown as $i => $row)
                                        <tr>
                                            <td><input type="text" name="breakdown[{{ $i }}][label]" class="bd-input"
                                                       value="{{ $row['label'] }}" placeholder="e.g. Red Roses" required></td>
                                            <td><input type="number" name="breakdown[{{ $i }}][quantity]" class="bd-input qty-bd"
                                                       value="{{ $row['quantity'] }}" min="0" step="0.01" oninput="calcBdRow(this)" required></td>
                                            <td><input type="number" name="breakdown[{{ $i }}][unit_price]" class="bd-input price-bd"
                                                       value="{{ $row['unit_price'] }}" min="0" step="0.01" oninput="calcBdRow(this)" required></td>
                                            <td class="bd-subtotal" style="font-weight:700;font-size:13px;color:#be185d;padding-left:8px;">
                                                ₱{{ number_format($row['subtotal'], 2) }}
                                            </td>
                                            <td><button type="button" class="bd-remove-btn" onclick="removeBdRow(this)"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <button type="button" class="add-row-btn" onclick="addBdRow()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>

                            <div class="grand-total-display">
                                <span class="grand-total-label"><i class="fas fa-calculator"></i> Grand Total</span>
                                <span class="grand-total-value" id="bdGrandTotal">
                                    ₱{{ $customization->price_breakdown
                                        ? number_format(collect($customization->price_breakdown)->sum('subtotal'), 2)
                                        : '0.00' }}
                                </span>
                            </div>
                        </div>

                        <!-- Final Price -->
                        <div class="form-group" style="margin-top:16px;">
                            <label class="form-label required" id="priceLabel">Final Price for Customer (₱)</label>
                            <input type="number" name="admin_price" id="adminPriceInput"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('admin_price', $customization->admin_price) }}"
                                   placeholder="0.00">
                            <small class="form-help">Auto-filled from breakdown total. Edit to override.</small>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Customization
                    </button>
                </form>

                <hr style="margin:1.5rem 0;">

                <!-- Delete -->
                <form action="{{ route('admin.customizations.destroy', $customization->id) }}" method="POST"
                      onsubmit="return confirm('Delete this customization? This cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </form>

                <!-- Current Price Summary -->
                @if($customization->admin_price)
                <div class="price-summary" style="margin-top:1rem;">
                    <div class="price-row">
                        <span class="price-label"><i class="fas fa-star"></i> Current Set Price</span>
                        <span class="price-value">₱{{ number_format($customization->admin_price, 2) }}</span>
                    </div>
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
document.addEventListener('DOMContentLoaded', function () {

    // Show/hide breakdown section on status change
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
            // Add blank row if table is empty
            if (document.querySelectorAll('#breakdownBody tr').length === 0) addBdRow();
        } else {
            priceLabel.classList.remove('required');
            priceInput.removeAttribute('required');
        }
    }

    statusSelect.addEventListener('change', togglePriceSection);
    togglePriceSection();
});

// ── Breakdown row management ───────────────────────────────
let bdIndex = {{ $customization->price_breakdown ? count($customization->price_breakdown) : 0 }};

function addBdRow() {
    const i  = bdIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="breakdown[${i}][label]" class="bd-input" placeholder="e.g. White Lilies" required></td>
        <td><input type="number" name="breakdown[${i}][quantity]" class="bd-input qty-bd" value="1" min="0" step="0.01" oninput="calcBdRow(this)" required></td>
        <td><input type="number" name="breakdown[${i}][unit_price]" class="bd-input price-bd" value="0" min="0" step="0.01" oninput="calcBdRow(this)" required></td>
        <td class="bd-subtotal" style="font-weight:700;font-size:13px;color:#be185d;padding-left:8px;">₱0.00</td>
        <td><button type="button" class="bd-remove-btn" onclick="removeBdRow(this)"><i class="fas fa-times"></i></button></td>`;
    document.getElementById('breakdownBody').appendChild(tr);
}

function removeBdRow(btn) {
    btn.closest('tr').remove();
    updateBdTotal();
}

function calcBdRow(input) {
    const row  = input.closest('tr');
    const qty  = parseFloat(row.querySelector('.qty-bd').value)   || 0;
    const unit = parseFloat(row.querySelector('.price-bd').value) || 0;
    row.querySelector('.bd-subtotal').textContent = '₱' + (qty * unit).toFixed(2);
    updateBdTotal();
}

function updateBdTotal() {
    let total = 0;
    document.querySelectorAll('#breakdownBody .bd-subtotal').forEach(cell => {
        total += parseFloat(cell.textContent.replace('₱', '')) || 0;
    });
    document.getElementById('bdGrandTotal').textContent    = '₱' + total.toFixed(2);
    document.getElementById('adminPriceInput').value       = total.toFixed(2);
}
</script>
@endpush