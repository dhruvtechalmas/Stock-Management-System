@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-truck"></i>
                    </span>

                    <div>

                        <p class="eyebrow mb-1">
                            Transaction
                        </p>

                        <h1 class="h3 mb-1">
                            Material Dispatch
                        </h1>

                    </div>

                </div>

            </div>

            <div class="d-flex gap-2 mb-4">

                <button class="btn btn-outline-secondary dispatch-tab" data-target="pendingSection">
                    Pending ({{ $pendingRequests->count() }})
                </button>

                <button class="btn btn-outline-secondary dispatch-tab" data-target="approvedSection">
                    Approved ({{ $approvedDispatches->count() }})
                </button>


                <button class="btn btn-outline-secondary dispatch-tab" data-target="dispatchedSection">
                    Dispatched ({{ $dispatched->count() }})
                </button>

                <button class="btn btn-outline-secondary dispatch-tab" data-target="partialSection">
                    Partial Dispatched ({{ $partialApprovedRequests->count() }})
                </button>

                <button class="btn btn-outline-secondary dispatch-tab" data-target="receivedSection">
                    Received ({{ $received->count() }})
                </button>

                <button class="btn btn-outline-secondary dispatch-tab" data-target="discrepancySection">
                    Discrepancy ({{ $discrepancy->count() }})
                </button>

                <button class="btn btn-outline-secondary dispatch-tab" data-target="rejectedSection">
                    Rejected ({{ $rejected->count() }})
                </button>

            </div>


            {{-- pendingRequests Table --}}
            <section id="pendingSection" class="mb-5">

                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="fw-bold mb-0">
                                Pending Requests

                                <span class="badge bg-warning text-dark">
                                    {{ $pendingRequests->count() }}
                                </span>
                            </h4>

                            <input type="text" class="form-control" placeholder="Search..." style="max-width:250px;">

                        </div>

                        <!-- Table -->
                        <div class="table-responsive">

                            <table class="table align-middle" style="table-layout: fixed; width: 100%;">

                                <colgroup>
                                    <col style="width: 13%;">
                                    <col style="width: 14%;">
                                    <col style="width: 14%;">
                                    <col style="width: 12%;">
                                    <col style="width: 12%;">
                                    <col style="width: 35%;">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th>Request No</th>
                                        <th>Requested By</th>
                                        <th>Request Date</th>
                                        <th>Total Items</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($pendingRequests as $request)
                                        <tr>
                                            <td>{{ $request->request_no }}</td>

                                            <td>{{ $request->user->name }}</td>

                                            <td>{{ $request->request_date->format('d M Y') }}</td>

                                            <td>{{ $request->items->count() }}</td>

                                            <td>
                                                <span class="badge bg-warning">
                                                    Pending
                                                </span>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2 flex-nowrap">

                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewRequestModal{{ $request->id }}">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>

                                                    @can('material-dispatch.edit')

                                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#approveModal{{ $request->id }}">

                                                            <i class="bi bi-check-circle"></i>
                                                            Approve

                                                        </button>

                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $request->id }}">

                                                            <i class="bi bi-x-circle"></i>
                                                            Reject

                                                        </button>

                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No Pending Requests Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-3">

                            Total :
                            <strong>{{ $pendingRequests->count() }}</strong>

                        </div>

                    </div>
                </div>



            </section>

            {{-- approvedDispatches Table --}}
            <section id="approvedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Approved Requests

                                </h4>

                            </div>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>

                                        <th>REQUESTED BY</th>

                                        <th>REQUEST DATE</th>

                                        <th>TOTAL ITEMS</th>

                                        <th>STATUS</th>

                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($approvedDispatches as $dispatch)

                                        <tr>

                                            <td>{{ $dispatch->request->request_no ?? '-' }}</td>

                                            <td>{{ $dispatch->request->user->name ?? '-' }}</td>

                                            <td>{{ optional($dispatch->request?->request_date)->format('d M Y') ?? '-' }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>
                                                <span class="badge bg-primary">
                                                    Approved
                                                </span>
                                            </td>

                                            <td>

                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                @can('material-dispatch.edit')
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#dispatchModal{{ $dispatch->id }}">
                                                        Dispatch
                                                    </button>
                                                @endcan
                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No Approved Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $approvedDispatches->count() }}</span>

                        </div>

                    </div>

                </div>
            </section>

            {{-- dispatched Table --}}
            <section id="dispatchedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Dispatched Requests

                                </h4>

                            </div>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="🔍 Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>

                                        <th>REQUESTED BY</th>

                                        <th>REQUEST DATE</th>

                                        <th>TOTAL ITEMS</th>

                                        <th>STATUS</th>

                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($dispatched as $dispatch)

                                        <tr>

                                            <td>{{ $dispatch->request?->request_no ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->user?->name ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->request_date?->format('d M Y') ?? '-' }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>

                                                <span class="badge bg-success">

                                                    Dispatched

                                                </span>

                                            </td>

                                            <td>

                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                @role('Kitchen Staff')
                                                @can('material-dispatch.receive')
                                                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#receiveModal{{ $dispatch->id }}">

                                                        <i class="bi bi-box-arrow-in-down"></i>

                                                        Receive

                                                    </button>
                                                @endcan
                                                @endrole

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No Dispatched Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $dispatched->count() }}</span>

                        </div>

                    </div>

                </div>
            </section>


            {{-- Partial Dispatches Table --}}
            <section id="partialSection">

                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="mb-0 fw-bold">
                                Partial Approve Requests

                                <span class="badge bg-warning text-dark ms-2">
                                    {{ $partialApprovedRequests->count() }}
                                </span>
                            </h4>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>
                                        <th>REQUESTED BY</th>
                                        <th>REQUEST DATE</th>
                                        <th>TOTAL ITEMS</th>
                                        <th>DISPATCHED QTY</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($partialApprovedRequests as $dispatch)

                                        @php
                                            $totalDispatchQty = $dispatch->items->sum('dispatched_qty');
                                        @endphp

                                        <tr>

                                            {{-- Request No --}}
                                            <td>
                                                {{ $dispatch->request?->request_no ?? '-' }}
                                            </td>

                                            {{-- Requested By --}}
                                            <td>
                                                {{ $dispatch->request?->user?->name ?? '-' }}
                                            </td>

                                            {{-- Request Date --}}
                                            <td>
                                                {{ $dispatch->request?->request_date?->format('d M Y') ?? '-' }}
                                            </td>

                                            {{-- Total Items --}}
                                            <td>
                                                {{ $dispatch->items->count() }}
                                            </td>

                                            {{-- Dispatched Qty --}}
                                            <td>

                                                <span class="fw-bold text-primary">

                                                    {{ number_format($totalDispatchQty, 2) }}

                                                </span>

                                            </td>

                                            {{-- Status --}}
                                            <td>

                                                <span class="badge bg-warning">

                                                    Ready To Dispatch

                                                </span>

                                            </td>

                                            {{-- Actions --}}
                                            <td>

                                                {{-- View --}}
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                @can('material-dispatch.edit')

                                                    {{-- Move To Dispatch --}}
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#partialApproveModal{{ $dispatch->id }}">

                                                        <i class="bi bi-truck"></i>

                                                        Dispatch

                                                    </button>

                                                @endcan

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="7" class="text-center py-4">

                                                No Partial Approve Requests Found.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="mt-3">

                            Total :
                            <strong>

                                {{ $partialApprovedRequests->count() }}

                            </strong>

                        </div>

                    </div>

                </div>

            </section>


            {{-- received Table --}}
            <section id="receivedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Received Requests

                                </h4>

                            </div>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="🔍 Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>

                                        <th>REQUESTED BY</th>

                                        <th>REQUEST DATE</th>

                                        <th>TOTAL ITEMS</th>

                                        <th>STATUS</th>

                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($received as $dispatch)

                                        <tr>

                                            <td>{{ $dispatch->request?->request_no ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->user?->name ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->request_date?->format('d M Y') ?? '-' }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>

                                                <span class="badge bg-success">

                                                    Completed

                                                </span>

                                            </td>

                                            <td>

                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm" disabled>

                                                    <i class="bi bi-check-circle"></i>

                                                    Completed

                                                </button>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No Received Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $received->count() }}</span>

                        </div>

                    </div>

                </div>
            </section>

            {{-- Discrepancy Table --}}
            <section id="discrepancySection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Receive & Discrepancy Requests


                                </h4>

                            </div>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>

                                        <th>REQUESTED BY</th>

                                        <th>REQUEST DATE</th>

                                        <th>TOTAL ITEMS</th>

                                        <th>STATUS</th>

                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($discrepancy as $dispatch)

                                        <tr>

                                            <td>{{ $dispatch->request?->request_no ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->user?->name ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->request_date?->format('d M Y') ?? '-' }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>

                                                <span class="badge bg-warning">

                                                    Discrepancy

                                                </span>

                                            </td>

                                            <td>

                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                @role('Admin')
                                                @can('material-dispatch.resolve')
                                                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#resolveModal{{ $dispatch->id }}">

                                                        <i class="bi bi-tools"></i>

                                                        Resolve

                                                    </button>
                                                @endcan
                                                @endrole

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No Discrepancy Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $discrepancy->count() }}</span>

                        </div>

                    </div>

                </div>
            </section>

            {{-- rejected Table --}}
            <section id="rejectedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Rejected Requests

                                </h4>

                            </div>

                            <div style="width:300px;">
                                <input type="text" class="form-control" placeholder="🔍 Filter by request...">
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>REQUEST #</th>

                                        <th>REQUESTED BY</th>

                                        <th>REQUEST DATE</th>

                                        <th>TOTAL ITEMS</th>

                                        <th>STATUS</th>

                                        <th>ACTIONS</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($rejected as $dispatch)

                                        <tr>

                                            <td>{{ $dispatch->request?->request_no ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->user->name ?? '-' }}</td>

                                            <td>{{ $dispatch->request?->request_date->format('d M Y') ?? '-' }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>

                                                <span class="badge bg-danger">

                                                    Rejected

                                                </span>

                                            </td>

                                            <td>

                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewDispatchModal{{ $dispatch->id }}">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No Rejected Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $rejected->count() }}</span>

                        </div>

                    </div>

                </div>
            </section>

            @include('stocks.material-dispatch.models.view')

            @role('Admin')
            @can('material-dispatch.edit')
                @include('stocks.material-dispatch.models.approve')
                @include('stocks.material-dispatch.models.dispatch')
            @endcan
            @can('material-dispatch.resolve')
                @include('stocks.material-dispatch.models.resolve')
            @endcan
            @endrole

            @role('Kitchen Staff')
            @can('material-dispatch.receive')
                @include('stocks.material-dispatch.models.receive')
            @endcan
            @endrole

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ============================
            // Dispatch Tabs
            // ============================
            const buttons = document.querySelectorAll('.dispatch-tab');
            const sections = document.querySelectorAll('[id$="Section"]');

            buttons.forEach(function (button) {

                button.addEventListener('click', function () {

                    buttons.forEach(function (btn) {
                        btn.classList.remove('btn-dark', 'text-white');
                        btn.classList.add('btn-outline-secondary');
                    });

                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-dark', 'text-white');

                    sections.forEach(function (section) {
                        const card = section.querySelector('.card');

                        if (card) {
                            card.classList.remove('border-primary', 'shadow');
                        }
                    });

                    const target = document.getElementById(this.dataset.target);

                    if (target) {
                        const card = target.querySelector('.card');

                        if (card) {
                            card.classList.add('border-primary', 'shadow');
                        }

                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }

                });

            });


            // ============================
            // Refresh when modal is closed
            // ============================
            document.addEventListener('hidden.bs.modal', function (event) {

                const modal = event.target;

                if (
                    modal.id.startsWith('approveModal') ||
                    modal.id.startsWith('rejectModal')
                ) {
                    window.location.reload();
                }

            });

        });
    </script>

@endsection