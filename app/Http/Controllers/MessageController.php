<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class MessageController extends Controller
{
    public function index(Request $request): View
    {



   $client = Auth::guard('client')->user();

$clientId = $client?->id;

$senderIds = Message::query()

    ->when($clientId, function ($query) use ($clientId) {

        $query->forClient($clientId);

    })

    ->select('senderid')

    ->distinct()

    ->whereNotNull('senderid')

    ->orderBy('senderid')

    ->pluck('senderid');

   $perPage = min((int) $request->per_page ?: 10, 100);

   $messages = Message::query()

    ->when($clientId, function ($query) use ($clientId) {

    $query->forClient($clientId);

})

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

            elseif ($status === 'pending') {

                $query->where(function ($q) {

                    $q->where('dlr_status', '0')
                      ->orWhere('dlr_status', 'PEND');

                });

            }

            elseif ($status === 'failed') {

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


    ]);
}

public function show(string $id): View
{
    $client = Auth::guard('client')->user();
   $message = Message::query()

    ->when($client, function ($query) use ($client) {

        $query->forClient($client->id);

    })
        ->findOrFail($id);

    return view('messages.show', [

        'message' => $message,

    ]);
}
}


