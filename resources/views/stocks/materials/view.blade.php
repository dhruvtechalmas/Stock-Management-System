@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">

                <div class="page-heading-copy">
                    <span class="page-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Material Details</h1>

                        <p class="text-muted mb-0">
                            View complete material information.
                        </p>
                    </div>
                </div>

                <div class="heading-actions">
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary btn-sm">

                        <i class="bi bi-arrow-left"></i>
                        Back to Materials

                    </a>
                </div>

            </div>

            <section class="row g-3">

                <!-- Left Side -->
                <div class="col-12 col-xl-4">

                    <div class="panel h-100 text-center profile-card">

                        <div class="profile-hero">

                            {{-- Image --}}
                            <div>
                                @if($material->image)
                                    <img src="{{ asset('storage/' . $material->image) }}" width="90" height="100"
                                        class="rounded object-fit-cover" alt="{{ $material->material_name }}">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" width="60" height="60"
                                        class="rounded object-fit-cover" alt="No Image">
                                @endif
                            </div>

                            <h2 class="h5 mt-3 mb-1">
                                {{ $material->material_name }}
                            </h2>

                            <span class="badge {{ $material->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $material->status ? 'Active' : 'Inactive' }}
                            </span>

                        </div>

                        <div class="info-list mt-4 text-start">

                            <div>
                                <span>Material Name</span>
                                <strong>{{ $material->material_name }}</strong>
                            </div>

                            <div>
                                <span>Material Category</span>
                                <strong>{{ $material->category?->category_name ?? 'N/A' }}</strong>
                            </div>

                            <div>
                                <span>Unit</span>
                                <strong>{{ $material->unit }}</strong>
                            </div>

                            
                            <div>
                                <span>Current Stock</span>
                                <strong>{{ $material->current_stock }}</strong>
                            </div>

                            <div>
                                <span>Minimum Stock</span>
                                <strong>{{ $material->minimum_stock }}</strong>
                            </div>

                            <div>
                                <span>Status</span>
                                <strong>
                                    {{ $material->status ? 'Active' : 'Inactive' }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right Side -->
                <div class="col-12 col-xl-8">

                    <!-- Material Overview -->
                    <div class="panel mb-3">

                        <div class="panel-header">
                            <h2 class="h5 mb-0">
                                Material Overview
                            </h2>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-3">
                                <div class="mini-card">
                                    <span>Status</span>
                                    <strong>
                                        {{ $material->status ? 'Active' : 'Inactive' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mini-card">
                                    <span>Material ID</span>
                                    <strong>#{{ $material->id }}</strong>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mini-card">
                                    <span>Unit</span>
                                    <strong>{{ $material->unit }}</strong>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mini-card">
                                    <span>Minimum Stock</span>
                                    <strong>{{ $material->minimum_stock }}</strong>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Description -->
                    <div class="panel mb-3">

                        <div class="panel-header">
                            <h2 class="h5 mb-0">
                                Description
                            </h2>
                        </div>

                        <p>
                            {{ $material->description ?? 'No description available.' }}
                        </p>

                    </div>

                    <!-- Recent Activity -->
                    <div class="panel">

                        <div class="panel-header">
                            <h2 class="h5 mb-0">
                                Recent Activity
                            </h2>
                        </div>

                        <div class="activity-item">

                            <span class="activity-dot bg-primary"></span>

                            <div>

                                <p class="mb-1 fw-semibold">
                                    Material Created
                                </p>

                                <p class="text-muted small mb-0">
                                    {{ $material->created_at->diffForHumans() }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

                <div class="panel mt-4">

    <div class="panel-header d-flex justify-content-between align-items-center">

        <h2 class="h5 mb-0">
            Stock Ledger History
        </h2>

        <span class="badge bg-primary">
            {{ $stockLedgers->count() }} Transactions
        </span>

    </div>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>#</th>
                    <th>Date</th>
                    <th>Transaction</th>
                    <th>Qty In</th>
                    <th>Qty Out</th>
                    <th>Balance</th>
                    <th>User</th>
                    <th>Remarks</th>

                </tr>

            </thead>

            <tbody>

                @forelse($stockLedgers as $ledger)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $ledger->created_at->format('d M Y') }}
                        </td>

                        <td>

                            @switch($ledger->transaction_type)

                                @case('purchase')
                                    <span class="badge bg-success">Purchase</span>
                                    @break

                                @case('dispatch')
                                    <span class="badge bg-warning text-dark">Dispatch</span>
                                    @break

                                @case('consumption')
                                    <span class="badge bg-info">Consumption</span>
                                    @break

                                @case('wastage')
                                    <span class="badge bg-danger">Wastage</span>
                                    @break

                                @case('opening_stock')
                                    <span class="badge bg-primary">Opening Stock</span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($ledger->transaction_type) }}
                                    </span>

                            @endswitch

                        </td>

                        <td class="text-success fw-bold">

                            @if($ledger->qty_in > 0)
                                <span class="text-success fw-bold">
                                    +{{ number_format($ledger->qty_in,3) }}
                                </span>
                            @else   
                                -
                            @endif

                        </td>

                        <td class="text-danger fw-bold">

                           @if($ledger->qty_out > 0)
                                <span class="text-danger fw-bold">
                                    -{{ number_format($ledger->qty_out,3) }}
                                </span>
                            @else
                                -
                            @endif

                        </td>

                        <td class="fw-bold">
                            {{ number_format($ledger->balance_after,3) }}
                            {{ $material->unit }}
                        </td>

                        <td>
                            {{ $ledger->user->name ?? '-' }}
                        </td>

                        <td>
                            {{ $ledger->remarks }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center text-muted py-4">

                            No stock ledger history found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

        </div>

    

    </main>

    <style>
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            color: #fff;
            font-size: 38px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
        }
    </style>

@endsection