<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\WalletHistory;

class WalletController extends Controller
{
    //

public function myWallet(): View
{
    $client = Auth::guard('client')->user();

    abort_if(! $client, 403);

    $wallet = Wallet::query()
        ->where('client_id', $client->id)
        ->first();

    $totalCredits = 0;
    $totalDebits = 0;

    $walletHistory = collect();

    if ($wallet) {

        $walletHistory = WalletHistory::query()
            ->where('wallet_id', $wallet->id)
            ->latest()
            ->paginate(20);

        $totalCredits = WalletHistory::query()
            ->where('wallet_id', $wallet->id)
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = WalletHistory::query()
            ->where('wallet_id', $wallet->id)
            ->where('type', 'debit')
            ->sum('amount');
    }

    return view('wallets.my-wallet', [
        'wallet' => $wallet,
        'walletHistory' => $walletHistory,
        'totalCredits' => $totalCredits,
        'totalDebits' => $totalDebits,
    ]);
}





}
