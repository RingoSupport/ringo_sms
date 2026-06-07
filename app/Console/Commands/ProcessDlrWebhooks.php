<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Message;

class ProcessDlrWebhooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhooks:process-dlr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process SMS DLR webhooks';

    /**
     * Execute the console command.
     */
public function handle(): int
{
    $dlrs = DB::table('mobi_dlr')
        ->select([
            'id',
            'message_id',
            'status',
            'error_code',
            'dlr_date',
            'msisdn',
            'ref_id',
            'network',
        ])
        ->orderBy('id')
        ->limit(100)
        ->get();

    $this->info('DLR Records Found: ' . $dlrs->count());

    if ($dlrs->isEmpty()) {

        $this->info('No pending DLR records.');

        return self::SUCCESS;
    }

    $messageIds = $dlrs
        ->pluck('message_id')
        ->unique()
        ->values();

    $messages = Message::query()
        ->select([
            'id',
            'client_id',
            'webhook_sent_at',
        ])
        ->whereIn('id', $messageIds)
        ->with([
            'client:id,webhook_url,webhook_enabled',
        ])
        ->get()
        ->keyBy('id');

    $finalStatuses = [
        'DELIVRD',
        'FAILED',
        'UNDELIV',
        'EXPIRED',
        'REJECTD',
    ];

    foreach ($dlrs as $dlr) {

        $message = $messages[$dlr->message_id] ?? null;

        if (! in_array(strtoupper($dlr->status), $finalStatuses)) {

            $this->info(
                "Skipping non-final status {$dlr->status} for Message {$dlr->message_id}"
            );

            DB::table('mobi_dlr')
                ->where('id', $dlr->id)
                ->delete();

            continue;
        }

        if (! $message) {

            $this->warn(
                "Message {$dlr->message_id} not found"
            );

            continue;
        }

        if (! $message->client) {

            $this->warn(
                "Client not found for message {$dlr->message_id}"
            );

            continue;
        }

        if (! $message->client->webhook_enabled) {

            $this->warn(
                "Webhook disabled for client {$message->client_id}"
            );

            continue;
        }

        if (empty($message->client->webhook_url)) {

            $this->warn(
                "Webhook URL missing for client {$message->client_id}"
            );

            continue;
        }

        if ($message->webhook_sent_at) {

            $this->warn(
                "Webhook already sent for Message {$dlr->message_id}"
            );

            DB::table('mobi_dlr')
                ->where('id', $dlr->id)
                ->delete();

            continue;
        }

        $payload = [

            'message_id'   => $dlr->message_id,

            'reference_id' => $dlr->ref_id,

            'destination'  => $dlr->msisdn,

            'status'       => $dlr->status,

            'error_code'   => $dlr->error_code === '0'
                ? null
                : $dlr->error_code,

            'network'      => $dlr->network,

            'dlr_date'     => $dlr->dlr_date,

        ];

        $this->info(
            "Sending DLR {$dlr->id} for Message {$dlr->message_id}"
        );

        try {

            $response = Http::timeout(10)
                ->acceptJson()
                ->post(
                    $message->client->webhook_url,
                    $payload
                );

        } catch (\Throwable $e) {

            report($e);

            $this->error(
                "Webhook failed for DLR {$dlr->id}: {$e->getMessage()}"
            );

            continue;
        }

        $this->info(
            "Webhook Response: {$response->status()}"
        );

        if ($response->successful()) {

           $message->update([
                'webhook_sent_at' => now(),
            ]);

            DB::table('mobi_dlr')
                ->where('id', $dlr->id)
                ->delete();

            $this->info(
                "Deleted DLR {$dlr->id}"
            );
        }
    }

    return self::SUCCESS;
}

}
