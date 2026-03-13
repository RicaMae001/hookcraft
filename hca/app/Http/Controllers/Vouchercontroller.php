<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper(trim($request->code)))->first();

        if (!$voucher) {
            return response()->json(['ok' => false, 'message' => 'Invalid voucher code.'], 422);
        }

        $result = $voucher->validate(Auth::id(), (float) $request->subtotal);

        if (!$result['ok']) {
            return response()->json(['ok' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'ok'             => true,
            'voucher_id'     => $voucher->id,
            'code'           => $voucher->code,
            'description'    => $voucher->description,
            'discount_label' => $voucher->discount_label,
            'discount'       => $result['discount'],
            'message'        => 'Voucher applied! You saved ₱' . number_format($result['discount'], 2),
        ]);
    }
}