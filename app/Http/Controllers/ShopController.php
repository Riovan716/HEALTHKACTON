<?php

namespace App\Http\Controllers;

use App\Models\Voucher; // Import model Voucher
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('shop.shope', compact('vouchers'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $voucher = Voucher::find($request->voucher_id);
        $user = Auth::user();

        if ($user->coins >= $voucher->cost) {
            $user->coins -= $voucher->cost;
            $user->save();

            // Mengembalikan response JSON dengan jumlah koin terbaru
            return response()->json(['coins' => $user->coins]);
        } else {
            return response()->json(['error' => 'Koin tidak cukup'], 400);
        }
    }


    public function getCoins()
    {
        $user = Auth::user();
        return response()->json(['coins' => $user->coins]);
    }

}

