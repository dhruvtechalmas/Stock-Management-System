<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialConsumption;
use App\Models\MaterialDispatch;
use App\Models\MaterialRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Wastage;
use Illuminate\Support\Facades\Auth;

class DashboardConroller extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);

        $data = [
            'isAdmin' => $isAdmin,
            'lowStockCount' => Material::whereColumn('current_stock', '<=', 'minimum_stock')->count(),
        ];

        if ($isAdmin) {
            $data['totalMaterials'] = Material::count();
            $data['totalSuppliers'] = Supplier::count();
            $data['totalPurchases'] = Purchase::count();
            $data['totalMaterialRequests'] = MaterialRequest::count();
            $data['totalUsers'] = User::count();

            $data['recentRequests'] = MaterialRequest::with('user')
                ->latest()
                ->take(5)
                ->get();

            $data['recentPurchases'] = Purchase::with('supplier')
                ->latest()
                ->take(5)
                ->get();

            $data['lowStockAlerts'] = Material::whereColumn('current_stock', '<=', 'minimum_stock')
                ->latest()
                ->take(5)
                ->get();

            $data['recentWastages'] = Wastage::with(['material', 'recordedBy'])
                ->latest()
                ->take(5)
                ->get();

            $data['recentUsers'] = User::with('roles')
                ->latest()
                ->take(5)
                ->get();

            // Chart 1: Current stock of the top 5 materials
            $topMaterials = Material::orderBy('current_stock', 'desc')
                ->take(5)
                ->get(['material_name', 'current_stock']);
            $data['stockChartLabels'] = $topMaterials->pluck('material_name');
            $data['stockChartValues'] = $topMaterials->pluck('current_stock');

            // Chart 2: Number of materials in each category
            $categories = MaterialCategory::withCount('materials')
                ->get(['category_name']);
            $data['categoryChartLabels'] = $categories->pluck('category_name');
            $data['categoryChartValues'] = $categories->pluck('materials_count');
        } else {
            $userId = $user->id;

            $data['myRequestsCount'] = MaterialRequest::where('requested_by', $userId)->count();
            $data['pendingRequestsCount'] = MaterialRequest::where('requested_by', $userId)
                ->where('status', 'pending')
                ->count();

            // Dispatches that are in 'dispatched' status for this user's requests, waiting to be received
            $data['dispatchesPendingCount'] = MaterialDispatch::where('status', 'dispatched')
                ->whereHas('request', function ($q) use ($userId) {
                    $q->where('requested_by', $userId);
                })
                ->count();

            $data['totalConsumed'] = MaterialConsumption::where('recorded_by', $userId)->sum('consumed_qty');

            $data['myRecentRequests'] = MaterialRequest::where('requested_by', $userId)
                ->latest()
                ->take(5)
                ->get();

            $data['myRecentConsumptions'] = MaterialConsumption::with('material')
                ->where('recorded_by', $userId)
                ->latest()
                ->take(5)
                ->get();

            $data['pendingReceiptDispatches'] = MaterialDispatch::with(['request.user', 'items.material'])
                ->where('status', 'dispatched')
                ->whereHas('request', function ($q) use ($userId) {
                    $q->where('requested_by', $userId);
                })
                ->latest()
                ->take(5)
                ->get();

            $data['myRecentWastages'] = Wastage::with('material')
                ->where('recorded_by', $userId)
                ->latest()
                ->take(5)
                ->get();

            // Chart 1: counts of material requests by status
            $statuses = MaterialRequest::where('requested_by', $userId)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            $data['requestStatusChartLabels'] = ['Pending', 'Approved', 'Rejected'];
            $data['requestStatusChartValues'] = [
                (int) $statuses->get('pending', 0),
                (int) $statuses->get('approved', 0),
                (int) $statuses->get('rejected', 0),
            ];

            // Chart 2: top consumed materials by quantity
            $topConsumptions = MaterialConsumption::with('material')
                ->where('recorded_by', $userId)
                ->selectRaw('material_id, sum(consumed_qty) as total_consumed')
                ->groupBy('material_id')
                ->orderBy('total_consumed', 'desc')
                ->take(5)
                ->get();
            $data['consumptionChartLabels'] = $topConsumptions->map(function ($c) {
                return $c->material->material_name ?? 'Unknown';
            });
            $data['consumptionChartValues'] = $topConsumptions->pluck('total_consumed');
        }

        return view('stocks.index', $data);
    }
}
