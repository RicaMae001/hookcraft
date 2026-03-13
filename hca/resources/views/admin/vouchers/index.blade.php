@extends('admin.layouts.admin')

@section('title', 'Vouchers')

@section('content')

<div class="animate-fade-in">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--text-primary);margin:0;">
                <i class="fas fa-ticket-alt me-2" style="color:var(--primary-pink);"></i>Vouchers
            </h1>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Manage promo codes and discount vouchers</p>
        </div>
        <button class="btn-modern btn-modern-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> New Voucher
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background:rgba(72,187,120,0.12);border:1px solid rgba(72,187,120,0.3);color:var(--success);border-radius:12px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="background:rgba(252,129,129,0.12);border:1px solid rgba(252,129,129,0.3);color:var(--danger);border-radius:12px;">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon primary"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-card-value">{{ $vouchers->count() }}</div>
                <div class="stat-card-label">Total Vouchers</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon success"><i class="fas fa-check-circle"></i></div>
                <div class="stat-card-value">{{ $vouchers->where('is_active', true)->count() }}</div>
                <div class="stat-card-label">Active</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon warning"><i class="fas fa-pause-circle"></i></div>
                <div class="stat-card-value">{{ $vouchers->where('is_active', false)->count() }}</div>
                <div class="stat-card-label">Inactive</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon info"><i class="fas fa-chart-bar"></i></div>
                <div class="stat-card-value">{{ $vouchers->sum('used_count') }}</div>
                <div class="stat-card-label">Total Uses</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">All Vouchers</h2>
        </div>

        <div style="overflow-x:auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Discount</th>
                        <th>Min Order</th>
                        <th>Usage</th>
                        <th>Per User</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:var(--primary-pink);font-family:monospace;font-size:0.95rem;">
                                {{ $voucher->code }}
                            </div>
                            @if($voucher->description)
                                <div style="font-size:0.78rem;color:var(--text-secondary);margin-top:2px;">
                                    {{ $voucher->description }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-modern {{ $voucher->discount_type === 'percent' ? 'badge-info' : 'badge-warning' }}">
                                <i class="fas {{ $voucher->discount_type === 'percent' ? 'fa-percent' : 'fa-tag' }}"></i>
                                {{ ucfirst($voucher->discount_type) }}
                            </span>
                        </td>
                        <td style="font-weight:600;">
                            @if($voucher->discount_type === 'percent')
                                {{ $voucher->discount_value }}%
                                @if($voucher->max_discount_cap)
                                    <div style="font-size:0.75rem;color:var(--text-secondary);">
                                        cap ₱{{ number_format($voucher->max_discount_cap, 2) }}
                                    </div>
                                @endif
                            @else
                                ₱{{ number_format($voucher->discount_value, 2) }}
                            @endif
                        </td>
                        <td>₱{{ number_format($voucher->min_order_amount, 2) }}</td>
                        <td>
                            <div style="font-size:0.85rem;">
                                {{ $voucher->used_count }} / {{ $voucher->max_uses ?? '∞' }}
                            </div>
                            @if($voucher->max_uses)
                                <div style="margin-top:4px;background:var(--border-color);border-radius:4px;height:5px;width:80px;overflow:hidden;">
                                    <div style="height:100%;border-radius:4px;background:var(--primary-pink);width:{{ min(100, ($voucher->used_count / $voucher->max_uses) * 100) }}%;"></div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $voucher->max_uses_per_user }}×</td>
                        <td style="font-size:0.8rem;">
                            @if($voucher->starts_at)
                                <div>From: {{ $voucher->starts_at->format('M d, Y') }}</div>
                            @endif
                            @if($voucher->expires_at)
                                <div style="color:{{ $voucher->expires_at->isPast() ? 'var(--danger)' : 'var(--text-secondary)' }};">
                                    Exp: {{ $voucher->expires_at->format('M d, Y') }}
                                </div>
                            @else
                                <span style="color:var(--success);">No expiry</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-modern {{ $voucher->is_active ? 'badge-success' : 'badge-danger' }}">
                                <i class="fas {{ $voucher->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button onclick="openEditModal({{ $voucher->id }})"
                                        class="btn btn-sm"
                                        style="background:rgba(102,126,234,0.12);color:var(--secondary);border:none;border-radius:8px;padding:0.4rem 0.75rem;">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm"
                                            style="background:{{ $voucher->is_active ? 'rgba(246,173,85,0.12)' : 'rgba(72,187,120,0.12)' }};color:{{ $voucher->is_active ? 'var(--warning)' : 'var(--success)' }};border:none;border-radius:8px;padding:0.4rem 0.75rem;"
                                            title="{{ $voucher->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $voucher->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete voucher {{ $voucher->code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:rgba(252,129,129,0.12);color:var(--danger);border:none;border-radius:8px;padding:0.4rem 0.75rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Hidden data for edit modal --}}
                    <script>
                        window.voucherData = window.voucherData || {};
                        window.voucherData[{{ $voucher->id }}] = {
                            id:               {{ $voucher->id }},
                            code:             "{{ $voucher->code }}",
                            description:      "{{ addslashes($voucher->description ?? '') }}",
                            discount_type:    "{{ $voucher->discount_type }}",
                            discount_value:   {{ $voucher->discount_value }},
                            min_order_amount: {{ $voucher->min_order_amount }},
                            max_discount_cap: {{ $voucher->max_discount_cap ?? 'null' }},
                            max_uses:         {{ $voucher->max_uses ?? 'null' }},
                            max_uses_per_user:{{ $voucher->max_uses_per_user }},
                            is_active:        {{ $voucher->is_active ? 'true' : 'false' }},
                            starts_at:        "{{ $voucher->starts_at ? $voucher->starts_at->format('Y-m-d') : '' }}",
                            expires_at:       "{{ $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '' }}",
                        };
                    </script>

                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                            <i class="fas fa-ticket-alt fa-3x mb-3" style="display:block;opacity:0.3;"></i>
                            No vouchers yet. Create your first one!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── Create / Edit Modal ── --}}
