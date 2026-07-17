@extends('layouts.main')

@section('content')

  {{-- main content area --}}
  <main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
      <div class="page-heading">
        <div class="page-heading-copy">
          <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <div>
            <p class="eyebrow mb-1">Overview</p>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">
              @if($isAdmin)
                Real-time stock levels, purchase records, material requests, and user activities.
              @else
                Track your material requests, record consumptions, and check low stock warnings.
              @endif
            </p>
          </div>
        </div>
      </div>

      <!-- ================= Stats Section ================= -->
      <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        @if($isAdmin)
          <!-- Admin Metrics -->
          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-primary">
              <div class="metric-top">
                <span class="metric-label">Total Materials</span>
                <span class="metric-icon"><i class="bi-box-seam" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $totalMaterials }}</div>
              <div class="metric-meta">
                <a href="{{ url('materials') }}" class="text-white text-decoration-none">Manage Materials <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-success">
              <div class="metric-top">
                <span class="metric-label">Total Purchases</span>
                <span class="metric-icon"><i class="bi bi-cart-plus" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $totalPurchases }}</div>
              <div class="metric-meta">
                <a href="{{ url('purchases') }}" class="text-white text-decoration-none">View Purchases <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-warning">
              <div class="metric-top">
                <span class="metric-label">Total Requests</span>
                <span class="metric-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $totalMaterialRequests }}</div>
              <div class="metric-meta">
                <a href="{{ url('material-requests') }}" class="text-white text-decoration-none">Review Requests <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card {{ $lowStockCount > 0 ? 'metric-danger' : 'metric-info' }}">
              <div class="metric-top">
                <span class="metric-label">Low Stock Items</span>
                <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $lowStockCount }}</div>
              <div class="metric-meta">
                <a href="{{ url('current-stock') }}" class="text-white text-decoration-none">Check Stock <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-info">
              <div class="metric-top">
                <span class="metric-label">Total Users</span>
                <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $totalUsers }}</div>
              <div class="metric-meta">
                <a href="{{ url('users') }}" class="text-white text-decoration-none">Manage Users <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>
        @else
          <!-- Kitchen Staff Metrics -->
          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-primary">
              <div class="metric-top">
                <span class="metric-label">My Requests</span>
                <span class="metric-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $myRequestsCount }}</div>
              <div class="metric-meta">
                <a href="{{ url('material-requests') }}" class="text-white text-decoration-none">My Requests <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-warning">
              <div class="metric-top">
                <span class="metric-label">Pending Requests</span>
                <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $pendingRequestsCount }}</div>
              <div class="metric-meta">
                <span class="text-white">Awaiting Approval</span>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-success">
              <div class="metric-top">
                <span class="metric-label">Pending Receipts</span>
                <span class="metric-icon"><i class="bi bi-truck" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $dispatchesPendingCount }}</div>
              <div class="metric-meta">
                <a href="{{ route('material-dispatch.index') }}#dispatchedSection" class="text-white text-decoration-none">Receive Dispatches <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card metric-info">
              <div class="metric-top">
                <span class="metric-label">Total Consumed</span>
                <span class="metric-icon"><i class="bi bi-basket" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ number_format($totalConsumed, 2) }}</div>
              <div class="metric-meta">
                <a href="{{ url('material-consumption') }}" class="text-white text-decoration-none">My Consumptions <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg">
            <article class="metric-card {{ $lowStockCount > 0 ? 'metric-danger' : 'metric-secondary' }}">
              <div class="metric-top">
                <span class="metric-label">Low Stock Items</span>
                <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
              </div>
              <div class="metric-value">{{ $lowStockCount }}</div>
              <div class="metric-meta">
                <a href="{{ url('current-stock') }}" class="text-white text-decoration-none">Check Stock <i class="bi bi-arrow-right-short"></i></a>
              </div>
            </article>
          </div>
        @endif
      </section>

      <!-- ================= Charts Row ================= -->
      <section class="row g-3 mt-1">
        <div class="col-12 col-xl-6">
          <div class="panel p-3 h-100">
            <h2 class="h5 mb-3 section-title">
              <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
              @if($isAdmin)
                <span>Top Materials Stock Levels</span>
              @else
                <span>My Top Consumed Materials</span>
              @endif
            </h2>
            <div style="position: relative; height:300px; width:100%;">
              <canvas id="primaryChart"></canvas>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-6">
          <div class="panel p-3 h-100">
            <h2 class="h5 mb-3 section-title">
              <i class="bi bi-pie-chart-fill me-2 text-success"></i>
              @if($isAdmin)
                <span>Materials Count by Category</span>
              @else
                <span>My Request Status Distribution</span>
              @endif
            </h2>
            <div style="position: relative; height:300px; width:100%;">
              <canvas id="secondaryChart"></canvas>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= Row 2 ================= -->
      <section class="row g-3 mt-3">
        @if($isAdmin)
          <!-- Recent Requests -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    <span>Recent Material Requests</span>
                  </h2>
                  <p class="text-muted mb-0">Latest requested materials in the system.</p>
                </div>
                <a href="{{ url('material-requests') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Request No</th>
                      <th>Requester</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentRequests as $req)
                      <tr>
                        <td><span class="fw-semibold text-primary">{{ $req->request_no }}</span></td>
                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                        <td>{{ $req->request_date->format('M d, Y') }}</td>
                        <td>
                          @if($req->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                          @elseif($req->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                          @else
                            <span class="badge bg-danger">Rejected</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('material-requests.show', $req->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i>
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted py-3">No recent requests found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Recent Purchases -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-cart-plus me-2"></i>
                    <span>Recent Purchases</span>
                  </h2>
                  <p class="text-muted mb-0">Latest vendor purchase transactions.</p>
                </div>
                <a href="{{ url('purchases') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Purchase No</th>
                      <th>Supplier</th>
                      <th>Date</th>
                      <th>Total Amount</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentPurchases as $purchase)
                      <tr>
                        <td><span class="fw-semibold text-success">{{ $purchase->purchase_no }}</span></td>
                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') : 'N/A' }}</td>
                        <td>${{ number_format($purchase->total_amount, 2) }}</td>
                        <td>
                          <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i>
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted py-3">No recent purchases found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          <!-- Kitchen Staff: My Recent Requests & Pending Receipt Dispatches -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    <span>My Recent Requests</span>
                  </h2>
                  <p class="text-muted mb-0">Your recently created material requests.</p>
                </div>
                <a href="{{ url('material-requests') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Request No</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($myRecentRequests as $req)
                      <tr>
                        <td><span class="fw-semibold text-primary">{{ $req->request_no }}</span></td>
                        <td>{{ $req->request_date->format('M d, Y') }}</td>
                        <td>
                          @if($req->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                          @elseif($req->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                          @else
                            <span class="badge bg-danger">Rejected</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('material-requests.show', $req->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i>
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center text-muted py-3">You have not submitted any requests.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-truck me-2"></i>
                    <span>Dispatches Ready to Receive</span>
                  </h2>
                  <p class="text-muted mb-0">Materials dispatched to you, waiting for receipt confirmation.</p>
                </div>
                <a href="{{ route('material-dispatch.index') }}#dispatchedSection" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Dispatch No</th>
                      <th>Request No</th>
                      <th>Dispatched Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($pendingReceiptDispatches as $disp)
                      <tr>
                        <td><span class="fw-semibold text-info">{{ $disp->dispatch_no }}</span></td>
                        <td>{{ $disp->request->request_no ?? 'N/A' }}</td>
                        <td>{{ $disp->dispatched_at ? \Carbon\Carbon::parse($disp->dispatched_at)->format('M d, Y') : 'N/A' }}</td>
                        <td><span class="badge bg-info">Dispatched</span></td>
                        <td>
                          <a href="{{ route('material-dispatch.index') }}#dispatchedSection" class="btn btn-success btn-sm text-white">
                            <i class="bi bi-box-arrow-in-down"></i> Receive
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted py-3">No dispatches pending receipt.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif
      </section>

      <!-- ================= Row 3 ================= -->
      <section class="row g-3 mt-3">
        @if($isAdmin)
          <!-- Low Stock Alerts -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                    <span>Low Stock Alerts</span>
                  </h2>
                  <p class="text-muted mb-0">Materials with stock at or below minimum level.</p>
                </div>
                <a href="{{ url('current-stock') }}" class="btn btn-light btn-sm">Check Stock</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Material</th>
                      <th>Current Stock</th>
                      <th>Min Stock</th>
                      <th>Unit</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($lowStockAlerts as $mat)
                      <tr>
                        <td><span class="fw-semibold">{{ $mat->material_name }}</span></td>
                        <td class="text-danger fw-bold">{{ number_format($mat->current_stock, 2) }}</td>
                        <td>{{ number_format($mat->minimum_stock, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $mat->unit }}</span></td>
                        <td><span class="badge bg-danger">Low Stock</span></td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-success fw-semibold py-3">
                          <i class="bi bi-check-circle me-1"></i> All materials are fully stocked!
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Recent Wastage Reports -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-trash3 me-2"></i>
                    <span>Recent Wastage Reports</span>
                  </h2>
                  <p class="text-muted mb-0">Latest logged material wastage logs.</p>
                </div>
                <a href="{{ url('wastages') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Wastage No</th>
                      <th>Material</th>
                      <th>Qty</th>
                      <th>Reason</th>
                      <th>Recorded By</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentWastages as $wastage)
                      <tr>
                        <td><span class="fw-semibold text-danger">{{ $wastage->wastage_no }}</span></td>
                        <td>{{ $wastage->material->material_name ?? 'N/A' }}</td>
                        <td class="text-danger fw-bold">{{ number_format($wastage->quantity, 2) }}</td>
                        <td>{{ Str::limit($wastage->reason, 20) }}</td>
                        <td>{{ $wastage->recordedBy->name ?? 'N/A' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted py-3">No wastage recorded recently.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          <!-- Kitchen Staff: My Recent Consumptions & My Recent Wastages -->
          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-basket me-2"></i>
                    <span>My Recent Consumptions</span>
                  </h2>
                  <p class="text-muted mb-0">Your recently logged material consumptions.</p>
                </div>
                <a href="{{ url('material-consumption') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Material</th>
                      <th>Consumed Qty</th>
                      <th>Remaining Qty</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($myRecentConsumptions as $cons)
                      <tr>
                        <td><span class="fw-semibold">{{ $cons->material->material_name ?? 'N/A' }}</span></td>
                        <td class="text-success fw-bold">{{ number_format($cons->consumed_qty, 2) }}</td>
                        <td>{{ number_format($cons->remaining_qty, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($cons->consumption_date)->format('M d, Y') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center text-muted py-3">No consumptions recorded recently.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-6">
            <div class="panel h-100">
              <div class="panel-header">
                <div>
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-trash3 me-2"></i>
                    <span>My Recent Wastages</span>
                  </h2>
                  <p class="text-muted mb-0">Your recently logged wastage records.</p>
                </div>
                <a href="{{ url('wastages') }}" class="btn btn-light btn-sm">View All</a>
              </div>
              <div class="table-responsive p-3">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Wastage No</th>
                      <th>Material</th>
                      <th>Qty</th>
                      <th>Reason</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($myRecentWastages as $wastage)
                      <tr>
                        <td><span class="fw-semibold text-danger">{{ $wastage->wastage_no }}</span></td>
                        <td>{{ $wastage->material->material_name ?? 'N/A' }}</td>
                        <td class="text-danger fw-bold">{{ number_format($wastage->quantity, 2) }}</td>
                        <td>{{ Str::limit($wastage->reason, 20) }}</td>
                        <td>{{ \Carbon\Carbon::parse($wastage->wastage_date)->format('M d, Y') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted py-3">No wastage recorded recently.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif
      </section>

      <!-- ================= Recent Users (Admin Only) ================= -->
      @if($isAdmin)
        <section class="panel mt-3">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title">
                <i class="bi bi-people me-2"></i>
                <span>Recent Users</span>
              </h2>
              <p class="text-muted mb-0">Latest account activity across the workspace.</p>
            </div>
            @can('user.index')
              <a class="btn btn-outline-secondary btn-sm" href="{{ url('users') }}">Manage Users</a>
            @endcan
          </div>
          <div class="table-responsive p-3">
            <table class="table align-middle mb-0" id="usersTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">User Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role</th>
                  <th scope="col">Joined</th>
                  <th scope="col" class="text-dark">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentUsers as $u)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="fw-semibold text-dark">{{ $u->name }}</span></td>
                    <td>{{ $u->email }}</td>
                    <td>
                      @if($u->roles->isNotEmpty())
                        <span class="badge bg-primary">{{ $u->roles->first()->name }}</span>
                      @else
                        <span class="badge bg-secondary">No Role</span>
                      @endif
                    </td>
                    <td>{{ $u->created_at->format('M d, Y') }}</td>
                    <td>
                      <a href="{{ route('users.show', $u->id) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-3">No users found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      @endif
    </div>
  </main>

  <!-- Chart.js and rendering code -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Dynamic Theme Chart colors (vibrant, modern)
      const colors = {
        palette: [
          'rgba(54, 162, 235, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(255, 99, 132, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 159, 64, 0.7)'
        ]
      };

      // Primary Chart (Bar chart)
      const ctx1 = document.getElementById('primaryChart').getContext('2d');
      new Chart(ctx1, {
        type: 'bar',
        data: {
          labels: @json($isAdmin ? $stockChartLabels : $consumptionChartLabels),
          datasets: [{
            label: @json($isAdmin ? 'Current Stock Qty' : 'Total Consumed Qty'),
            data: @json($isAdmin ? $stockChartValues : $consumptionChartValues),
            backgroundColor: colors.palette,
            borderColor: colors.palette.map(c => c.replace('0.7', '1')),
            borderWidth: 1.5,
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
              grid: { display: false }
            }
          }
        }
      });

      // Secondary Chart (Doughnut / Pie)
      const ctx2 = document.getElementById('secondaryChart').getContext('2d');
      new Chart(ctx2, {
        type: 'doughnut',
        data: {
          labels: @json($isAdmin ? $categoryChartLabels : $requestStatusChartLabels),
          datasets: [{
            data: @json($isAdmin ? $categoryChartValues : $requestStatusChartValues),
            backgroundColor: colors.palette,
            borderColor: '#ffffff',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
              labels: { boxWidth: 15, padding: 15 }
            }
          },
          cutout: '60%'
        }
      });
    });
  </script>

@endsection