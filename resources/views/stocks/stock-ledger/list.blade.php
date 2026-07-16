@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            {{-- Page Heading --}}
            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-journal-text"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Reports</p>
                        <h1 class="h3 mb-1">Stock Ledger</h1>
                    </div>

                </div>

            </div>

            {{-- Filter Panel --}}
            <section class="panel mb-4">

                <div class="panel-header">

                    <h5 class="mb-0">
                        Filter Stock Ledger
                    </h5>

                </div>

                <div class="panel-body">

                    <form method="GET" action="{{ route('stock-ledger.index') }}">

                        <div class="row">

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    Material
                                </label>

                                <select name="material_id" class="form-select">

                                    <option value="">
                                        All Materials
                                    </option>

                                    @foreach($materials as $material)

                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>

                                            {{ $material->material_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    Transaction
                                </label>

                                <select name="transaction_type" class="form-select">

                                    <option value="">
                                        All Transactions
                                    </option>

                                    <option value="purchase" {{ request('transaction_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    <option value="dispatch" {{ request('transaction_type') == 'dispatch' ? 'selected' : '' }}>Dispatch</option>
                                    <option value="receive" {{ request('transaction_type') == 'receive' ? 'selected' : '' }}>Receive</option>
                                    <option value="consumption" {{ request('transaction_type') == 'consumption' ? 'selected' : '' }}>Consumption</option>
                                    <option value="wastage" {{ request('transaction_type') == 'wastage' ? 'selected' : '' }}>Wastage</option>
                                    <option value="adjustment" {{ request('transaction_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>

                                </select>

                            </div>

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    User
                                </label>

                                <select name="created_by" class="form-select">

                                    <option value="">All Users</option>

                                    @foreach($users as $user)

                                        <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>

                                            {{ $user->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    From Date
                                </label>

                                <input type="text" id="from_date" name="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="Select From Date">

                            </div>

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    To Date
                                </label>

                                <input type="text" id="to_date" name="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="Select To Date">

                            </div>

                            <div class="col-md-2 d-flex align-items-end mb-3">

                                <button type="submit" class="btn btn-primary me-2 w-100">

                                    <i class="bi bi-search"></i>
                                    Filter

                                </button>

                                <a href="{{ route('stock-ledger.index') }}" class="btn btn-outline-secondary w-100">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </section>

            {{-- Ledger Table --}}
            <section class="panel">

                {{-- <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Ledger"
                            data-table-search="stockLedgerTable">

                    </div>

                </div> --}}

                <div class="table-responsive">

                    <table class="table align-middle mb-0" id="stockLedgerTable" data-searchable-table>

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Date</th>
                                <th>Material</th>
                                <th>Transaction</th>
                                <th>Qty In</th>
                                <th>Qty Out</th>
                                <th>Balance</th>
                                <th>By</th>
                                <th>Remarks</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($ledgers as $ledger)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <i class="bi bi-calendar3 text-primary me-1"></i>

                                        {{ $ledger->transaction_date->format('d M Y') }}

                                    </td>

                                    <td>

                                        {{ $ledger->material?->material_name ?? 'N/A' }}

                                    </td>

                                    <td>

                                        @php
                                            $badge = match ($ledger->transaction_type) {
                                                'purchase' => 'bg-success',
                                                'dispatch' => 'bg-warning text-dark',
                                                'receive' => 'bg-info',
                                                'consumption' => 'bg-primary',
                                                'wastage' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp

                                        <span class="badge {{ $badge }}">

                                            {{ ucfirst($ledger->transaction_type) }}

                                        </span>

                                    </td>

                                    <td class="text-success fw-semibold">

                                        {{ $ledger->qty_in > 0 ? '+' . number_format($ledger->qty_in, 3) : '-' }}

                                    </td>

                                    <td class="text-danger fw-semibold">

                                        {{ $ledger->qty_out > 0 ? '-' . number_format($ledger->qty_out, 3) : '-' }}

                                    </td>

                                    <td class="fw-bold">

                                        {{ number_format($ledger->balance_after, 3) }}

                                    </td>

                                    <td>

                                        {{ $ledger->createdBy?->name ?? 'System' }}

                                    </td>

                                    <td>

                                        {{ $ledger->remarks ?? '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center text-muted py-5">

                                        No Stock Ledger Records Found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-end mt-3">

                    {{ $ledgers->links('pagination::bootstrap-4') }}

                </div>

            </section>

        </div>

    </main>

@endsection