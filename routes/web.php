<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiClientController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\OtpController;
use App\Models\Message;
use App\Models\Wallet;

Route::get('/', function () {
    return redirect()->route('login');
});



Route::get('/dashboard', function () {

    $totalMessages = Message::query()
        ->where('created_at', '>=', date('Y-m-d 00:00:00'))
->where('created_at', '<=', date('Y-m-d 23:59:59'))
        ->count();

    $deliveredMessages = Message::query()
        ->where('created_at', '>=', date('Y-m-d 00:00:00'))
        ->where('created_at', '<=', date('Y-m-d 23:59:59'))
        ->where('dlr_status', 'DELIVRD')
        ->count();

        $pendingMessages = Message::query()

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->where(function ($query) {

        $query->where('dlr_status', '0')
              ->orWhere('dlr_status', 'PEND')
              ->orWhereNull('dlr_status');

    })

    ->count();

    $failedMessages = Message::query()
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
        END) as pending
    ', [])

    ->where('created_at', '>=', date('Y-m-d 00:00:00'))

    ->where('created_at', '<=', date('Y-m-d 23:59:59'))

    ->groupBy('network')

    ->orderByDesc('total')

    ->get();

    $topSenderIds = Message::query()

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

    $lowWalletClients = Wallet::query()

    ->with('client')

    ->where('balance', '<=', 5000)

    ->orderBy('balance')

    ->limit(10)

    ->get();

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

})->middleware(['auth', 'verified'])->name('dashboard');


 Route::middleware('pending.otp')->group(function () {

    Route::get('/otp/verify', [OtpController::class, 'create'])
        ->name('otp.verify');

    Route::post('/otp/verify', [OtpController::class, 'store'])
        ->name('otp.verify.store');

    Route::post('/otp/resend', [OtpController::class, 'resend'])
        ->name('otp.resend');

});

Route::middleware('auth')->group(function () {


   Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:view users')
    ->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])
    ->middleware('permission:create users')
    ->name('users.create');
    Route::post('/users', [UserController::class, 'store'])
    ->middleware('permission:create users')
    ->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('permission:edit users')
    ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('permission:edit users')
    ->name('users.update');
    Route::get('/clients', [ApiClientController::class, 'index'])
    ->middleware('permission:view clients')
    ->name('clients.index');
    Route::get('/clients/create', [ApiClientController::class, 'create'])
    ->middleware('permission:create clients')
    ->name('clients.create');
    Route::post('/clients', [ApiClientController::class, 'store'])
    ->middleware('permission:create clients')
    ->name('clients.store');
    Route::get('/clients/{client}', [ApiClientController::class, 'show'])
    ->middleware('permission:view clients')
    ->name('clients.show');
    Route::patch('/clients/{client}/status', [ApiClientController::class, 'updateStatus'])
    ->middleware('permission:disable clients')
    ->name('clients.update-status');

    Route::get('/messages', [MessageController::class, 'index'])
    ->middleware('permission:view messages')
    ->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])
    ->middleware('permission:view messages')
    ->name('messages.show');

      Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:view roles')
        ->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:create roles')
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:create roles')
        ->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:edit roles')
        ->name('roles.edit');

    Route::patch('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:edit roles')
        ->name('roles.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
