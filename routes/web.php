<?php

use App\Http\Controllers\CurrentStockReportController;
use App\Http\Controllers\DashboardConroller;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialConsumptionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialDispatchController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockLedgerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WastageController;
use App\Models\AppNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-dashboard', [DashboardConroller::class, 'index'])
        ->name('stocks.index');

    /*
    |--------------------------------------------------------------------------
    | Masters
    |--------------------------------------------------------------------------
    */

    Route::resource('material-category', MaterialCategoryController::class);

    Route::resource('materials', MaterialController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource('users', UserController::class);

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    Route::resource('purchases', PurchaseController::class);

    Route::resource('material-requests', MaterialRequestController::class);
    Route::patch('material-requests/{material_request}/approve', [MaterialRequestController::class, 'approve'])->name('material-requests.approve');
    Route::patch('material-requests/{material_request}/reject', [MaterialRequestController::class, 'reject'])->name('material-requests.reject');

    Route::prefix('material-dispatch')->name('material-dispatch.')->group(function () {
        // Main Page
        Route::get('/', [MaterialDispatchController::class, 'index'])->name('index');
        // Pending -> Approve
        Route::post('/approve', [MaterialDispatchController::class, 'approve'])->name('approve');
        // Pending -> Reject
        Route::post('/reject', [MaterialDispatchController::class, 'reject'])->name('reject');
        // Approved/Partial -> Dispatch
        Route::post('/dispatch', [MaterialDispatchController::class, 'dispatch'])->name('dispatch');
        // Dispatched -> Receive
        Route::post('/receive', [MaterialDispatchController::class, 'receive'])->name('receive');
        // Discrepancy -> Resolve
        Route::post('/resolve', [MaterialDispatchController::class, 'resolve'])->name('resolve');

    });

    Route::prefix('material-consumption')->name('material-consumption.')->group(function () {
        // Main Page
        Route::get('/', [MaterialConsumptionController::class, 'index'])->name('index');
        // Store Material Consumption
        Route::post('/', [MaterialConsumptionController::class, 'store'])->name('store');
    });

    Route::resource('wastages', WastageController::class);

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-ledger', [StockLedgerController::class, 'index'])->name('stock-ledger.index');

    Route::get('/current-stock', [CurrentStockReportController::class, 'index'])->name('current-stock-report.index');

    /*
    |--------------------------------------------------------------------------
    | Notifications API
    |--------------------------------------------------------------------------
    */
    Route::get('/api/notifications', function () {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);

        $query = AppNotification::where('is_read', false);

        if (!$isAdmin) {
            $role = 'Kitchen Staff';
            $query->where(function ($q) use ($user, $role) {
                $q->where('user_id', $user->id)->orWhere('target_role', $role);
            });
        }

        $notifications = $query->latest()->get();

        return response()->json($notifications);
    })->name('api.notifications.index');

    Route::post('/api/notifications/{notification}/read', function (AppNotification $notification) {
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    })->name('api.notifications.read');

    Route::post('/api/notifications/read-all', function () {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);

        $query = AppNotification::where('is_read', false);

        if (!$isAdmin) {
            $role = 'Kitchen Staff';
            $query->where(function ($q) use ($user, $role) {
                $q->where('user_id', $user->id)
                    ->orWhere('target_role', $role);
            });
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    })->name('api.notifications.read-all');

    Route::get('/notifications-history', function () {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);
        $role = $isAdmin ? 'Admin' : 'Kitchen Staff';

        $query = AppNotification::with('user');

        // Admin can see everything, other users can only see notifications that are targeting them directly or their role
        if (!$isAdmin) {
            $query->where(function ($q) use ($user, $role) {
                $q->where('user_id', $user->id)->orWhere('target_role', $role);
            });
        }

        $notifications = $query->latest()->paginate(15);

        return view('stocks.notifications.history', compact('notifications'));
    })->name('notifications.history');

});

require __DIR__ . '/auth.php';
