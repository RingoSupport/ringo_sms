<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;



class MessageController extends Controller
{
    public function index(Request $request): View
    {
    $totalMessagesToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->count();

$deliveredToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->where('dlr_status', 'DELIVRD')
    ->count();

$pendingToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
  ->where(function ($query) {

        $query->where('dlr_status', '0')
              ->orWhere('dlr_status', 'PEND');

    })
    ->count();

$failedToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->whereIn('dlr_status', [
        'EXPIRD',
        'FAILED',
        'UNDELIV',
    ])

    ->count();
     $senderIds = Message::query()
        ->select('senderid')
        ->distinct()
        ->whereNotNull('senderid')
        ->orderBy('senderid')
        ->pluck('senderid');

    $perPage = $request->per_page ?? 10;

    $messages = Message::query()

        ->when($request->search, function ($query, $search) {

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('msisdn', 'like', "%{$search}%");

            });

        })

        ->when($request->status, function ($query, $status) {

            if ($status === 'delivered') {

                $query->where('dlr_status', 'DELIVRD');

            }

            if ($status === 'pending') {

                $query->where(function ($q) {

                    $q->where('dlr_status', '0')
                      ->orWhere('dlr_status', 'PEND');

                });

            }

            if ($status === 'failed') {

                $query->whereIn('dlr_status', [
                    'EXPIRD',
                    'FAILED',
                    'UNDELIV',
                ]);

            }

        })

        ->when($request->network, function ($query, $network) {

            $query->where('network', $network);

        })

        ->when($request->senderid, function ($query, $senderId) {

            $query->where('senderid', $senderId);

        })

        ->when($request->start_date, function ($query, $startDate) {

            $query->whereDate('created_at', '>=', $startDate);

        })

        ->when($request->end_date, function ($query, $endDate) {

            $query->whereDate('created_at', '<=', $endDate);

        })

        ->latest('created_at')

        ->paginate($perPage)

        ->withQueryString();

    return view('messages.index', [
        'messages' => $messages,
        'senderIds' => $senderIds,

        'totalMessagesToday' => $totalMessagesToday,
        'deliveredToday' => $deliveredToday,
        'pendingToday' => $pendingToday,
        'failedToday' => $failedToday,
    ]);
}
}


