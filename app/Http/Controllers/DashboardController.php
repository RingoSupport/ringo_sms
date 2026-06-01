<?php

namespace App\Http\Controllers;


use App\Models\Message;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    //
     public function index(): View
    {
       $user = Auth::user();

$client = Auth::guard('client')->user();

$clientId = $client?->id;

    $totalMessages = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })
        ->where('created_at', '>=', date('Y-m-d 00:00:00'))
->where('created_at', '<=', date('Y-m-d 23:59:59'))
        ->count();

    $deliveredMessages = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })
        ->where('created_at', '>=', date('Y-m-d 00:00:00'))
        ->where('created_at', '<=', date('Y-m-d 23:59:59'))
        ->where('dlr_status', 'DELIVRD')
        ->count();

        $pendingMessages = Message::query()
          ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->where(function ($query) {

        $query->where('dlr_status', '0')
              ->orWhere('dlr_status', 'PEND')
              ->orWhereNull('dlr_status');

    })

    ->count();

    $failedMessages = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })
        ->where('created_at', '>=', date('Y-m-d 00:00:00'))
        ->where('created_at', '<=', date('Y-m-d 23:59:59'))
        ->whereIn('dlr_status', [
            'EXPIRD',
            'FAILED',
            'UNDELIV',
        ])
        ->count();

        $deliveryRate = $totalMessages > 0
    ? round(($deliveredMessages / $totalMessages) * 100, 2)
    : 0;

    $totalWalletBalance = Wallet::query()
        ->sum('balance');

        $hourlyTraffic = Message::query()
         ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

        ->selectRaw('
            HOUR(created_at) as hour,
            COUNT(*) as total
        ', [])

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->groupByRaw('HOUR(created_at)')

    ->orderBy('hour')

    ->get();

    $recentFailedMessages = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->whereIn('dlr_status', [
        'EXPIRD',
        'FAILED',
        'UNDELIV',
    ])

    ->latest('created_at')

    ->limit(10)

    ->get();

    $recentPendingMessages = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->where(function ($query) {

        $query->where('dlr_status', '0')
              ->orWhere('dlr_status', 'PEND')
              ->orWhereNull('dlr_status');

    })

    ->latest('created_at')

    ->limit(10)

    ->get();

    $networkHealth = Message::query()
    ->when($clientId, function ($query) use ($clientId) {
        $query->forClient($clientId);
    })
    ->selectRaw('
        network,

        COUNT(*) as total,

        SUM(CASE WHEN dlr_status = "DELIVRD" THEN 1 ELSE 0 END) as delivered,

        SUM(CASE
            WHEN dlr_status IN ("EXPIRD", "FAILED", "UNDELIV")
            THEN 1
            ELSE 0
        END) as failed,

        SUM(CASE
            WHEN dlr_status IN ("0", "PEND")
            OR dlr_status IS NULL
            THEN 1
            ELSE 0
        END) as pending,

            ROUND(
            (
                SUM(CASE WHEN dlr_status = "DELIVRD" THEN 1 ELSE 0 END)
                / NULLIF(COUNT(*), 0)
            ) * 100,
            2
        ) as delivery_rate
    ')
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->groupBy('network')
    ->orderByDesc('total')
    ->get();

    $topSenderIds = Message::query()
      ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

    ->selectRaw('
        senderid,
        COUNT(*) as total
    ', [])

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->groupBy('senderid')

    ->orderByDesc('total')

    ->limit(10)

    ->get();
    $activeClients = collect();
   if (! $client) {
    $activeClients = Message::query()

    ->selectRaw('
        client_id,
        COUNT(*) as total
    ', [])

    ->with('client')

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->whereNotNull('client_id')

    ->groupBy('client_id')

    ->orderByDesc('total')

    ->limit(10)

    ->get();
    }

    $lowWalletClients = collect();


if (! $client) {

    $lowWalletClients = Wallet::query()

    ->with('client')

    ->where('balance', '<=', 5000)

    ->orderBy('balance')

    ->limit(10)

    ->get();
}
    return view('dashboard', [

        'totalMessages' => $totalMessages,

        'deliveredMessages' => $deliveredMessages,

        'failedMessages' => $failedMessages,

        'totalWalletBalance' => $totalWalletBalance,
        'deliveryRate' => $deliveryRate,
        'hourlyTraffic' => $hourlyTraffic,
        'recentFailedMessages' => $recentFailedMessages,
        'pendingMessages' => $pendingMessages,
        'networkHealth' => $networkHealth,
        'topSenderIds' => $topSenderIds,
        'activeClients' => $activeClients,
        'lowWalletClients' => $lowWalletClients,
        'recentPendingMessages' => $recentPendingMessages,

    ]);


    }
}
