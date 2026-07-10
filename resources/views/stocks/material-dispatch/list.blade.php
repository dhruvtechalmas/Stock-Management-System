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
                    Partial Dispatched ({{ $partialDispatches->count() }})
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

                <div class="card shadow-sm border-0 rounded-4">

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

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>Request No</th>
                                        <th>Requested By</th>
                                        <th>Request Date</th>
                                        <th>Total Items</th>
                                        <th>Status</th>
                                        <th width="220">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($pendingRequests as $request)

                                        <tr>

                                            <td>
                                                {{ $request->request_no }}
                                            </td>

                                            <td>
                                                {{ $request->user->name }}
                                            </td>

                                            <td>
                                                {{ $request->request_date->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ $request->items->count() }}
                                            </td>

                                            <td>

                                                <span class="badge bg-warning">

                                                    Pending

                                                </span>

                                            </td>

                                            <td>

                                                <!-- View -->

                                                <button class="btn btn-outline-primary btn-sm viewBtn"
                                                    data-id="{{ $request->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <!-- Approve -->

                                                <button class="btn btn-success btn-sm approveBtn" data-id="{{ $request->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#approveModal">

                                                    <i class="bi bi-check-circle"></i>

                                                    Approve

                                                </button>

                                                <!-- Reject -->

                                                <button class="btn btn-danger btn-sm rejectBtn" data-id="{{ $request->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal">

                                                    <i class="bi bi-x-circle"></i>

                                                    Reject

                                                </button>

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
            <div id="approvedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Approved Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $approvedDispatches->count() }}
                                    </span> --}}
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

                                            <td>{{ $dispatch->dispatch_no }}</td>

                                            <td>{{ $dispatch->request->request_no }}</td>

                                            <td>{{ $dispatch->request->user->name }}</td>

                                            <td>{{ optional($dispatch->dispatched_at)->format('d M Y') }}</td>

                                            <td>{{ $dispatch->items->count() }}</td>

                                            <td>
                                                <span class="badge bg-primary">
                                                    Approved
                                                </span>
                                            </td>

                                            <td>
                                                <button class="btn btn-success btn-sm">
                                                    Dispatch
                                                </button>
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

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $approvedDispatches->count() }}</span>

                        </div>

                    </div>

                </div>
            </div>

            {{-- dispatched Table --}}
            <div id="dispatchedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Dispatched Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $dispatched->count() }}
                                    </span> --}}
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

                                    @forelse($dispatched as $request)

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

                                                <button class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm reviewBtn" data-id="{{ $request->id }}">

                                                    <i class="bi bi-search"></i>

                                                    Review

                                                </button>

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

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $dispatched->count() }}</span>

                        </div>

                    </div>

                </div>
            </div>


            {{-- partialDispatches Table --}}
            <div id="partialSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Partial Dispatches Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $partialDispatches->count() }}
                                    </span> --}}
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

                                    @forelse($partialDispatches as $request)

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

                                                <button class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm reviewBtn" data-id="{{ $request->id }}">

                                                    <i class="bi bi-search"></i>

                                                    Review

                                                </button>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                No partialDispatches Requests Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Total : {{ $partialDispatches->count() }}</span>

                        </div>

                    </div>

                </div>
            </div>


            {{-- received Table --}}
            <div id="receivedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Received Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $received->count() }}
                                    </span> --}}
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

                                    @forelse($received as $request)

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

                                                <button class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm reviewBtn" data-id="{{ $request->id }}">

                                                    <i class="bi bi-search"></i>

                                                    Review

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
            </div>

            {{-- Discrepancy Table --}}
            <div id="discrepancySection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Discrepancy Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $discrepancy->count() }}
                                    </span> --}}
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

                                    @forelse($discrepancy as $request)

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

                                                <button class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm reviewBtn" data-id="{{ $request->id }}">

                                                    <i class="bi bi-search"></i>

                                                    Review

                                                </button>

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
            </div>

            {{-- rejected Table --}}
            <div id="rejectedSection">
                <div class="card shadow-sm border-0 rounded-4 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    Rejected Requests

                                    {{-- <span class="badge bg-warning-subtle text-warning ms-2">
                                        {{ $rejected->count() }}
                                    </span> --}}
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

                                    @forelse($rejected as $request)

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

                                                <button class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                    View

                                                </button>

                                                <button class="btn btn-dark btn-sm reviewBtn" data-id="{{ $request->id }}">

                                                    <i class="bi bi-search"></i>

                                                    Review

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
            </div>

            <!-- Modals -->

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const buttons = document.querySelectorAll('.dispatch-tab');
            const sections = document.querySelectorAll('[id$="Section"]');

            buttons.forEach(function (button) {

                button.addEventListener('click', function () {

                    // Active Button
                    buttons.forEach(function (btn) {
                        btn.classList.remove('btn-dark', 'text-white');
                        btn.classList.add('btn-outline-secondary');
                    });

                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-dark', 'text-white');

                    // Remove previous highlight
                    sections.forEach(function (section) {
                        section.querySelector('.card').classList.remove('border-primary', 'shadow');
                    });

                    // Current Section
                    const target = document.getElementById(this.dataset.target);

                    if (target) {

                        target.querySelector('.card').classList.add('border-primary', 'shadow');

                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });

                    }

                });

            });

        });
    </script>

@endsection