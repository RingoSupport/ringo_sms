<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    return view('wallets.my-wallet', [

        'wallet' => $wallet,

    ]);
}
}