<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">

            <div class="modal-header" style="background:linear-gradient(135deg,var(--primary-pink),#FF8AAE);color:white;border-radius:16px 16px 0 0;border:none;">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-ticket-alt me-2"></i>New Voucher
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="voucherForm" method="POST" action="{{ route('admin.vouchers.store') }}">
                @csrf
                <span id="methodField"></span>

                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Code --}}
                        <div class="col-md-6">
                            <label class="form-label fw-600">Voucher Code *</label>
                            <input type="text" name="code" id="fCode" class="form-control"
                                   placeholder="e.g. SAVE50" style="text-transform:uppercase;" required>
                        </div>

                        {{-- Description --}}
                        <div class="col-md-6">
                            <label class="form-label fw-600">Description</label>
                            <input type="text" name="description" id="fDescription" class="form-control"
                                   placeholder="Short description shown to customer">
                        </div>

                        {{-- Discount Type --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Discount Type *</label>
                            <select name="discount_type" id="fDiscountType" class="form-select" onchange="toggleCapField()" required>
                                <option value="fixed">Fixed (₱)</option>
                                <option value="percent">Percentage (%)</option>
                            </select>
                        </div>

                        {{-- Discount Value --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Discount Value *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="discountPrefix">₱</span>
                                <input type="number" name="discount_value" id="fDiscountValue" class="form-control"
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>

                        {{-- Max Discount Cap --}}
                        <div class="col-md-4" id="capField" style="display:none;">
                            <label class="form-label fw-600">Max Discount Cap (₱)</label>
                            <input type="number" name="max_discount_cap" id="fMaxCap" class="form-control"
                                   placeholder="e.g. 100" min="0" step="0.01">
                            <small style="color:var(--text-secondary);">Leave blank for no cap</small>
                        </div>

                        {{-- Min Order --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Min Order Amount (₱)</label>
                            <input type="number" name="min_order_amount" id="fMinOrder" class="form-control"
                                   placeholder="0.00" min="0" step="0.01" value="0">
                        </div>

                        {{-- Max Uses --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Total Usage Limit</label>
                            <input type="number" name="max_uses" id="fMaxUses" class="form-control"
                                   placeholder="Leave blank = unlimited" min="1">
                        </div>

                        {{-- Max Per User --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Max Uses Per User *</label>
                            <input type="number" name="max_uses_per_user" id="fMaxPerUser" class="form-control"
                                   value="1" min="1" required>
                        </div>

                        {{-- Starts At --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Start Date</label>
                            <input type="date" name="starts_at" id="fStartsAt" class="form-control">
                        </div>

                        {{-- Expires At --}}
                        <div class="col-md-4">
                            <label class="form-label fw-600">Expiry Date</label>
                            <input type="date" name="expires_at" id="fExpiresAt" class="form-control">
                            <small style="color:var(--text-secondary);">Leave blank = never expires</small>
                        </div>

                        {{-- Active --}}
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch" style="margin-bottom:0.5rem;">
                                <input class="form-check-input" type="checkbox" name="is_active" id="fIsActive"
                                       value="1" checked style="width:2.5rem;height:1.25rem;">
                                <label class="form-check-label fw-600 ms-2" for="fIsActive">Active</label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid var(--border-color);">
                    <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-modern-primary">
                        <i class="fas fa-save"></i> <span id="submitLabel">Create Voucher</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleCapField() {
        const type   = document.getElementById('fDiscountType').value;
        const cap    = document.getElementById('capField');
        const prefix = document.getElementById('discountPrefix');
        cap.style.display  = type === 'percent' ? 'block' : 'none';
        prefix.textContent = type === 'percent' ? '%' : '₱';
    }

    function openModal() {
        // Reset to create mode
        const form = document.getElementById('voucherForm');
        form.reset();
        form.action = "{{ route('admin.vouchers.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').innerHTML  = '<i class="fas fa-ticket-alt me-2"></i>New Voucher';
        document.getElementById('submitLabel').textContent = 'Create Voucher';
        document.getElementById('fIsActive').checked = true;
        toggleCapField();
        new bootstrap.Modal(document.getElementById('voucherModal')).show();
    }

    function openEditModal(id) {
        const v = window.voucherData[id];
        if (!v) return;

        const form = document.getElementById('voucherForm');
        form.action = `/admin/vouchers/${id}`;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').innerHTML  = '<i class="fas fa-edit me-2"></i>Edit Voucher';
        document.getElementById('submitLabel').textContent = 'Save Changes';

        document.getElementById('fCode').value          = v.code;
        document.getElementById('fDescription').value   = v.description;
        document.getElementById('fDiscountType').value  = v.discount_type;
        document.getElementById('fDiscountValue').value = v.discount_value;
        document.getElementById('fMaxCap').value        = v.max_discount_cap ?? '';
        document.getElementById('fMinOrder').value      = v.min_order_amount;
        document.getElementById('fMaxUses').value       = v.max_uses ?? '';
        document.getElementById('fMaxPerUser').value    = v.max_uses_per_user;
        document.getElementById('fStartsAt').value      = v.starts_at;
        document.getElementById('fExpiresAt').value     = v.expires_at;
        document.getElementById('fIsActive').checked    = v.is_active;

        toggleCapField();
        new bootstrap.Modal(document.getElementById('voucherModal')).show();
    }

    // Auto-uppercase code input
    document.getElementById('fCode').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // Init cap field on page load
    toggleCapField();
</script>
@endpush