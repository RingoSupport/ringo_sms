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

$submittedToday = Message::query()
     ->where('created_at', '>=', date('Y-m-d 00:00:00'))
      ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->where('status', '1')
    ->count();

$pendingToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->where('status', '0')
    ->count();

$failedToday = Message::query()
    ->where('created_at', '>=', date('Y-m-d 00:00:00'))
    ->where('created_at', '<=', date('Y-m-d 23:59:59'))
    ->where('status', '2')
    ->count();
        $messages = $messages = Message::query()

            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('id', 'like', "%{$search}%")
                        ->orWhere('msisdn', 'like', "%{$search}%")
                        ->orWhere('senderid', 'like', "%{$search}%");

                });

            })

            ->when($request->status, function ($query, $status) {

                $query->where('status', $status);

            })

            ->when($request->network, function ($query, $network) {

                $query->where('network', $network);

            })
            ->when($request->start_date, function ($query, $startDate) {

    $query->whereDate('created_at', '>=', $startDate);

})

->when($request->senderid, function ($query, $senderId) {

    $query->where('senderid', 'like', "%{$senderId}%");

})

->when($request->end_date, function ($query, $endDate) {

    $query->whereDate('created_at', '<=', $endDate);

})

            ->latest('created_at')

            ->paginate(20)

            ->withQueryString();

       return view('messages.index', [
    'messages' => $messages,

    'totalMessagesToday' => $totalMessagesToday,
    'submittedToday' => $submittedToday,
    'pendingToday' => $pendingToday,
    'failedToday' => $failedToday,
]);
    }

    public function show(Message $message): View
{
    return view('messages.show', [
        'message' => $message,
    ]);
}
}
